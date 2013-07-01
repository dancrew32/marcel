<?
require_once(dirname(__FILE__).'/inc.php');

# SCRAPE ETRADE
yellow("eTrade Scraper\n");

$etrade = [
	'url'      => 'https://us.etrade.com',
	'username' => gets("Username:"),
	'password' => prompt_silent("Password:"),
];

$b = new browser('firefox');

try {
	$login = $b->open("{$etrade['url']}/e/t/user/login")
		->wait_for('#User');
} catch (Exception $e) {
	return fail();
}

# FILL IN LOGIN FORM
$b->find('#User')[0]->sendKeys($etrade['username']);
$b->find('#txtPassword')[0]->sendKeys($etrade['password']);
$b->find('form .log-on-btn')[0]->click();

sleep(1);

$b->wait_for('#etContent');

$file = IMAGE_DIR.'/etrade.png';

$etContent = $b->find('#etContent')[0];
$account_overview = $etContent->elements('css selector', 'table')[1]
	->elements('css selector', 'tbody tr')[0]
	->elements('css selector', 'td')[0]
	->elements('css selector', 'table')[1]
	->elements('css selector', 'tbody tr')[0]
	->elements('css selector', 'td')[1]
	->elements('css selector', 'table td')[0];
	;
$need_help_img = $account_overview->elements('css selector', 'img')[1];

$js = '(function($) {
	$(\'img[usemap="#NeedHelp"]\').hide();
}(jQuery));';
$b->session->execute(['script' => $js, 'args' => []]);
$b->session->setScriptTimeout(15);


$net_assets = $account_overview->elements('css selector', 'table')[0]
	->elements('css selector', 'table')[0]
	->elements('css selector', 'table table td')[1]
	->elements('css selector', 'b')[0]->text();

ok("Net Assets: {$net_assets}");

$b->screenshot_element($account_overview, $file);

//$b->screenshot($file);

ok('http:'.BASE_URL.'/img/etrade.png');

$b->close();
