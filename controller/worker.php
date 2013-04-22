<?
class controller_worker extends controller_base {
	function __construct($o) {
		$this->root_path = app::get_path('Worker Home');
		parent::__construct($o);
   	}

	function all($o) {
		$filter = take($o['params'], 'filter');
		switch ($filter) {
			case 'scheduled':	
				$conditions = ['run_on is not null'];
				$this->total = Worker::total('where run_on is not null');	
				$this->filter = "Scheduled";
				break;
			case 'active':	
				$conditions = ['active = 1'];
				$this->total = Worker::total('where active = 1');	
				$this->filter = "Active";
				break;
			default:
				$conditions = [];
				$this->total = Worker::total();	
				$this->filter = 'All';
		}
		$this->workers = Worker::find('all', [
			'conditions' => $conditions,
			'limit'      => 25,
			'order'      => 'active desc, id desc',
		]);
	}

	function view($o) {
		$this->worker = take($o, 'worker');
		$this->filter = take($o, 'filter');
	}

	function reset($o) {
		$id = take($o['params'], 'id');
		if (!$id) app::redir($this->root_path);

		$worker = Worker::find_by_id($id);
		if (!$worker) app::redir($this->root_path);
		$worker->active = 0;
		$worker->save();

		note::set('worker:reset', 1);
		app::redir($this->root_path);
	}

	function delete($o) {
		$id = take($o['params'], 'id');
		if (!$id) app::redir($this->root_path);

		$worker = Worker::find_by_id($id);
		if (!$worker) app::redir($this->root_path);
		$worker->delete();

		note::set('worker:delete', 1);
		app::redir($this->root_path);
	}
}
