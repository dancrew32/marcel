<?
class table {
	public $data;
	public $table_class = 'table ';
	public $hidden_columns = [];
	public $primary_key = 'id';
	public $delete_col = false;
	public $delete_url = '';

	function __construct($data) {
		$this->data = $data;
	}

	function __toString() {
		return $this->render();	
	}

	function render() {
		$keys = $this->keys();

		$html = '<table class="' . $this->table_class . '">';
		$html .= '<thead>';

		if ($this->delete_col)
			$html .= '<th> &nbsp; </th>';

		foreach ($keys as $key)
			$html .= '<th>' . $key . '</th>';
		$html .= '</thead>';

		foreach ($this->data as $row) {
			$cls = !is_object($row) && isset($row['_class']{0}) ? " class=\"{$row['_class']}\"" : '';
			$html .= "<tr{$cls}>";

			if ($this->delete_col) {
				$del_href =	"{$this->delete_url}?{$this->primary_key}={$row[$this->primary_key]}";
				$html .= "<td> <a class=\"icon-trash\" href=\"{$del_href}\"></a> </td>";
			}

			if (is_object($row)) {
				foreach ($row->to_array() as $k => $v) {
					if (in_array($k, $keys))
						$html .= '<td>' . $v . '</td>';
				}
			} else {
				foreach ($row as $k => $v) {
					if (in_array($k, $keys))
						$html .= '<td>' . $v . '</td>';
				}
			}
		}
		$html .= '</tr></table>';

		return $html;
	}

	function hide_columns() {
		$columns_to_hide = func_get_args();

		foreach ($columns_to_hide as $col)
			$this->hidden_columns[$col] = $col;

		return true;
	}

	function delete_column($url) {
		$this->delete_url = $url;
		$this->delete_col = true;
	}

	private function keys() {
		$keys = [];

		if (!isset($this->data[0])) return $keys;
		$row = is_object($this->data[0]) ? $this->data[0]->to_array() : $this->data[0];
		foreach ($row as $key => $value) {
			if (!in_array($key, $this->hidden_columns))
				$keys[] = $key;
		}
		return $keys;
	}
}
