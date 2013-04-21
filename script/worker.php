<?
require_once(dirname(__FILE__).'/inc.php');

$threads = isset($argv[1]{0}) ? $argv[1] : 10;
$thread_store = [];
for ($i = 0; $i < $threads; $i++) {
	$pid = pcntl_fork();
	$thread_store[] = $pid;			
	if ($pid === 0) {
		while(true) {
			$ws = Worker::find('all', [
				'conditions' => ['active = 0'],
				'limit' => 1,
			]);
			foreach ($ws as $w) {
				$w->run($pid);	
			}
			sleep(1);
		}
	} else
		$thread_store[] = $pid;
}

foreach ($thread_store as $pid)
	pcntl_waitpid($pid, $status);
