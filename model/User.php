<?
class User extends ActiveRecord\Model {
	static $table_name = 'users';
	static $roles = [
		'admin'   => 'Admin',
		'manager' => 'Manager',
		'user'    => 'User',
	];

	static $in   = false;
	static $user = [];

	static function init() {
		self::$in = take($_SESSION, 'in', false);
		if (!self::$in) return false;
		$id = (int) take($_SESSION, 'id');
		$cache = "user:init:{$id}";
		self::$user = Cache::get($cache, $found, true);
		if (!$found) {
			self::$user = User::find($id);
			Cache::set($cache, self::$user, time::ONE_HOUR, true);
		}
	}

	static function login($u, $p) {
		$p = self::spass($p);
		$r = self::id_or_email_or_username($u);
		$match = take($r, 'password') == $p;
		if ($match && $r->active) {
			$_SESSION['in'] = 1;
			$_SESSION['id'] = take($r, 'id');
			self::$in = true;
			$r->last_login = db::dtnow();
			$r->last_login_ip = take($_SERVER, 'REMOTE_ADDR', null);
			$r->login_count++;
			$r->save();
			self::$user = $r;
		} else {
			unset($_SESSION['in']);
			unset($_SESSION['id']);
			self::$in = false;
			self::$user = [];
		}
		return $match;
	}

	static function id_or_email_or_username($key) {
		if (is_numeric($key))
			return self::find_by_id($key);
		if (strpos($key, '@'))
			return self::find_by_email($key);
		return self::find_by_username($key);
	}

	static function logout() {
		session_destroy();
		app::redir('/');
	}

	static function spass($p) {
		return md5(SALT.$p.SALT);	
	}

}
