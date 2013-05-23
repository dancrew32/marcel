<?
class controller_user_permission extends controller_base {
	function __construct($o) {
		$this->root_path = app::get_path('User Permission Home');
		auth::only(['user_permission']);
		parent::__construct($o);
   	}
 
	function all($o) {
		$this->features = Feature::all();
		$this->user_types = User_Type::all();
		$this->action = "{$this->root_path}/update";
	}	

	function update() {
		User_Permission::delete_all();

		foreach ($_POST as $key => $val) {
			$parts = explode('|', $key);
			$up = new User_Permission;
			$up->feature_id = $parts[0];
			$up->user_type_id = $parts[1];
			$up->save();
		}

		app::redir($this->root_path);
	}

}
