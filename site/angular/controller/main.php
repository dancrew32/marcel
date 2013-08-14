<?
class controller_main extends controller_base {
	function __construct($o) {
		$this->root_path = route::get('Home');
		parent::__construct($o);
		$this->cls = util::explode_pop('_', __CLASS__);
   	}

	function index() {
	}

	function circle() {
		$this->ctrl = $this->cls.'.'. __FUNCTION__;

	}

	function nav() {
		$this->urls = [
			'Home'   => route::get('Home'),
			'Bar'    => route::get('Bar'),
			'Circle' => route::get('Circle'),
		];
	}

	function test() {
		$this->ctrl = $this->cls.'.'. __FUNCTION__;
		$name_group = [ 'label' => 'Name' ]; 
		$name_field = new field('input', [ 
			'ng-model'     => 'name',
			'value'        => '2',
		]);
		$this->form = new form(['angular' => true]);
		$this->form->open();
		$this->form->group($name_group, $name_field);
	}

	function bar() {
		$this->ctrl = $this->cls.'.'. __FUNCTION__;
		$even_field = new field('checkbox', [ 
			'ng-model'  => 'even',
			'label'     => 'Even',
			'inline'    => true,
		]);
		$odd_field = new field('checkbox', [ 
			'ng-model'  => 'odd',
			'label'     => 'Odd',
			'inline'    => true,
		]);
		$lucky_field = new field('checkbox', [ 
			'ng-model'  => 'lucky',
			'label'     => 'Lucky',
			'inline'    => true,
		]);
		$search_field = new field('text', [ 
			'placeholder' => 'Search',
			'ng-model'    => 'search',
			'required'    => true,
			'ng-pattern'  => '/[0-9]+/',
		]);
		$this->form = new form(['angular' => true]);
		$this->form->open();
		$this->form->group($even_field, $odd_field, $lucky_field);
		$this->form->group($search_field);
	}
	
	function modal() {
	}

	function data() {
		json(range(0, 200));
	}

	function audio() {
		$query = take($_GET, 'query');
		$url = "https://translate.google.com/translate_tts";
		$mp3 = remote::get($url, [
			'ie' => 'UTF-8',
			'q'  => $query,
			'tl' => 'en',
		], [
			'user_agent' => useragent::GOOGLE_CHROME,
		]);

		header("Content-Type: audio/mpeg");
		//header('Content-Length: ' . filesize($track));
		//header('Content-Disposition: inline; filename="lilly.mp3"');
		header('X-Pad: avoid browser bug');
		header('Cache-Control: no-cache');
		//readfile($track);
		die($mp3->get_data());
	}

}
