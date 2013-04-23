<?
require_once(dirname(__FILE__).'/inc.php');

$ok = true;
$name = strtolower(gets("Enter Controller name:"));

$crud = strtolower(gets("CRUD? [N/y]"));
$model = false;
$model_lower = '';
if ($crud == 'y') {
	$model = gets("What model?");
	$model_lower = strtolower($model);
}

$boilerplate = "<?
class controller_{$name} extends controller_base {
	function __construct(\$o) {
		\$this->root_path = app::get_path('". ucfirst($name) ." Home');
		parent::__construct(\$o);
   	}
";
if (isset($model{0})) {
$boilerplate .= " 
	function all(\$o) {
		\$page = take(\$o['params'], 'page', 1); 
		\$rpp = 5;
		\$this->total = {$model}::total();
		\$this->pager = r('common', 'pager', [
			'total' => \$this->total,
			'rpp'   => \$rpp,
			'page'  => \$page,
			'base'  => \"{\$this->root_path}/\",
		]);
		\$this->{$model_lower}s = {$model}::find('all', [
			'limit'  => \$rpp,
			'offset' => model::get_offset(\$page, \$rpp),
			'order'  => 'updated_at desc',
		]);
	}

	function view(\$o) {
		\$this->". strtolower($model) ." = take(\$o, '". strtolower($model) ."');	
	}

	function add(\$o) {
		\${$model_lower} = new {$model};
		\${$model_lower} = trim(take(\$_POST, 'name'));
		\${$model_lower} = take(\$_POST, 'active', 0);
		\$ok = \${$model_lower}->save();
		if (\$ok) {
			note::set('{$model_lower}:add', 1);
			app::redir(\$this->root_path);
		}

		note::set('{$model_lower}:form', json_encode([
			'{$model_lower}'   => \$_POST, 
			'errors' => \${$model_lower}->errors->to_array(),
		]));
		app::redir(\$this->root_path);
	}

	function edit(\$o) {
		\$this->{$model_lower} = {$model}::find_by_id(take(\$o['params'], 'id'));
		if (!\$this->{$model_lower}) app::redir(\$this->root_path);
		if (!\$this->is_post) return;

		\$this->{$model_lower}->name   = trim(take(\$_POST, 'name'));
		\$this->{$model_lower}->active = take(\$_POST, 'active', 0);
		\$ok = \$this->{$model_lower}->save();
		if (\$ok) {
			note::set('{$model_lower}:edit', 1);
			app::redir(\$this->root_path);
		}

		note::set('{$model_lower}:form', json_encode([
			'{$model_lower}'   => \$_POST, 
			'errors' => \$this->{$model_lower}->errors->to_array(),
		]));

		app::redir(\"{\$this->root_path}/edit/{\$this->{$model_lower}->id}\");
	}

	function delete(\$o) {
		\$id = take(\$o['params'], 'id');
		if (!\$id) app::redir(\$this->root_path);

		\${$model_lower} = {$model}::find_by_id(\$id);
		if (!\${$model_lower}) app::redir(\$this->root_path);

		\${$model_lower}->delete();
		note::set('{$model_lower}:delete', 1);
		app::redir(\$this->root_path);
	}

/*
 * FORMS
 */
	# no view
	function add_form() {
		\$this->form = new form;
		\$this->form->open(\"{\$this->root_path}/add\", 'post', [
			'class' => 'last',
			'id'    => '{$model_lower}-add',
		]);
		\$note = json_decode(note::get('{$model_lower}:form'));
		\$this->_build_form(take(\$note, '{$model_lower}'), take(\$note, 'errors'));
		\$this->form->add(
			new field('submit', [
				'text' => 'Add', 
				'icon' => 'plus',
				'data-loading-text' => h('<i class=\"icon-plus\"></i> Adding&hellip;'),
			])
		);
		echo \$this->form;
	}

	# no view
	function edit_form(\$o) {
		\${$model_lower} = {$model}::find_by_id(take(\$o['{$model_lower}'], 'id'));
		if (!\${$model_lower}) app::redir(\$this->root_path);

		\$this->form = new form;
		\$this->form->open(\"{\$this->root_path}/edit/{\${$model_lower}->id}\", 'post', [
			'class' => 'last', 
		]);
		\$note = json_decode(note::get('{$model_lower}:form'));
		\$this->_build_form(take(\$note, '{$model_lower}', \${$model_lower}), take(\$note, 'errors'));
		\$this->form->add(
			new field('submit', [
				'text' => 'Update', 
				'icon' => 'edit',
				'data-loading-text' => h('<i class=\"icon-edit\"></i> Updating&hellip;')
			])
		);
		echo \$this->form;
	}

	private function _build_form(\${$model_lower}=null, \$errors=null) {
		app::asset('validate.min', 'js');
		# app::asset('view/{$model_lower}.form', 'js');

		\$this->form->group([ 
				'label' => 'Name', 
				'class' => model::error_class('name'),
			], 
			new field('input', [ 
				'name'        => 'name', 
				'class'       => 'input-block-level required',
				'value'       => h(take(\${$model_lower}, 'name')),
				'placeholder' => h('e.g. \"Update Records\"'),
			]),
			new field('help', [ 
				'text' => model::take_error(\$errors, 'name'),
			])
		)
		->group(
			new field('checkbox', [ 
				'name'    => 'active',
				'checked' => take(\${$model_lower}, 'active'),
				'label'   => 'Activate',
				'inline'  => true,
			])
		);
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
