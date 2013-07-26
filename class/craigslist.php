<?
class craigslist {

	const RESTRICTION_URL = 'http://www.craigslist.org/about/help/html_in_craigslist_postings/details';

	private $orig_html;
	private $inlined_html;

	function __construct($orig_html) {
		$this->orig_html = $orig_html;
		return $this;
	}

	static function get_restricted_html_rules() {
		$dom = dom::get_html(self::RESTRICTION_URL);	
		$rule_table_rows = $dom->find('#postingbody tbody tr');

		$rules = [];
		foreach ($rule_table_rows as $k => $r) {
			if (!$k) continue; # skip headers
			$r = dom::set_html($r);
			$html = html_entity_decode($r->find('td', 0)->innertext);
			$allowed = html_entity_decode($r->find('td', 1)->innertext);
			$allowed = explode('<br>', $allowed);

			$out_allowed = [];
			foreach ($allowed as $a) {
				$allowed_parts = explode('=', $a);
				if (!isset($allowed_parts[0])) continue;

				$attr = dom::set_html($allowed_parts[0]);
				if (!isset($allowed_parts[1]{0})) continue;

				$properties = array_map(function($hit) {
					return preg_replace('/<([^>]*)>/', '$1', $hit);
				}, array_map('trim', explode(',', rtrim(ltrim($allowed_parts[1], '['), ']'))));

				$out_allowed[trim($attr->plaintext)] = $properties;
			}

			$html = preg_match_all('/<([^>]*)>/', $html, $matches);
			foreach ($matches[1] as $m)
				$rules[$m] = $out_allowed;
		}

		return $rules;

	}

	function make_inline() {
		$this->inlined_html = html::inline_convert($this->orig_html);
		return $this;
	}

	function get_inline() {
		$this->make_inline();
		return $this->inlined_html;
	}

	function get_inline_attributed() {
		// TODO: this is a hot mess and doesn't work for some reason.
		$this->make_inline();
		$rules = self::get_restricted_html_rules();
		$dom = dom::set_html($this->inlined_html);
		$find = 'html,body,div,small,span,strong,'.implode(',', array_keys($rules));
		foreach ($dom->find($find) as $el) {
			if (!isset($el->style)) continue;
			$properties = explode(';', $el->style);

			foreach ($properties as $prop) {
				$parts = explode(':', $prop);	
				$part_count = count($parts);
				if ($part_count > 2 || $part_count <= 1) continue;
				$converted = css::to_attribute($parts[0], $parts[1]);	

				if (!$converted) continue;
				$el->$converted['attr'] = $converted['value'];
			}
			unset($el->style);
		}
		return $dom->save();
	}


}
