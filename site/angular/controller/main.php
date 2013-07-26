<?
class controller_main extends controller_base {
	function __construct($o) {
		$this->root_path = route::get('Home');
		parent::__construct($o);
		$this->cls = util::explode_pop('_', __CLASS__);
   	}

	function index() {
	}

	function nav() {
		$this->urls = [
			'Home' => route::get('Home'),
			'Bar' => route::get('Bar'),
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
		]);
		$odd_field = new field('checkbox', [ 
			'ng-model'  => 'odd',
			'label'     => 'Odd',
		]);
		$lucky_field = new field('checkbox', [ 
			'ng-model'  => 'lucky',
			'label'     => 'Lucky',
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

	function data() {
		json(range(0, 200));
	}

}
