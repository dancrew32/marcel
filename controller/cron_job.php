<?
class controller_cron_job extends controller_base {
	function all() {
		$this->crons = Cron_Job::all();
	}
}
