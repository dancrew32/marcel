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

	function log_simple() {
		$this->commits = $this->git->log_simple(25);
	}

	function log_simple_row($o) {
		$commit = take($o, 'commit');
		$this->hash     = take($commit, 'hash');
		$this->hash_url = $this->git->github_commit_url($this->hash);
		$this->message  = take($commit, 'message');
	}

	function file($o) {
		$this->path = take($o, 'path');

		$this->stage   = false;
		$this->unstage = false;

		switch(take($o, 'status')) {
			case 'staged':
				$this->unstage = route::get('Git Unstage', ['files' => $this->path]);
				break;
			case 'untracked':
			case 'modified':
				$this->stage = route::get('Git Stage', ['files' => $this->path]);
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
