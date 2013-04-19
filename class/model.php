<?
class model extends ActiveRecord\Model {
	static function total() {
		$query = Cron_Job::find_by_sql('select count(id) as total from '. static::$table_name);
		return $query[0]->total;
	}	
}
