<?
class controller_user_verification extends controller_base {
	function __construct($o) {
		$this->root_path = route::get('User Verification');
		parent::__construct($o);
   	}

	function verify($o) {
		$ok = User_Verification::verify(take($o, 'params'));	
		if ($ok)
			note::set('user_verification:success', 1);
		else 
			note::set('user_verification:failure', 1);

		app::redir(route::get('Home'));
	}

	function resend($o) {
		if (!User::$user) app::redir(route::get('Home'));

		$home = route::get('Home');

		# Admin resend
		$uid_param = take($o['params'], 'id', false);
		$admin_mode = $uid_param && auth::can(['user']);
		if (!$admin_mode) {
			# User Resend
			$verified = take(User::$user, 'verified');
			if ($verified) app::redir($home);
			$queued = User::$user->send_verification_email();
			if ($queued)
				note::set('user_verification:sent', 1);
			app::redir($home);
		}

		$redirect = take($_GET, 'r', $home);
		$user = User::find_by_id($uid_param);
		if (!$user) app::redir($redirect);

		$queued = $user->send_verification_email();
		if ($queued)
			note::set('user_verification:sent', 1);
		app::redir($redirect);
	}
}
