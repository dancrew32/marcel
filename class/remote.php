<?
class remote {
	#http://php.net/manual/en/function.curl-setopt.php

	public $data = [];
	
	static $default_keys = [
		'follow'      => CURLOPT_FOLLOWLOCATION,
		'return'      => CURLOPT_RETURNTRANSFER,
		'user_agent'  => CURLOPT_USERAGENT,
		'dns_timeout' => CURLOPT_DNS_CACHE_TIMEOUT,
		'timeout'     => CURLOPT_CONNECTTIMEOUT, 
		'header'      => CURLOPT_HEADER, 
		'get'         => CURLOPT_HTTPGET,
		'post'        => CURLOPT_POST,
		'put'         => CURLOPT_PUT, # infile/infilesize
		'fields'      => CURLOPT_POSTFIELDS,
		'ssl'         => CURLOPT_SSL_VERIFYPEER,
		'cookie'      => CURLOPT_COOKIE,
		'cookie_file' => CURLOPT_COOKIEFILE,
		'cookie_jar'  => CURLOPT_COOKIEJAR,
	];

	static $info_keys = [
		'http_code' => CURLINFO_HTTP_CODE,
	];


	function close() {
		curl_close($this->data['curl']);
	}

	function __destruct() {
		$this->close();
	}

	function __toString() {
		return $this->get_data();
	}

	function set_data(array $data=[]) {
		$this->data = $data;
		return $this;
	}

	function get_data() {
		return take($this->data, 'data');
	}

	function get_curl() {
		return take($this->data, 'curl');
	}

	function ok() {
		return curl_getinfo($this->get_curl(), self::$info_keys['http_code']) == 200;
	}

	function info() {
		return curl_getinfo($this->get_curl());
	}

	function get_cookies() {
		preg_match_all('#^Set-Cookie:\s*(?P<cookie>[^;]*)#mi', $this->get_data(), $matches);
		$cookies = take($matches, 'cookie');
		$out = [];
		foreach ($cookies as $c) {
			$parts = explode('=', $c);
			$out[array_shift($parts)] = implode('=', $parts);
		}
		return $out;
	}

	static function init($url) {
		return curl_init($url);
	}

	static function set_options($c, array $options=[]) {
		$out = [];
		foreach ($options as $k => $v)
			$out[self::$default_keys[$k]] = $v;
		curl_setopt_array($c, $out);
	}

	static function run($url, array $options=[]) {
		$options = array_merge([
			'follow' => true,
			'return' => true,
		], $options);
		$r = new self();
		$c = self::init($url);
		self::set_options($c, $options);
		return $r->set_data([
			'curl' => $c,
			'data' => curl_exec($c),
		]);
	}

	static function get($url, array $params=[], array $options=[]) {
		$options = array_merge([
			'get' => true,
		], $options);
		$url .= count($params) ? '?'.http_build_query($params) : '';
		return self::run($url, $options);
	}

	static function post($url, array $params=[], array $options=[]) {
		$options = array_merge([
			'post'   => true,
			'fields' => $params,
		], $options);
		return self::run($url, $options);
	}

	static function multi_init() {
		require_once VENDOR_DIR .'/multicurl/EpiCurl.php';
		return EpiCurl::getInstance();
	}

	/*
		
    // TODO
	$mc = remote::multi_init();
	$a = self::init($url);
	remote::set_options($a, [ 'get' => true ]);
	$mc_a = $mc->addCurl($a);

	$b = self::init($url);
	remote::set_options($b, [ 'get' => true ]);
	$mc_b = $mc->addCurl($b);

	echo $a.$b;

	*/

}
