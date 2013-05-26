<?
class User_Verification extends model {
	static $table_name = 'user_verifications';

	const INVALID_AFTER = time::ONE_DAY;
	const BCRYPT_COST = 5;

/*
 * RELATIONSHIP
 */
	static $belongs_to = [
		[ 'user' ],
	];

/*
 * VALIDATION
 */
	static $validates_presence_of = [
		['user_id'],
		['hash'],
	];

/*
 * INSTANCE
 */
	function url() {
		$uv_path = BASE_URL . app::get_path('User Verification');
		return "{$uv_path}/{$this->hash}/{$this->user_id}";
	}

/*
 * STATIC
 */
	static function generate_hash($user) {
		require_once VENDOR_DIR.'/password_compat/lib/password.php';
		$hash = password_hash($user->email, PASSWORD_BCRYPT, [
			'cost' => self::BCRYPT_COST,
			'salt' => md5(SALT.time()),
		]);
		return str_replace('/', '|', $hash);
	}

	static function verify(array $o) {
		$hash = take($o, 'hash');
		$user_id = take($o, 'user_id');
		$user = User::find_by_id($user_id);
		if (!$user) 
			return false;
		if ($user->verified)
			return true;

		$uv = self::find('first', [
			'conditions' => [
				'user_id = ? and hash = ?', $user_id, $hash
			]
		]);
		if (!$uv) 
			return false;
		if ($uv->hash != $hash) 
			return false;
		if ($uv->created_at->format('U') + self::INVALID_AFTER < time()) 
			return false;

		$user->verified = 1;
		if (!$user->save())
			return false;

		self::cleanup_user(['user_id' => $user->id]);
		return true;
	}

	static function queue_delete_by_user_id($user_id) {
		return Worker::add([
			'class'  => __CLASS__,
			'method' => 'cleanup_user',
			'args'   => [
				'user_id' => $user_id,
			],
		]);
	}

	static function cleanup_user(array $args) {
		return self::delete_all([
			'conditions' => [ 'user_id = ?', (int) take($args, 'user_id') ],
		]);
	}

}
