<?
class controller_cron_job extends controller_base {

	function all($o) {
		$page = take($o['params'], 'page', 1); 
		$rpp = 5;
		$this->total = Cron_Job::total();
		$this->pager = r('common', 'pager', [
			'total' => $this->total,
			'rpp'   => $rpp,
			'page'  => $page,
			'base'  => '/cron/',
		]);
		$this->crons = Cron_Job::find('all', [
			'limit'  => $rpp,
			'offset' => model::get_offset($page, $rpp),
			'order'  => 'active desc, updated_on desc',
		]);
	}

	function view($o) {
		$this->cron = take($o, 'cron');	
		$this->should_run = $this->cron->should_run();
		$this->status_class = '';
		if ($this->cron->active)
			$this->status_class = $this->should_run ? ' label-success' : ' label-info';
	}

	function add() {
		$now  = time::now();
		$cron = new Cron_Job;
		$cron->name       = take($_POST, 'name');
		$cron->active     = take($_POST, 'active', 0);
		$cron->script     = take($_POST, 'script');
		$cron->frequency  = take($_POST, 'frequency');
		$cron->created_on = $now;
		$cron->updated_on = $now;
		$ok = $cron->save();
		if ($ok) {
			note::set('cron_job:add', 1);
			app::redir('/cron');
		}

		note::set('cron_job:form', json_encode([
			'cron' => $_POST, 
			'errors' => $cron->get_errors(),
		]));
		app::redir('/cron');
	}

	function edit($o) {
		$this->cron = Cron_Job::find_by_id(take($o['params'], 'id'));
		if (!$this->cron) app::redir('/cron');
		if (!$this->is_post) return;

		$this->cron->name       = take($_POST, 'name');
		$this->cron->active     = take($_POST, 'active', 0);
		$this->cron->script     = take($_POST, 'script');
		$this->cron->frequency  = take($_POST, 'frequency');
		$this->cron->updated_on = time::now();
		$ok = $this->cron->save();
		if ($ok) {
			note::set('cron_job:edit', 1);
			app::redir('/cron');
		}

		note::set('cron_job:form', json_encode([
			'cron'   => $_POST, 
			'errors' => $this->cron->get_errors(),
		]));

		app::redir("/cron/edit/{$this->cron->id}");
	}

	function delete($o) {
		$id = take($o['params'], 'id');
		if (!$id) app::redir('/cron');

		$cron = Cron_Job::find_by_id($id);
		if (!$cron) app::redir('/cron');

		$cron->delete();
		note::set('cron_job:delete', 1);
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
		$this->form->open('/cron/add', 'post', ['class' => 'last']);
		$note = json_decode(note::get('cron_job:form'));
		$this->_build_form(take($note, 'cron'), take($note, 'errors'));
		$this->form->add(
			new field('submit', ['text' => 'Add', 'icon' => 'plus'])
		);
		echo $this->form;
	}

	# no view
	function edit_form($o) {
		$cron = Cron_Job::find_by_id(take($o['cron'], 'id'));
		if (!$cron) app::redir('/cron');

		$this->form = new form;
		$this->form->open("/cron/edit/{$cron->id}", 'post', ['class' => 'last']);
		$note = json_decode(note::get('cron_job:form'));
		$this->_build_form(take($note, 'cron', $cron), take($note, 'errors'));
		$this->form->add(
			new field('submit', ['text' => 'Edit', 'icon' => 'edit'])
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
				'class'       => 'input-block-level required',
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
				'data-items'     => 5,
				'data-minLength' => 2,
				'placeholder'    => h('e.g. cron.<"whatyoutype">.php'),
				'class'          => 'cron-script input-block-level required',
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
				'class'       => 'input-block-level required',
				'placeholder' => h('e.g. * * * * *'),
			]),
			new field('help', [ 
				'text' => take($errors, 'frequency'),
			])
		);
	}

}
