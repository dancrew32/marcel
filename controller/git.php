<?
class controller_git extends controller_base {
	function __construct($o) {
		$this->root_path = route::get('Git Home');
		auth::only(['git']);
		$this->git = git::open(ROOT_DIR);
		parent::__construct($o);
   	}

	function main() {
		$this->count = $this->git->commit_count();
		app::title('Git');
   	}

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


	function origin() {
		$this->ahead = $this->git->ahead_origin();
		$this->push_url = route::get('Git Push', ['branch' => 'master']);
		$this->pull_url = route::get('Git Pull', ['branch' => 'master']);
		$this->fetch_url = route::get('Git Fetch', ['branch' => 'master']);
	}

	function push($o) {
		$branch = take($o['params'], 'branch');	
		if (!$branch)
			$this->redir();

		$ok = $this->git->push($branch);
		if ($ok) {
			# TODO: note
			$this->redir();
		}

		# TODO: note
		$this->redir();
	}

	function pull($o) {
		$branch = take($o['params'], 'branch');	
		if (!$branch)
			$this->redir();

		$ok = $this->git->pull($branch);
		if ($ok) {
			# TODO: note
			$this->redir();
		}

		# TODO: note
		$this->redir();
	}

	function fetch($o) {
		$branch = take($o['params'], 'branch');	
		if (!$branch)
			$this->redir();

		$ok = $this->git->fetch($branch);
		if ($ok) {
			# TODO: note
			$this->redir();
		}

		# TODO: note
		$this->redir();
	}

	function log_simple() {
		$this->commits = $this->git->log_simple(20);
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

	function stage($o) {
		$files = take($o['params'], 'files');
		if (!$files)
			$this->redir();

		$ok = $this->git->stage(explode(',', $files));
		if ($ok) {
			# TODO: note
			$this->redir();
		}

		# TODO: note
		$this->redir();
	}

	function unstage($o) {
		$files = take($o['params'], 'files');
		if (!$files)
			$this->redir();

		$ok = $this->git->unstage(explode(',', $files));
		if ($ok) {
			# TODO: note
			$this->redir();
		}

		# TODO: note
		$this->redir();
	}

	function reset($o) {
		$files = take($o['params'], 'files');
		if (!$files)
			$this->redir();

		$ok = $this->git->reset(explode(',', $files));
		if ($ok) {
			# TODO: note
			$this->redir();
		}

		# TODO: note
		$this->redir();
	}

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
		$ok = $this->git->submodule_add($source, $alias);
		if ($ok) {
			# TODO: note
			$this->redir();
		}

		# TODO: note
		$this->redir();
	}

	function submodule_delete($o) {
		$path = take($o['params'], 'path');
		$ok = $this->git->submodule_delete($path);
		if ($ok) {
			# TODO: note
			$this->redir();
		}

		# TODO: note
		$this->redir();
	}

	function commit() {
		$commit = trim(take_post('commit'));
		if (!$commit)
			$this->redir();

		$ok = $this->git->commit($commit);
		if ($ok) {
			# TODO: note
			$this->redir();
		}

		# TODO: note
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
			->group($source_group, $source_field, $source_help)
			->group($alias_group, $alias_field, $alias_help)
			;

	}
}
