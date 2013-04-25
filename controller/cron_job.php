<?
class controller_cron_job extends controller_base {

	function __construct($o) {
		$this->root_path = app::get_path('Cron Home');
		auth::check('cron_job_section');
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
			'order'  => 'active desc, updated_at desc',
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
		$cron = new Cron_Job;
		$cron->name        = take($_POST, 'name');
		$cron->script      = take($_POST, 'script');
		$cron->frequency   = take($_POST, 'frequency');
		$cron->description = take($_POST, 'description');
		$cron->active      = take($_POST, 'active', 0);
		$ok = $cron->save();
		if ($ok) {
			note::set('cron_job:add', 1);
			app::redir($this->root_path);
		}

		$cron->to_note();
		app::redir($this->root_path);
	}

	function edit($o) {
		$this->cron = Cron_Job::find_by_id(take($o['params'], 'id'));
		if (!$this->cron) app::redir($this->root_path);
		if (!$this->is_post) return;

		$this->cron->name        = take($_POST, 'name');
		$this->cron->script      = take($_POST, 'script');
		$this->cron->frequency   = take($_POST, 'frequency');
		$this->cron->description = take($_POST, 'description');
		$this->cron->active      = take($_POST, 'active', 0);
		$ok = $this->cron->save();
		if ($ok) {
			note::set('cron_job:edit', 1);
			app::redir($this->root_path);
		}

		$this->cron->to_note();
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
		$cron = new Cron_Job;
		$cron = $cron->from_note();

		$this->form = new form;
		$this->form->open("{$this->root_path}/add#cron-add", 'post', [
			'class' => 'last',
			'id'    => 'cron-add',
		]);
		$this->_build_form($cron);
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
		$cron = take($o, 'cron');
		$cron = $cron->from_note();
		if (!$cron) app::redir($this->root_path);

		$this->form = new form;
		$this->form->open("{$this->root_path}/edit/{$cron->id}", 'post', [
			'class' => 'last', 
		]);
		$this->_build_form($cron);
		$this->form->add(
			new field('submit', [
				'text' => 'Update', 
				'icon' => 'edit',
				'data-loading-text' => h('<i class="icon-edit"></i> Updating&hellip;')
			])
		);
		echo $this->form;
	}

	private function _build_form($cron) {
		app::asset('validate.min', 'js');
		//app::asset('view/cron_job.form', 'js');


		# Name
		$cron_name_group = [ 
			'label' => 'Name', 
			'class' => $cron->error_class('name'),
		];
		$cron_name_field = new field('input', [ 
			'name'        => 'name', 
			'class'       => 'input-block-level required',
			'value'       => h(take($cron, 'name')),
			'placeholder' => h('e.g. "Update Records"'),
		]);
		$cron_name_help = new field('help', [ 
			'text' => $cron->take_error('name'),
		]);


		# Script
		$cron_script_group = [
			'label' => 'Script', 
			'class' => $cron->error_class('script'),
		];
		$cron_script_field = new field('typeahead', [ 
			'name'           => 'script', 
			'data-api'       => '/cron/scripts',
			'data-items'     => 5,
			'data-minLength' => 2,
			'placeholder'    => h('e.g. cron."whatyoutype".php'),
			'class'          => 'cron-script input-block-level required',
			'value'          => h(take($cron, 'script')),
		]);
		$cron_script_help = new field('help', [ 
			'text' => $cron->take_error('script'),
		]);


		# Frequency
		$cron_frequency_group = [
			'label' => 'Frequency', 
			'class' => $cron->error_class('frequency'),
		];
		$cron_frequency_field = new field('input', [ 
			'name'        => 'frequency',
			'value'       => h(take($cron, 'frequency')),
			'class'       => 'input-block-level required',
			'placeholder' => h('e.g. * * * * *'),
		]);
		$cron_frequency_help = new field('help', [ 
			'text' => $cron->take_error('frequency'),
		]);


		# Description
		$cron_description_group = [
			'label' => 'Description', 
			'class' => $cron->error_class('description'),
		];
		$cron_description_field = new field('textarea', [ 
			'name'        => 'description',
			'value'       => h(take($cron, 'description')),
			'class'       => 'input-block-level',
			'placeholder' => h('e.g. "Updates records every hour"'),
		]);
		$cron_description_help = new field('help', [ 
			'text' => $cron->take_error('description'),
		]);


		# Active
		$cron_active_field = new field('checkbox', [ 
			'name'    => 'active',
			'checked' => take($cron, 'active'),
			'label'   => 'Activate',
			'inline'  => true,
		]);

		$this->form
			->group($cron_name_group, $cron_name_field, $cron_name_help)
			->group($cron_script_group, $cron_script_field, $cron_script_help)
			->group($cron_frequency_group, $cron_frequency_field, $cron_frequency_help)
			->group($cron_description_group, $cron_description_field, $cron_description_help)
			->group($cron_active_field);
	}

}
