<?
require_once(dirname(__FILE__).'/inc.php');

$threads = isset($argv[1]{0}) ? $argv[1] : 10;
$thread_store = [];
$_ = $_SERVER['_'];

# Recover fatals
register_shutdown_function(function() {
	global $_, $argv;	
	# TODO: http://stackoverflow.com/questions/7679535/php-function-pcntl-exec-is-undefined
	# Install apache module?
	if (function_exists('pcntrl_exec'))
		pcntrl_exec($_, $argv);
});

for ($i = 0; $i < $threads; $i++) {
	$pid = pcntl_fork();
	if ($pid === 0) {
		while(true) {
			$ws = Worker::find('all', [
				'conditions' => [
					"id % {$threads} = {$i}",
					'active = 0',
				],
				'limit' => 1,
			]);
			foreach ($ws as $w)
				$w->run($pid);	
			sleep(5);
		}
	} else
		$thread_store[] = $pid;
}

foreach ($thread_store as $pid)
	pcntl_waitpid($pid, $status);
