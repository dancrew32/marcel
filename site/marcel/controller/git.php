<?
class controller_git extends controller_base {
	function __construct($o) {
		$this->root_path = route::get('Git Home');
		auth::only(['git']);
		$this->git = git::open(config::$setting['root_dir']);
		parent::__construct($o);
   	}

	function main() {
		$this->count = $this->git->commit_count();
		app::title('Git');
   	}

	function notification() {
		$notes = [
			'stage', 
			'unstage',
			'push',
			'pull',
			'fetch',
			'branch_add',
			'branch_delete',
			'branch_checkout',
			'submodule_add',
			'submodule_delete',
			'commit',
		];

		$this->notifications = [];
		foreach ($notes as $note) {
			if ($success = note::get("git:{$note}:success"))
				$this->notifications[] = html::alert($success, ['type' => 'success']);
			if ($error = note::get("git:{$note}:failure"))
				$this->notifications[] = html::alert($error, ['type' => 'error']);
		}
	}


/*
 * STATUS
 */
	function status() {
		$status = $this->git->status();
		$this->staged    = take($status, 'staged');
		$this->deleted   = take($status, 'deleted');
		$this->modified  = take($status, 'modified');
		$this->untracked = take($status, 'untracked');
	}

	function status_staged($o) {
		$this->paths  = take($o, 'paths');
		$this->status = 'staged';
		$this->color_class = 'success';
	}

	function status_deleted($o) {
		$this->paths  = take($o, 'paths');
		$this->status = 'deleted';
		$this->color_class = 'error';
	}

	function status_modified($o) {
		$this->paths  = take($o, 'paths');
		$this->status = 'modified';
		$this->color_class = 'plain';
	}

	function status_untracked($o) {
		$this->paths  = take($o, 'paths');
		$this->status = 'untracked';
		$this->color_class = 'info';
	}


/*
 * FILE
 */
	function file($o) {
		$this->path = take($o, 'path');
		$this->path_trunc = util::truncate($this->path, 20);
		$this->stage = $this->unstage = false;
		$this->color_class = take($o, 'color_class', 'plain');

		switch(take($o, 'status')) {
			case 'staged':
				$this->unstage = route::get('Git Unstage', ['files' => $this->path]);
				$this->title = $this->path;
				break;
			case 'untracked':
			case 'modified':
			case 'deleted':
				$this->stage = route::get('Git Stage', ['files' => $this->path]);
				//$this->reset = route::get('Git Reset', ['files' => $this->path]);
				$diff = $this->git->diff($this->path);
				$this->title = $diff ? h(nl2br(ansi::to_html($diff))) : $this->path;
				break;
		}
	}


/*
 * ORIGIN
 */
	function origin() {
		$this->ahead = $this->git->ahead_origin();
		$this->push_url = route::get('Git Push', ['branch' => 'master']);
		$this->pull_url = route::get('Git Pull', ['branch' => 'master']);
		$this->fetch_url = route::get('Git Fetch', ['branch' => 'master']);
	}


/*
 * BRANCH
 */
	function branches() {
		$this->branches = $this->git->list_branches();
		$this->current_branch = $this->git->active_branch();
		if (AJAX)
			json($this->branches);
	}

	function branch($o) {
		$current_branch = take($o, 'current_branch', $this->git->active_branch());
		$this->branch = take($o, 'branch');
		$this->is_current = $current_branch == $this->branch;
		$this->branch_url = "{$this->git->github_url()}/tree/{$this->branch}";
		$this->delete_url = $this->branch != 'master' 
			? route::get('Git Branch Delete', ['branch' => $this->branch]) 
			: false;
		$this->checkout_url = route::get('Git Branch Checkout', ['branch' => $this->branch]);
	}

	function branch_add() {
		$branch_name = take_post('branch_name');
		try { 
			$this->git->create_branch($branch_name);
			note::set('git:'.__FUNCTION__.':success', h("Added new branch: \"{$branch_name}\""));
		} catch (Exception $e) {
			note::set('git:'.__FUNCTION__.':failure', git::error($e));
		}
		$this->redir();
	}

	function branch_delete($o) {
		$branch = take($o, 'branch');	
		if (!$branch || $branch == 'master')
			$this->redir();

		try {
			$force = false;
			$ok = $this->git->delete_branch($branch, $force);
			note::set('git:'.__FUNCTION__.':success', h("Deleted branch: \"{$branch}\""));
		} catch (Exception $e) {
			note::set('git:'.__FUNCTION__.':failure', git::error($e));
		}
		$this->redir();
	}

	function branch_checkout($o) {
		$branch = take($o, 'branch');	
		if (!$branch)
			$this->redir();

		try {
			$this->git->checkout($branch);
			note::set('git:'.__FUNCTION__.':success', h("Checked out \"{$branch}\""));
		} catch (Exception $e) {
			note::set('git:'.__FUNCTION__.':failure', git::error($e));
		}
		$this->redir();
	}

	# no view
	function branch_add_form($o) {
		$this->form = new form;
		$this->form->open(route::get('Git Branch Add'), 'post', [
			'class' => 'last', 
		]);
		$this->_build_branch_add_form();
		$submit_options = [
			'text' => 'Add Branch',
			'icon' => 'plus',
			'data-loading-text' => html::verb_icon('Adding Branch', 'plus'),
		];
		$this->form->add(new field('submit', $submit_options));
		echo $this->form;
	}


/*
 * PUSH
 */
	function push($o) {
		$branch = take($o, 'branch');	
		if (!$branch)
			$this->redir();

		try {
			$this->git->push($branch);
			note::set('git:'.__FUNCTION__.':success', h("Successfully pushed {$branch} to origin/{$branch}"));
		} catch (Exception $e) {
			note::set('git:'.__FUNCTION__.':failure', git::error($e));
		}
		$this->redir();
	}


/*
 * PULL
 */
	function pull($o) {
		$branch = take($o, 'branch');	
		if (!$branch)
			$this->redir();

		try {
			$this->git->pull($branch);
			note::set('git:'.__FUNCTION__.':success', h("Successfully pulled from origin/{$branch} into {$this->git->active_branch()}"));
		} catch (Exception $e) {
			note::set('git:'.__FUNCTION__.':failure', git::error($e));
		}
		$this->redir();
	}


/*
 * FETCH
 */
	function fetch($o) {
		$branch = take($o, 'branch');	
		if (!$branch)
			$this->redir();

		try {
			$this->git->fetch($branch);
			note::set('git:'.__FUNCTION__.':success', h("Successfully fetched from origin/{$branch}"));
		} catch (Exception $e) {
			note::set('git:'.__FUNCTION__.':failure', git::error($e));
		}
		$this->redir();
	}


/*
 * LOG
 */
	function log_simple($o) {
		$count = take($o, 'count', 20);
		$this->commits = $this->git->log_simple($count);
		$this->after_head = false;
	}

	function log_simple_row($o) {
		$commit = take($o, 'commit');
		$this->after_head  = take($o, 'after_head', false);
		$this->hash        = take($commit, 'hash');
		$this->hash_url    = $this->git->github_commit_url($this->hash);
		$this->is_head     = take($commit, 'is_head', false);
		$this->message     = take($commit, 'message');
		$this->label_class = $this->is_head 
			? 'success' 
			: ($this->after_head ? 'muted' : 'warning');
	}


/*
 * ADD (STAGE)
 */
	function stage($o) {
		$files = take($o, 'files');
		if (!$files)
			$this->redir();

		$files = explode(',', $files);
		try {
			$this->git->stage($files);
			note::set('git:'.__FUNCTION__.':success', h("Staged ". util::list_english($files)));
		} catch (Exception $e) {
			note::set('git:'.__FUNCTION__.':failure', git::error($e));
		}
		$this->redir();
	}


/*
 * RM (UNSTAGE)
 */
	function unstage($o) {
		$files = take($o, 'files');
		if (!$files)
			$this->redir();

		$files = explode(',', $files);
		try {
			$this->git->unstage($files);
			note::set('git:'.__FUNCTION__.':success', h("Unstaged ". util::list_english($files)));
		} catch (Exception $e) {
			note::set('git:'.__FUNCTION__.':failure', git::error($e));
		}
		$this->redir();
	}


/*
 * RESET
 */
	function reset($o) {
		$files = take($o, 'files');
		if (!$files)
			$this->redir();

		$files = explode(',', $files);
		try {
			$this->git->reset($files);
			note::set('git:'.__FUNCTION__.':success', h("Reset ". util::list_english($files)));
		} catch (Exception $e) {
			note::set('git:'.__FUNCTION__.':failure', git::error($e));
		}
		$this->redir();
	}


/*
 * SUBMODULE
 */
	function submodules() {
		$this->submodules = $this->git->submodules();
	}

	function submodule($o) {
		$this->path = take($o, 'path');
		$this->url = take($o, 'url', '#');
		$this->delete_url = route::get('Git Submodule Delete', ['path' => $this->path]);
	}

	function submodule_add() {
		$source = take_post('source');
		$alias  = take_post('alias');
		try {
			$this->git->submodule_add($source, $alias);
			note::set('git:'.__FUNCTION__.':success', h("Added submodule \"{$alias}\" to vendor/{$alias}"));
		} catch (Exception $e) {
			note::set('git:'.__FUNCTION__.':failure', git::error($e));
		}
		$this->redir();
	}

	function submodule_delete($o) {
		$path = take($o, 'path');
		try {
			$this->git->submodule_delete($path);
			$folder_name = util::explode_pop('/', $path);
			note::set('git:'.__FUNCTION__.':success', h("Deleted submodule \"{$folder_name}\" from {$path}"));
		} catch (Exception $e) {
			note::set('git:'.__FUNCTION__.':failure', git::error($e));
		}
		$this->redir();
	}

	# no view
	function submodule_add_form($o) {
		$this->form = new form;
		$this->form->open(route::get('Git Submodule Add'), 'post', [
			'class' => 'last', 
		]);
		$this->_build_submodule_add_form();
		$submit_options = [
			'text' => 'Add Submodule',
			'icon' => 'plus',
			'data-loading-text' => html::verb_icon('Adding Submodule', 'plus'),
		];
		$this->form->add(new field('submit', $submit_options));
		echo $this->form;
	}


/*
 * COMMIT
 */
	function commit() {
		$commit = trim(take_post('commit'));
		if (!$commit)
			$this->redir();

		try {
			preg_match("/(?P<hash>\b[0-9a-f]{5,40}\b)/", $this->git->commit($commit), $matches);
			$hash = count($matches) ? ': '. take($matches, 'hash') : '';
			note::set('git:'.__FUNCTION__.':success', "Created commit{$hash}");
		} catch (Exception $e) {
			note::set('git:'.__FUNCTION__.':failure', git::error($e));
		}
		$this->redir();
	}

	# no view
	function commit_form() {
		$this->form = new form;
		$this->form->open(route::get('Git Commit'), 'post', [
			'class' => 'last', 
		]);
		$status = $this->git->status();
		$staged_count = count(take($status, 'staged', []));
		$this->_build_commit_form($staged_count);
		$submit_options = [
			'text' => 'Commit',
			'icon' => 'ok',
			'data-loading-text' => html::verb_icon('Committing', 'ok'),
		];
		if (!$staged_count)
			$submit_options['disabled'] = true;
		$this->form->add(new field('submit', $submit_options));
		echo $this->form;
	}


/*
 * FORMS
 */
	private function _build_commit_form($staged_count) {

		# Commit Message
		$commit_group = [ 'label' => 'Message', 'class' => null ];
		$commit_help  = new field('help', [ 'text' => null ]);
		$commit_field_options = [
			'name'         => 'commit', 
			'class'        => 'input-block-level',
			'autocomplete' => false,
		];
		if (!$staged_count)
			$commit_field_options['disabled'] = true;
		$commit_field = new field('textarea', $commit_field_options);

		# Build Form
		$this->form
			->group($commit_group, $commit_field, $commit_help)
			;

	}

	private function _build_submodule_add_form() {

		# Source
		$source_group = [ 'label' => 'Source', 'class' => null ];
		$source_help  = new field('help', [ 'text' => null ]);
		$source_field = new field('input', [
			'name'         => 'source', 
			'class'        => 'input-block-level required',
			'placeholder'  => 'e.g. "https://github.com/ircmaxell/filterus.git"',
		]);

		# Alias
		$alias_group = [ 'label' => 'Alias', 'class' => null ];
		$alias_help  = new field('help', [ 'text' => null ]);
		$alias_field = new field('input', [
			'name'         => 'alias', 
			'class'        => 'input-block-level required',
			'placeholder'  => 'e.g. vendor/"<what-you-write-here>"',
		]);

		# Build Form
		$this->form
			->fieldset('Add A Submodule')
			->group($source_group, $source_field, $source_help)
			->group($alias_group, $alias_field, $alias_help)
			;

	}

	private function _build_branch_add_form() {

		# Source
		$name_group = [ 'label' => 'Branch Name', 'class' => null ];
		$name_help  = new field('help', [ 'text' => null ]);
		$name_field = new field('input', [
			'name'         => 'branch_name', 
			'class'        => 'input-block-level required',
			'placeholder'  => 'e.g. "my_branch"',
		]);

		# Build Form
		$this->form
			->fieldset('Add A Branch')
			->group($name_group, $name_field, $name_help)
			;

	}
}
