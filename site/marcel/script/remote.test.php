<?
require_once(dirname(__FILE__).'/inc.php');

$config = setting::$config;

/*
 * LOGIN TO OUR SITE
 * VISIT WORKER PAGE
 * SCRAPE CONTENT
 */

# Cookie
$cook = tempnam(config::$setting['tmp_dir'].'/cookie', uniqid('cookie_'));
$browser = useragent::GOOGLE_CHROME;

# POST TO /login
$a = remote::post("http://{$config['base_url']}/login", [
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
$a = remote::get("http://{$config['base_url']}/workers", [], [
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
