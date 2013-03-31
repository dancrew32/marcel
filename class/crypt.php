<?
class crypt {
	static function set($str) {
		return self::mencrypt($str, SALT);	
	}
	static function get($str) {
		return self::mdecrypt($str, SALT);
	}

	static function mencrypt($input, $key){
		$key = substr(md5($key),0,24);
		$td = mcrypt_module_open ('tripledes', '', 'ecb', '');
		$iv = mcrypt_create_iv(mcrypt_enc_get_iv_size($td), MCRYPT_RAND);
		mcrypt_generic_init($td, $key, $iv);
		$encrypted_data = mcrypt_generic($td, $input);
		mcrypt_generic_deinit($td);
		mcrypt_module_close($td);
		return trim(chop(self::url_base64_encode($encrypted_data)));
	}

	static function mdecrypt($input,$key){
		$input = trim(chop(self::url_base64_decode($input)));
		$td = mcrypt_module_open('tripledes', '', 'ecb', '');
		$key = substr(md5($key),0,24);
		$iv = mcrypt_create_iv(mcrypt_enc_get_iv_size($td), MCRYPT_RAND);
		mcrypt_generic_init($td, $key, $iv);
		$decrypted_data = mdecrypt_generic($td, $input);
		mcrypt_generic_deinit($td);
		mcrypt_module_close($td);
		return trim(chop($decrypted_data));
	}

	static function url_base64_encode($str){
		return strtr(base64_encode($str),
			array(
				'+' => '.',
				'=' => '-',
				'/' => '~'
			)
		);
	}

	static function url_base64_decode($str) {
		return base64_decode(strtr($str,
			array(
				'.' => '+',
				'-' => '=',
				'~' => '/'
			)
		));
	}
}
