<?
class profile {
	static function start($name='') {
		if (function_exists('xhprof_enable'))
			xhprof_enable();
	}
	static function stop($name='') {
		if (function_exists('xhprof_disable'))
			xhprof_disable();
	}
}
