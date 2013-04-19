<?
class controller_cron_job extends controller_base {

	function all($o) {
		$page = take($o['params'], 'page', 1); 
		$this->crons = Cron_Job::all();
		$this->pager = r('common', 'pager', [
			'total' => Cron_Job::total(),
			'rpp'   => 5,
			'page'  => $page,
			'base'  => '/cron/',
		]);
	}

	function view($o) {
		$this->cron = take($o, 'cron');	
	}

	function edit($o) {
		$this->cron = Cron_Job::find_by_id(take($o['params'], 'id'));
		if (!$this->cron) app::redir('/cron');

		if (!$this->is_post) return;
		$this->cron->name = take($_POST, 'name');
		$this->cron->active = take($_POST, 'active', 0);
		$this->cron->script = take($_POST, 'script');
		$this->cron->frequency = take($_POST, 'frequency');
		$this->cron->save();

		app::redir('/cron');
	}

	function add() {
		$cron = new Cron_Job;
		$cron->name = take($_POST, 'name');
		$cron->active = take($_POST, 'active', 0);
		$cron->script = take($_POST, 'script');
		$cron->frequency = take($_POST, 'frequency');
		$ok = $cron->save();
		if ($ok) {
			# set flash success
			app::redir('/cron');
		}

		note::set('cron_job:add', json_encode([
			'cron' => $_POST, 
			'errors' => $cron->get_errors(),
		]));
		app::redir('/cron');
	}

	function delete($o) {
		$id = take($o['params'], 'id');
		if (!$id) app::redir('/cron');

		$cron = Cron_Job::find_by_id($id);
		$cron->delete();

		app::redir('/cron');
	}

	function scripts() {
		$query = take($_POST, 'query');
		$matches = Cron_Job::find_scripts($query);
		json($matches);
	}


/*
 * FORMS
 */
	# no view
	function add_form() {
		$this->form = new form;
		$this->form->open('/cron/add');
		$note = json_decode(note::get('cron_job:add'));
		$this->_build_form(take($note, 'cron'), take($note, 'errors'));
		$this->form->actions(
			new field('submit', ['text' => 'Add'])
		);
		echo $this->form;
	}

	# no view
	function edit_form($o) {
		$cron = Cron_Job::find_by_id(take($o['cron'], 'id'));
		if (!$cron) app::redir('/cron');

		$this->form = new form;
		$this->form->open("/cron/edit/{$cron->id}");
		$this->_build_form($cron);
		$this->form->actions(
			new field('submit', ['text' => 'Edit'])
		);
		echo $this->form;
	}

	private function _build_form($cron=null, $errors=null) {
		app::asset('validate.min', 'js');
		app::asset('view/cron_job.form', 'js');

		$this->form->group([ 
				'label' => 'Name', 
				'class' => take($errors, 'name') ? 'error' : '',
			], 
			new field('input', [ 
				'name'        => 'name', 
				'class'       => 'input-block-level',
				'value'       => h(take($cron, 'name')),
				'placeholder' => h('e.g. "Update Records"'),
			]),
			new field('help', [ 
				'text' => take($errors, 'name'),
			])
		)
		->add('Active', new field('checkbox', [ 
			'name'    => 'active', 
			'checked' => take($cron, 'active'), 
		]))
		->group([
				'label' => 'Script', 
				'class' => take($errors, 'script') ? 'error' : '',
			],
			new field('typeahead', [ 
				'name'           => 'script', 
				'data-api'       => '/cron/scripts',
				'data-provide'   => "typeahead",
				'data-items'     => 5,
				'placeholder'    => h('e.g. cron.<"whatyoutype">.php'),
				'autocomplete'   => false,
				'data-minLength' => 2,
				'class'          => 'cron-script input-block-level',
				'value'          => h(take($cron, 'script')),
			]),
			new field('help', [ 
				'text' => take($errors, 'script'),
			])
		)
		->group([
				'label' => 'Frequency', 
				'class' => take($errors, 'frequency') ? 'error' : '',
			],
			new field('input', [ 
				'name'        => 'frequency',
				'value'       => h(take($cron, 'frequency')),
				'class'       => 'input-block-level',
				'placeholder' => h('e.g. * * * * *'),
			]),
			new field('help', [ 
				'text' => take($errors, 'frequency'),
			])
		);
	}

}
