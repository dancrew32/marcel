<?
class controller_file_manager extends controller_base {
	function __construct($o) {
		$this->root_path = route::get('File Manager Home');
		auth::only(['file_manager']);
		parent::__construct($o);
   	}

	function main($o) {
		$this->path = take($o, 'path');
		$this->tree = is_dir(ROOT_DIR."/{$this->path}");
	}

	function explorer($o) {
		$path = take($o, 'path');
		$this->files = util::directory_list("/{$path}", $path);
	}

	function explorer_file($o) {
		$this->file_path = take($o, 'file_path');
		$this->file_name = util::explode_pop(ROOT_DIR, $this->file_path);
		$this->is_file   = is_file($this->file_path);
		$this->is_folder = is_dir($this->file_path);
		$safe_name = ltrim($this->file_name, '/');
		$this->file_url = route::get('File Manager Home', ['path' => $safe_name]);
	}

	function view($o) {
		$this->path = take($o, 'path');
		$full_path = ROOT_DIR."/{$this->path}";
		if (!is_file($full_path)) $this->skip();

		$this->ext = h(pathinfo($full_path, PATHINFO_EXTENSION));
		$content = file_get_contents($full_path);

		$image = mime::is($this->ext, 'image');
		if ($image) {
			times(2, function() { ob_end_clean(); });
			header("Content-Type: {$image}");
			die($content);
		} else {
			switch ($this->ext) {
				//case 'md':
					//$this->content = markdown::render_text($this->content);
					//break;
				case 'php':
				case 'rb':
				case 'js':
				case 'css':
					code::highlight();
					code::vim();
					$this->content = h($content);
					$this->read_only = "<pre><code data-language=\"{$this->ext}\">{$this->content}</code></pre>";
					break;
				default:
					code::highlight();
					$this->content = h($content);
					$this->read_only = "<pre><code data-language=\"generic\">{$this->content}</code></pre>";
			}
		}
	}

	function edit($o) {
		$this->path = take($o, 'path');
		$full_path = ROOT_DIR."/{$this->path}";
		if (!is_file($full_path)) $this->skip();

		if (POST) {
			$file_content = take_post('file_content');
			$saved = file_put_contents($full_path, $file_content);
			if (AJAX)
				json($saved);
			$this->redir($this->path);
		}

		code::vim();

		$this->action = route::$path;
		$this->form = new form;
		$this->form->open(route::get('File Manager Edit', ['path' => $this->path]), 'post', [
			'class' => 'last', 
		]);
		$this->_build_edit_form($full_path);
	}

	function search() {
		$query = take_post('query');
		if (AJAX)
			json(util::find_files($query, 50));
		$this->redir(route::get('File Manager Home', ['path' => ltrim($query, '/')]));
	}

	# no view
	function search_form() {
		$this->form = new form;
		$this->form->open(route::get('File Manager Search'), 'post', [
			'class' => 'last', 
		]);
		$this->_build_search_form();
		echo $this->form;
	}

	function upload($o) {
		$redir = take_post('r');
		if (!count($_FILES)) 
			$this->redir($redir);
		$upload = upload::factory(TMP_DIR.'/upload');
		$upload->file(take($_FILES, 'file'));
		$results = $upload->upload();
		if (mime::in($results['mime'], 'image')) {
			//pd($results);
		}
		$this->redir($redir);
	}

	# no view
	function upload_form($o) {
		$this->form = new form;
		$this->form->open(route::get('File Manager Upload'), 'post', [
			'class' => 'last', 
		]);
		$this->_build_upload_form();
		
		echo $this->form;
	}

	private function _build_upload_form() {

		# File
		$file_group = [ 'label' => 'File', 'class' => null ];
		$file_help  = new field('help', [ 'text' => null ]);
		$file_field = new field('file_simple', [ 
			'name'         => 'file', 
			'text'         => 'Upload',
			'class'        => 'input-block-level',
			'autocomplete' => false,
			'icon'         => 'upload',
		]);

		# File
		$file2_group = [ 'label' => 'File Full', 'class' => null ];
		$file2_help  = new field('help', [ 'text' => null ]);
		$file2_field = new field('file', [ 
			'name'         => 'file2', 
			'text'         => 'Upload',
			'class'        => 'input-block-level',
			'autocomplete' => false,
			'icon'         => 'upload',
		]);

		$redirect_field = new field('hidden', [
			'name'  => 'r',
			'value' => route::$path,
		]);

		# Build Form
		$this->form
			->group($file_group, $file_field, $file_help)
			->group($file2_group, $file2_field, $file2_help)
			->group($redirect_field)
			;

	}

	private function _build_edit_form($full_path) {

		# File
		$editor_field = new field('textarea', [ 
			'name'         => 'file_content', 
			'class'        => 'input-block-level code',
			'data-ext'     => mime::is(pathinfo($full_path, PATHINFO_EXTENSION)),
			'autocomplete' => false,
			'value'        => h(file_get_contents($full_path)),
		]);

		$this->form
			->group($editor_field)
			;
	}

	private function _build_search_form() {

		# Search
		$search_group = [ 'label' => "Search", 'class' => null ];
		$search_help  = new field('help', [ 'text' => null ]);
		$search_field = new field('typeahead', [
			'name'           => 'query',
			'class'          => 'input-block-level',
			'placeholder'    => 'Enter File Name',
			'data-api'       => route::get('File Manager Search'),
			'data-items'     => 5,
			'data-minLength' => 20,
		]);

		$this->form
			->group($search_group, $search_help, $search_field)
			;
	}
}
