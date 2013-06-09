<?
class controller_cron_job extends controller_base {

	function __construct($o) {
		$this->root_path = route::get('Cron Home');
		auth::only(['cron_job']);
		parent::__construct($o);
   	}

	function all($o) {
		$page = take($o['params'], 'page', 1); 
		$rpp = 5;
		$this->total = Cron_Job::count();
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
		$this->mode = take($o, 'mode');
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
		$cron = Cron_Job::create($_POST);
		if ($cron) {
			note::set('cron_job:add', $cron->id);
			app::redir($this->root_path);
		}

		$cron->to_note();
		app::redir($this->root_path);
	}

	function edit($o) {
		$this->cron = Cron_Job::find_by_id(take($o['params'], 'id'));
		if (!$this->cron) app::redir($this->root_path);
		if (!POST) return;

		# handle booleans
		$_POST['active'] = take_post('active', 0);

		$ok = $this->cron->update_attributes($_POST);
		if ($ok) {
			note::set('cron_job:edit', $this->cron->id);
			app::redir($this->root_path);
		}

		$this->cron->to_note();
		app::redir(route::get('Cron Edit', ['id' => $this->cron->id]));
	}

	function delete($o) {
		$id = take($o['params'], 'id');
		if (!$id) app::redir($this->root_path);

		$cron = Cron_Job::find_by_id($id);
		if (!$cron) app::redir($this->root_path);

		$cron->delete();
		note::set('cron_job:delete', $cron->id);
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
		$this->form->open(route::get('Cron Add'), 'post', [
			'class' => 'last',
			'id'    => 'cron-add',
		]);
		$this->_build_form($cron);
		$this->form->add(new field('submit_add'));
		echo $this->form;
	}

	# no view
	function edit_form($o) {
		$cron = take($o, 'cron');
		$cron = $cron->from_note();
		if (!$cron) app::redir($this->root_path);

		$this->form = new form;
		$this->form->open(route::get('Cron Edit', ['id' => $cron->id]), 'post', [
			'class' => 'last', 
		]);
		$this->_build_form($cron);
		$this->form->add(new field('submit_update'));
		echo $this->form;
	}

	private function _build_form($cron) {

		# Name
		$cron_name_group = [ 'label' => 'Name', 'class' => $cron->error_class('name') ];
		$cron_name_help  = new field('help', [ 'text' => $cron->take_error('name') ]);
		$cron_name_field = new field('input', [ 
			'name'        => 'name', 
			'class'       => 'input-block-level required',
			'value'       => take($cron, 'name'),
			'placeholder' => 'e.g. "Update Records"',
		]);


		# Script
		$cron_script_group = [ 'label' => 'Script', 'class' => $cron->error_class('script') ];
		$cron_script_help  = new field('help', [ 'text' => $cron->take_error('script') ]);
		$cron_script_field = new field('typeahead', [ 
			'name'           => 'script', 
			'data-api'       => '/cron/scripts',
			'data-typeahead-method' => 'key-get-value',
			'data-items'     => 5,
			'data-minLength' => 2,
			'placeholder'    => 'e.g. cron."whatyoutype".php',
			'class'          => 'cron-script input-block-level required',
			'value'          => take($cron, 'script'),
		]);


		# Frequency
		$cron_frequency_group = [ 'label' => 'Frequency', 'class' => $cron->error_class('frequency') ];
		$cron_frequency_help  = new field('help', [ 'text' => $cron->take_error('frequency') ]);
		$cron_frequency_field = new field('input', [ 
			'name'        => 'frequency',
			'value'       => take($cron, 'frequency'),
			'class'       => 'input-block-level required',
			'placeholder' => 'e.g. * * * * *',
		]);


		# Description
		$cron_description_group = [ 'label' => 'Description', 'class' => $cron->error_class('description') ];
		$cron_description_help  = new field('help', [ 'text' => $cron->take_error('description') ]);
		$cron_description_field = new field('textarea', [ 
			'name'        => 'description',
			'value'       => take($cron, 'description'),
			'class'       => 'input-block-level',
			'placeholder' => 'e.g. "Updates records every hour"',
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
