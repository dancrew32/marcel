<?
require_once(dirname(__FILE__).'/inc.php');

$ok = true;
$name = preg_replace('/[^a-zA-Z0-9]*/', '', strtolower(gets("Enter Controller name:")));
if (!isset($name{0})) return red("Must have a name!\n");

$crud = strtolower(gets("CRUD? [N/y]"));
$model = false;
$model_lower = '';
if ($crud == 'y') {
	$model = gets("What model? e.g. Cron_Job");
	$model_lower = strtolower($model);
}

$boilerplate = "<?
class controller_{$name} extends controller_base {
	function __construct(\$o) {
		\$this->root_path = app::get_path('". ucfirst(preg_replace("/[^a-zA-Z]*/", " ", $name)) ." Home');
		parent::__construct(\$o);
   	}
";
if (isset($model{0})) {
$boilerplate .= " 
	function all(\$o) {
		\$page   = take(\$o['params'], 'page', 1); 
		\$format = take(\$o['params'], 'format');
		switch (\$format) {
			case '.table':
				\$this->output_style = 'table';
				\$rpp = 15;
				break;
			case '.json':
				\$rpp = 10;
				break;
			default:	
				\$this->output_style = 'media';
				\$rpp = 5;
		}
		\$this->total = {$model}::total();
		\$this->pager = r('common', 'pager', [
			'total'  => \$this->total,
			'rpp'    => \$rpp,
			'page'   => \$page,
			'base'   => \"{\$this->root_path}/\",
			'suffix' => h(\$format),
		]);
		\$this->{$model_lower} = {$model}::find('all', [
			//'select' => 'id',
			'limit'  => \$rpp,
			'offset' => model::get_offset(\$page, \$rpp),
			'order'  => 'id asc',
		]);

		if (\$format == '.json') 
			json(model::collection_to_json(\$this->{$model_lower}));
	}	

	function view(\$o) {
		\$this->{$model_lower} = take(\$o, '{$model_lower}');	
		\$this->mode = take(\$o, 'mode', false);
	}	

	function table(\$o) {
		\$this->{$model_lower} = take(\$o, '{$model_lower}');	
	}

	function add() {
		\${$model_lower} = new {$model};
		# \${$model_lower}->property = take(\$_POST, 'property');
		\$ok = \${$model_lower}->save();
		if (\$ok) {
			note::set('{$model_lower}:add', \${$model_lower}->id);
			app::redir(\$this->root_path);
		}

		\${$model_lower}->to_note();
		app::redir(\$this->root_path);
	}	

	function edit(\$o) {
		\$this->{$model_lower} = {$model}::find_by_id(take(\$o['params'], 'id'));
		if (!\$this->{$model_lower}) app::redir(\$this->root_path);
		if (!\$this->is_post) return;

		# \$this->{$model_lower}->property = take(\$_POST, 'property');
		\$ok = \$this->{$model_lower}->save();
		if (\$ok) {
			note::set('{$model_lower}:edit', \$this->{$model_lower}->id);
			app::redir(\$this->root_path);
		}

		\$this->{$model_lower}->to_note();
		app::redir(\"{\$this->root_path}/edit/{\$this->{$model_lower}->id}\");
	}	

	function delete(\$o) {
		\$id = take(\$o['params'], 'id');
		if (!\$id) app::redir(\$this->root_path);

		\${$model_lower} = {$model}::find_by_id(\$id);
		if (!\${$model_lower}) app::redir(\$this->root_path);

		\${$model_lower}->delete();
		note::set('{$model_lower}:delete', \${$model_lower}->id);
		app::redir(\$this->root_path);
	}	

/*
 * FORMS
 */
	# no view
	function add_form(\$o) {
		\${$model_lower} = new {$model};
		\${$model_lower} = \${$model_lower}->from_note();

		\$this->form = new form;
		\$this->form->open(\"{\$this->root_path}/add\", 'post', [
			'class' => 'last',
		]);
		\$this->_build_form(\${$model_lower});
		\$this->form->add(new field('submit_add'));

		echo \$this->form;
	}

	# no view
	function edit_form(\$o) {
		\${$model_lower} = take(\$o, '{$model_lower}');
		\${$model_lower} = \${$model_lower}->from_note();
		if (!\${$model_lower}) app::redir(\$this->root_path);

		\$this->form = new form;
		\$this->_build_form(\${$model_lower});
		\$this->form->add(new field('submit_update'));

		echo \$this->form;
	}

	private function _build_form(\${$model_lower}) {

		// \$this->form
		//   ->group();
	}
";
}
$boilerplate .= "}";

$script_name = "{$name}.php";
$full_script_path = CONTROLLER_DIR."/{$script_name}";

$exists = file_exists($full_script_path);
if ($exists)
	return red("Controller exists.\n");

$ok = file_put_contents($full_script_path, $boilerplate);
if ($ok)
	green("Successfully created controller: {$script_name}\n");
else
	red("WRITE FAIL\n");
