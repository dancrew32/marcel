<?
class controller_filter extends controller_base {
	function __construct($o) {
		$this->root_path = route::get('Filter Home');
		# auth::only(['filter']);
		parent::__construct($o);
   	}

	function main() {
		$this->pass = [
			'alnum'  => [
				'value'   => '1234abcd',
				'display' => '"1234abcd"',
			],
			'array'  => [
				'value'   => [1, 'a', 4, 'f'],
				'display' => "[1, 'a', 4, 'f']",
			],
			'bool'   => [
				'value'   => (bool) false,
				'display' => 'false',
			],
			'float'  => [
				'value'   => 52.65,
				'display' => 52.65,
			],
			'int'    => [
				'value'   => 42,
				'display' => 42,
			],
			'ip'     => [
				'value'   => '127.0.0.1',
				'display' => '"127.0.0.1"', 
			],
			'object' => [
				'value'   => (object) ['a' => 'Ay', 'b' => 'Bee'],
				'display' => "(object) ['a' => 'Ay', 'b' => 'Bee']",
			],
			'regex'  => [
				'value'   => '/[^0-9]/',
				'display' => '"/[^0-9]/"',
			],
			'string' => [
				'value'   => 'cheese',
				'display' => '"cheese"',
			],
			'url'    => [
				'value'   => 'http://danmasq.com',
				'display' => '"http://danmasq.com"',
			],
		];

		$this->fail = [
			'alnum'  => [
				'value'   => '**3abc$',
				'display' => '"**3abc$"',
			],
			'array'  => [
				'value'   => 'blamp.',
				'display' => '"blamp."',
			],
			'bool'   => [
				'value'   => false,
				'display' => 'false',
			],
			'float'  => [
				'value'   => 'foo',
				'display' => '"foo"',
			],
			'int'    => [
				'value'   => 'foo',
				'display' => '"foo"',
			],
			'ip'     => [
				'value'   => 'what is an ip?',
				'display' => '"what is an ip?"', 
			],
			'object' => [
				'value'   => ['a' => 'Ay', 'b' => 'Bee'],
				'display' => "['a' => 'Ay', 'b' => 'Bee']",
			],
			'regex'  => [
				'value'   => '0',
				'display' => '"0"',
			],
			'string' => [
				'value'   => function() {},
				'display' => '(function() {})',
			],
			'url'    => [
				'value'   => '.com',
				'display' => '".com"',
			],
		];

		$this->options = [
			h('alnum,min,max,default')  => [
				'test'    => 'alnum, min:5, max:10',
				'value'   => 'abcd1234efgh5678',
				'display' => '"abcd1234efgh5678"',
			],
			h('array,min,max,keys,values') => [
				'test'    => 'array,keys:int',
				'value'   => ['a', 'b', 'c', 'd', 'e', 7],
				'display' => "['a', 'b', 'c', 'd', 'e', 7]",
			],
		];
	}
}
