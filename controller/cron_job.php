<?
class controller_cron_job extends controller_base {

	function __construct($o) {
		$this->root_path = app::get_path('Cron Home');
		parent::__construct($o);
   	}

	function all($o) {
		$page = take($o['params'], 'page', 1); 
		$rpp = 5;
		$this->total = Cron_Job::total();
		$this->pager = r('common', 'pager', [
			'total' => $this->total,
			'rpp'   => $rpp,
			'page'  => $page,
			'base'  => "{$this->root_path}/",
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
		$this->exists = $this->cron->script_exists();

		# Display
		$this->status_class = '';
		$this->script_class = 'muted';
		if (!$this->exists) {
			$this->status_class = ' label-important';
			$this->script_class = 'error';
		} elseif ($this->cron->active) {
			$this->status_class = $this->should_run ? ' label-success' : ' label-info';
			$this->script_class = $this->should_run ? 'success' : 'info';
		}
	}

	function add() {
		$now  = time::now();
		$cron = new Cron_Job;
		$cron->name        = trim(take($_POST, 'name'));
		$cron->active      = take($_POST, 'active', 0);
		$cron->script      = trim(take($_POST, 'script'));
		$cron->frequency   = trim(take($_POST, 'frequency'));
		$cron->description = trim(take($_POST, 'description'));
		$cron->created_on  = $now;
		$cron->updated_on  = $now;
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
		if (!$this->cron) app::redir($this->root_path);
		if (!$this->is_post) return;

		$this->cron->name        = trim(take($_POST, 'name'));
		$this->cron->active      = take($_POST, 'active', 0);
		$this->cron->script      = trim(take($_POST, 'script'));
		$this->cron->frequency   = trim(take($_POST, 'frequency'));
		$this->cron->description = trim(take($_POST, 'description'));
		$this->cron->updated_on  = time::now();
		$ok = $this->cron->save();
		if ($ok) {
			note::set('cron_job:edit', 1);
			app::redir($this->root_path);
		}

		note::set('cron_job:form', json_encode([
			'cron'   => $_POST, 
			'errors' => $this->cron->get_errors(),
		]));

		app::redir("{$this->root_path}/edit/{$this->cron->id}");
	}

	function delete($o) {
		$id = take($o['params'], 'id');
		if (!$id) app::redir($this->root_path);

		$cron = Cron_Job::find_by_id($id);
		if (!$cron) app::redir($this->root_path);

		$cron->delete();
		note::set('cron_job:delete', 1);
		app::redir($this->root_path);
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
		$this->form->open("{$this->root_path}/add#cron-add", 'post', [
			'class' => 'last',
			'id'    => 'cron-add',
		]);
		$note = json_decode(note::get('cron_job:form'));
		$this->_build_form(take($note, 'cron'), take($note, 'errors'));
		$this->form->add(
			new field('submit', [
				'text' => 'Add', 
				'icon' => 'plus',
				'data-loading-text' => h('<i class="icon-plus"></i> Adding&hellip;'),
			])
		);
		echo $this->form;
	}

	# no view
	function edit_form($o) {
		$cron = Cron_Job::find_by_id(take($o['cron'], 'id'));
		if (!$cron) app::redir($this->root_path);

		$this->form = new form;
		$this->form->open("{$this->root_path}/edit/{$cron->id}", 'post', [
			'class' => 'last', 
		]);
		$note = json_decode(note::get('cron_job:form'));
		$this->_build_form(take($note, 'cron', $cron), take($note, 'errors'));
		$this->form->add(
			new field('submit', [
				'text' => 'Update', 
				'icon' => 'edit',
				'data-loading-text' => h('<i class="icon-edit"></i> Updating&hellip;')
			])
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
		->group(
			[
				'label' => 'Script', 
				'class' => take($errors, 'script') ? 'error' : '',
			],
			new field('typeahead', [ 
				'name'           => 'script', 
				'data-api'       => '/cron/scripts',
				'data-items'     => 5,
				'data-minLength' => 2,
				'placeholder'    => h('e.g. cron."whatyoutype".php'),
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
		)
		->group([
				'label' => 'Description', 
				'class' => take($errors, 'description') ? 'error' : '',
			],
			new field('textarea', [ 
				'name'        => 'description',
				'value'       => h(take($cron, 'description')),
				'class'       => 'input-block-level',
				'placeholder' => h('e.g. "Updates records every hour"'),
			]),
			new field('help', [ 
				'text' => take($errors, 'description'),
			])
		)
		->group(
			new field('checkbox', [ 
				'name'    => 'active',
				'checked' => take($cron, 'active'),
				'label'   => 'Activate',
				'inline'  => true,
			])
		);
	}

}
