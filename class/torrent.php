<?
class torrent {

	# apt-get install transmission-daemon

	private $options = [];
	private $rpc;
	public $results  = [];

	function __construct(array $options = []) {
		require_once VENDOR_DIR .'/transmission/class/TransmissionRPC.class.php';
		$this->options = array_merge([
			'mode'             => 'transmission',
			'rpc_url'          => 'localhost:9091/transmission/rpc',
			'target'           => TMP_DIR."/torrent",
			'rss'              => null, # e.g. http://rss.thepiratebay.sx/205 (http://thepiratebay.sx/rss)
			'formats_allowed'  => [],   # e.g. ['xvid', 'mkv', 'mp3']
			'total_per_search' => null,
			'username'         => null,
			'password'         => null,
		], $options);
		return $this;
	}

	function get_rss() {
		$rss = $this->options['rss'];
		if (!$rss) return false;
		return xml::parse(remote::get($rss)->get_data());
	}

	function find_in_rss(array $search = []) {
		$rss = $this->get_rss();
		if (!$rss) return false;

		# Test each channel
		$search_hit = [];
		foreach ($rss->channel->item as $item) {

			# search
			foreach ($search as $s) {
				$already_searched = $this->options['total_per_search'] 
					&& isset($search_hit[$s]) 
					&& $search_hit[$s] >= $this->options['total_per_search'];
				if ($already_searched) continue;

				$hit = stristr($item->link, $s);
				if (!$hit) continue;

				if (!count($this->options['formats_allowed'])) {
					if (!isset($this->results[$s]))
						$this->results[$s] = [];
					$this->results[$s][] = $item; # match found
					if (!isset($search_hit[$s]))
						$search_hit[$s] = 0;
					$search_hit[$s]++;
					continue;
				}

				# ensure allowed formats
				foreach ($this->options['formats_allowed'] as $fa) {
					if (!stristr($item->link, $fa)) continue;
					if (!isset($this->results[$s]))
						$this->results[$s] = [];
					$this->results[$s][] = $item; # match found
					if (!isset($search_hit[$s]))
						$search_hit[$s] = 0;
					$search_hit[$s]++;
				}
			}
		}
		return $this;
	}

	function init() {
		if (!count($this->results)) return false;

		$this->rpc = new TransmissionRPC(
			$this->options['rpc_url'],
			$this->options['username'],
			$this->options['password']
		);

		$added = [];
		foreach ($this->results as $category => $items) {
			foreach ($items as $item) {
				$target = take($this->options, 'target') ."/{$category}";
				try {
					# transmission client takes over
					echo "trying: {$item->link}\n";
					$result = $this->rpc->add((string) $item->link, $target); 
					$added[] = $result->result;
				} catch (Exception $e) {
					throw new Exception('torrent_error: ' . $e->getMessage());
				}
			}
		}

		return $added;
	}

	function stop(array $ids = []) {
		if (!$this->rpc) return false;
		return $this->rpc->stop($ids);
	}

	function start(array $ids = []) {
		if (!$this->rpc) return false;
		return $this->rpc->start($ids);
	}

	function stats() {
		if (!$this->rpc) return false;
		return $this->rpc->sstats()->arguments;
	}

	function session() {
		if (!$this->rpc) return false;
		return $this->rpc->sget()->arguments;
	}

	function get(array $options = []) {
		if (!$this->rpc) return [];
		$options = array_merge([
			'ids'    => [],
			'fields' => [],
		], $options);
		$args = $this->rpc->get($options['ids'], $options['fields'])->arguments;
		return take($args, 'torrents', []);
	}

	# self::tv_season_range(5, 12) will yeild [s05e01, s05e02, .... s05e12]
	static function tv_season_range($season=1, $max_episode=1) {
		$season = str_pad($season, 2, '0', STR_PAD_LEFT);
		return array_map(function($episode) use($season) { 
			return "s{$season}e". str_pad($episode, 2, '0', STR_PAD_LEFT);
		}, range(1, $max_episode));
	}

	# self::tv_show_range(9, 12) will yeild [s01e01, s01e02, .... s09e12]
	static function tv_show_range($max_season=1, $max_episode=1) {
		$out = [];
		for ($i = 1; $i < $max_season + 1; $i++)
			$out = array_merge_recursive($out, array_values(self::tv_season_range($i, $max_episode)));
		return $out;
	}

}
