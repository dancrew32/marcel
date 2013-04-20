<? require_once(dirname(__FILE__).'/inc.php');

$jobs = Cron_Job::all();
foreach ($jobs as $j) {
	if (!$j->should_run()) continue;
	exec("nohup /usr/bin/php -q {$j->script} > /dev/null 2>&1 &");
}
