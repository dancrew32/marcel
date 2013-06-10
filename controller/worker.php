<?
class controller_worker extends controller_base {
	function __construct($o) {
		$this->root_path = route::get('Worker Home');
		auth::only(['worker']);
		parent::__construct($o);
   	}

	function all($o) {
		$filter = take($o['params'], 'filter');
		switch ($filter) {
			case 'scheduled':	
				$conditions = ['run_at is not null'];
				$this->total = Worker::count([
					'conditions' => 'run_at is not null'
				]);	
				$this->filter = "Scheduled";
				break;
			case 'active':	
				$conditions = ['active = 1'];
				$this->total = Worker::count([
					'conditions' => 'active = 1'
				]);	
				$this->filter = "Active";
				break;
			default:
				$conditions = [];
				$this->total = Worker::count();	
				$this->filter = 'All';
		}
		$this->workers = Worker::find('all', [
			'conditions' => $conditions,
			'limit'      => 25,
			'order'      => 'active desc, id desc',
		]);
	}

	function nav_filter($o) {
		$this->filter = take($o, 'filter', 'All');
		$this->links = [
			'All' => [
				'href' => route::get('Worker Home'),
				'text' => "All",
				'icon' => 'eye-open',
			],
			'Active' => [
				'href' => route::get('Worker Home', ['filter' => 'active']),
				'text' => "Active",
				'icon' => 'ok-circle',
			],
			'Scheduled' => [
				'href' => route::get('Worker Home', ['filter' => 'scheduled']),
				'text' => "Scheduled",
				'icon' => 'calendar',
			],
		];
	}

	function view($o) {
		$this->worker = take($o, 'worker');
		$this->filter = take($o, 'filter');
	}

	function reset($o) {
		$id = take($o['params'], 'id');
		if (!$id) $this->redir();

		$worker = Worker::find_by_id($id);
		if (!$worker) $this->redir();
		$worker->active = 0;
		$worker->save();

		note::set('worker:reset', $worker->id);
		$this->redir();
	}

	function delete($o) {
		$id = take($o['params'], 'id');
		if (!$id) $this->redir();

		$worker = Worker::find_by_id($id);
		if (!$worker) $this->redir();
		$worker->delete();

		note::set('worker:delete', $worker->id);
		$this->redir();
	}
}
