<?
require_once(dirname(__FILE__).'/inc.php');

/*
 * LOGIN TO OUR SITE
 * VISIT WORKER PAGE
 * SCRAPE CONTENT
 */

# Cookie
$cook = tempnam(TMP_DIR.'/cookie', uniqid('cookie_'));
$browser = 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_8_2) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/27.0.1453.116 Safari/537.36';

# POST TO /login
$a = remote::post('http:'.BASE_URL.'/login', [
	'user'       => 'test_user',
	'pass'       => 'password',
], [
	//'header'     => true, # if pp($a->get_cookies());
	'cookie_jar' => $cook,
	'user_agent' => $browser,
]);

# OK? 
if (!$a->ok()) {
	unset($a);
	unlink($cook);
	return fail('Could not login');
}
unset($a);

# GET TO /workers
$a = remote::get('http:'.BASE_URL.'/workers', [], [
	'cookie_file' => $cook,
	'user_agent'  => $browser,
]);

# OK?
if (!$a->ok()) {
	unset($a);
	unlink($cook);
	return fail();
}

# GET DOM
$d = dom::set_html($a->get_data());
unset($a);

# FIND TEXT
try {
	$text = $d->find('p.lead', 0)->innertext;
} catch (Exception $e) {
	unlink($cook);
	return fail();
}
unset($d);
unlink($cook);

# OUTPUT TEXT
pd(util::strclean($text));
