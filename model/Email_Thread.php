<?
class Email_Thread extends model {
	static $table_name = 'email_threads';

	static function latest(array $o=[]) {
		$o = array_merge([
			'hash' => null,
			'from' => null,
		], $o);

		return Email_Thread::find('first', [
			'conditions' => ['`hash` = ? and `from` != ?', $o['hash'], $o['from']],
			'order'      => '`created_at` asc',
		]);
	}

}
