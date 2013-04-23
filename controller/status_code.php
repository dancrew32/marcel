<?
class controller_status_code extends controller_base {

	# http://www.w3.org/Protocols/rfc2616/rfc2616-sec10.html

	# 403
	function forbidden() {
		$code   = 403;
		$status = 'Forbidden';
		header("HTTP/1.1 {$code} {$status}");
		json([
			'status'   =>	$status,
			'code'     => $code,
			'redirect' => '/'
		]);
	}

	# 404
	function not_found() {
		$code   = 404;
		$status = 'Not Found';
		header("HTTP/1.1 {$code} {$status}");
		json([
			'status'   =>	$status,
			'code'     => $code,
			'redirect' => '/'
		]);
	}	

	# 500
	function fatal_error() {
		$code   = 500;
		$status = 'Internal Server Error';
		header("HTTP/1.1 {$code} {$status}");
		json([
			'status' =>	$status,
			'code'   => $code,
		]);
	}	
}
