<?
require_once(dirname(__FILE__).'/inc.php');

$p = new program;
$p->option([
	'short' => 'n',
	'long'  => 'name',
	'value' => true,
	'help'  => 'Controller name',
]);
$p->option([
	'short' => 'c',
	'long'  => 'crud',
	'help'  => 'Is the controller a CRUD controller?',
]);
$p->option([
	'short' => 'm',
	'long'  => 'model',
	'value' => true,
	'help'  => 'What model is this CRUD model associated with?',
]);
$p->option([
	'short' => 'f',
	'long'  => 'feature',
	'help'  => 'Will this controller need a Feature?',
]);
if ($p->get('h')) die($p->help());


$ok = true;
$name = preg_replace('/[^a-zA-Z0-9_]*/', '', $p->get('n') ? $p->get('n') : strtolower(gets("Enter Controller name:")));
if (!isset($name{0})) return red("Must have a name!\n");

$crud = $p->get('c') ? $p->get('c') : strtolower(gets("CRUD? [N/y]"));
$model = false;
$model_lower = '';
if ($crud == 'y') {
	$model = $p->get('m') ? $p->get('m') : gets("What model? e.g. Cron_Job");
	$model_lower = strtolower($model);
}

$english_name = ucfirst(preg_replace("/[^a-zA-Z]+/", "", preg_replace("/_/", ' ', $name)));

$boilerplate = "<?
class controller_{$name} extends controller_base {
	function __construct(\$o) {
		\$this->root_path = route::get('{$english_name} Home');
		# auth::only(['{$name}']);
		parent::__construct(\$o);
   	}
";
if (isset($model{0})) {
$boilerplate .= " 
	function all(\$o) {
		\$this->page   = take(\$o, 'page', 1); 
		\$format = take(\$o, 'format');
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
		\$this->total = {$model}::count();
		\$this->pager = r('common', 'pager', [
			'total'  => \$this->total,
			'rpp'    => \$rpp,
			'page'   => \$this->page,
			'base'   => \"{\$this->root_path}/\",
			'suffix' => h(\$format),
		]);
		\$this->{$model_lower}s = {$model}::find('all', [
			//'select' => 'id',
			'limit'  => \$rpp,
			'offset' => model::get_offset(\$this->page, \$rpp),
			'order'  => 'id asc',
		]);

		if (\$format == '.json') 
			json(model::collection_to_json(\$this->{$model_lower}s));
	}	

	function view(\$o) {
		\$this->{$model_lower} = take(\$o, '{$model_lower}');	
		\$this->mode = take(\$o, 'mode', false);
	}	

	function table(\$o) {
		\$this->{$model_lower}s = take(\$o, '{$model_lower}s');	
	}

	function add() {
		\${$model_lower} = {$model}::create(\$_POST);
		if (\${$model_lower}) {
			note::set('{$model_lower}:add', \${$model_lower}->id);
			\$this->redir();
		}

		\${$model_lower}->to_note();
		\$this->redir();
	}	

	function edit(\$o) {
		\$this->{$model_lower} = {$model}::find_by_id(take(\$o, 'id'));
		if (!\$this->{$model_lower}) \$this->redir();
		if (!POST) return;

		# handle booleans
		# \$_POST['active'] = take_post('active', 0);

		\$ok = \$this->{$model_lower}->update_attributes(\$_POST);
		if (\$ok) {
			note::set('{$model_lower}:edit', \$this->{$model_lower}->id);
			\$this->redir();
		}

		\$this->{$model_lower}->to_note();
		app::redir(route::get('{$english_name} Edit', ['id' => \$this->{$model_lower}->id]));
	}	

	function delete(\$o) {
		\$id = take(\$o, 'id');
		if (!\$id) \$this->redir();

		\${$model_lower} = {$model}::find_by_id(\$id);
		if (!\${$model_lower}) \$this->redir();

		\${$model_lower}->delete();
		note::set('{$model_lower}:delete', \${$model_lower}->id);
		\$this->redir();
	}	

/*
 * FORMS
 */
	# no view
	function add_form(\$o) {
		\${$model_lower} = new {$model};
		\${$model_lower} = \${$model_lower}->from_note();

		\$this->form = new form;
		\$this->form->open(route::get('{$english_name} Add'), 'post', [
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
		if (!\${$model_lower}) \$this->redir();

		\$this->form = new form;
		\$this->form->open(route::get('{$english_name} Edit', ['id' => \${$model_lower}->id])\", 'post', [
			'class' => 'last',
		]);
		\$this->_build_form(\${$model_lower});
		\$this->form->add(new field('submit_update'));

		echo \$this->form;
	}

	private function _build_form(\$o) {

		// \$this->form
		//   ->group();
	}
";
}
$boilerplate .= "}";

$script_name = "{$name}.php";
$full_script_path = config::$setting['controller_dir']."/{$script_name}";

$exists = is_file($full_script_path);
if ($exists)
	return red("Controller exists.\n");

$ok = file_put_contents($full_script_path, $boilerplate);
if ($ok)
	green("Successfully created controller: {$script_name}\n");
else
	red("WRITE FAIL\n");


$feature = $p->get('f') ? 'y' : strtolower(gets("Add Feature? [N/y]"));
if ($feature != 'y') return green("No feature will be added. Now Exiting.\n");

$feature = Feature::create([
	'name' => gets("Feature Name: e.g. ".ucwords($name)),
	'slug' => gets("Feature slug: e.g. {$name}"),
]);

if ($feature)
	green("Created Feature id: {$feature->id}\n");
else 
	red("FAILED to create feature.\n");

