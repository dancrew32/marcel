<?
class controller_git extends controller_base {
	function __construct($o) {
		$this->root_path = route::get('Git Home');
		auth::only(['git']);
		parent::__construct($o);
		$this->git = git::open(ROOT_DIR);
   	}

	function main() { }

	function status() {
		$this->status = $this->git->status();
	}

	function origin() {
		echo $this->git->ahead_origin() .' commit ahead of origin'; 
	}

	function log_simple() {
		$this->commits = $this->git->log_simple(25);
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

	function file($o) {
		$this->path = take($o, 'path');
		$this->path_trunc = util::truncate($this->path, 20);

		$this->stage   = false;
		$this->unstage = false;

		switch(take($o, 'status')) {
			case 'staged':
				$this->unstage = route::get('Git Unstage', ['files' => $this->path]);
				$this->title = $this->path;
				break;
			case 'untracked':
			case 'modified':
				$this->stage = route::get('Git Stage', ['files' => $this->path]);
				//$this->reset = route::get('Git Reset', ['files' => $this->path]);
				$this->title = h($this->git->run("diff {$this->path}"));
				break;
		}
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
	function commit_form() {
		$this->form = new form;
		$this->form->open(route::get('Git Commit'), 'post', [
			'class' => 'last', 
		]);
		$this->_build_commit_form();
		$this->form->add(new field('submit', [
			'text' => 'Commit',
			'icon' => 'ok',
			'data-loading-text' => html::verb_icon('Committing', 'ok'),
		]));
		echo $this->form;
	}

	private function _build_commit_form() {
		# Commit Message
		$commit_group = [ 'label' => 'Message', 'class' => null ];
		$commit_help  = new field('help', [ 'text' => null ]);
		$commit_field = new field('textarea', [ 
			'name'         => 'commit', 
			'class'        => 'input-block-level',
			'autocomplete' => false,
		]);

		# Build Form
		$this->form
			->group($commit_group, $commit_field, $commit_help)
			;
	}
}
