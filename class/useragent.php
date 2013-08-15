<?
class useragent {

	# cURL
	const CURL = 'curl/7.9.8 (i686-pc-linux-gnu) libcurl 7.9.8 (OpenSSL 0.9.6b) (ipv6 enabled)';

	# Java
	const JAVA = 'Java/1.6.0_26';

	# Python
	const PYTHON = 'Python-urllib/3.1';

	# PHP
	const PHP = 'PHP/5.4.17';

	# Google
	const GOOGLE_CHROME = 'Mozilla/5.0 (Windows NT 6.2; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/29.0.1547.2 Safari/537.36';
	const GOOGLE_BOT    = 'Mozilla/5.0 (compatible; Googlebot/2.1; +http://www.google.com/bot.html)';

	# Russian bot
	const YANDEX = 'Mozilla/5.0 (compatible; YandexBot/3.0; +http://yandex.com/bots)';

	# Internet Explorer
	const IE_10     = 'Mozilla/5.0 (compatible; MSIE 10.6; Windows NT 6.1; Trident/5.0; InfoPath.2; SLCC1; .NET CLR 3.0.4506.2152; .NET CLR 3.5.30729; .NET CLR 2.0.50727) 3gpp-gba UNTRUSTED/1.0';
	const IE_9      = 'Mozilla/5.0 (Windows; U; MSIE 9.0; WIndows NT 9.0; en-US))';
	const IE_8      = 'Mozilla/5.0 (compatible; MSIE 8.0; Windows NT 6.1; Trident/4.0; GTB7.4; InfoPath.2; SV1; .NET CLR 3.3.69573; WOW64; en-US)';
	const IE_7      = 'Mozilla/4.0(compatible; MSIE 7.0b; Windows NT 6.0)';
	const IE_6      = 'Mozilla/4.0 (compatible; MSIE 6.1; Windows XP; .NET CLR 1.1.4322; .NET CLR 2.0.50727)';
	const IE_5      = 'Mozilla/4.0 (compatible; MSIE 5.5b1; Mac_PowerPC)';
	const IE_4      = 'Mozilla/4.0 (compatible; MSIE 4.5; Windows NT 5.1; .NET CLR 2.0.40607)';
	const IE_MOBILE = 'Mozilla/5.0 (compatible; MSIE 9.0; Windows Phone OS 7.5; Trident/5.0; IEMobile/9.0)';

	# Opera
	const OPERA = 'Opera/9.80 (Windows NT 6.0) Presto/2.12.388 Version/12.14';

	# Blackberry
	const BB_9900 = 'Mozilla/5.0 (BlackBerry; U; BlackBerry 9900; en) AppleWebKit/534.11+ (KHTML, like Gecko) Version/7.1.0.346 Mobile Safari/534.11+';

	# Android
	const ANDROID = 'Mozilla/5.0 (Linux; U; Android 4.0.3; ko-kr; LG-L160L Build/IML74K) AppleWebkit/534.30 (KHTML, like Gecko) Version/4.0 Mobile Safari/534.30';	

	# Playstation
	const PLAYSTATION = 'Mozilla/5.0 (PLAYSTATION 3; 3.55)';
	const PSP         = 'PSP (PlayStation Portable); 2.00';

	# Nintendo Wii
	const WII = 'wii libnup/1.0';

	# Validators
	const W3C_VALIDATOR = 'W3C_Validator/1.654';

	# iTunes
	const ITUNES = 'iTunes/9.1.1';

	# UserAgentString.com API Endpoint
	const UA_INFO_API = 'http://http://www.useragentstring.com';

	static function get_info($user_agent) {
		$r = remote::get(self::UA_INFO_API, [
			'uas'     => $user_agent,
			'getJSON' => 'all',
		]);
		if (!$r->ok())
			return false;
		return json_decode($r->get_info());
	}

	static function get_current_info() {
		return self::get_info(USER_AGENT);
	}
