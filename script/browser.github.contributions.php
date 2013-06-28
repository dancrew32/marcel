<?
require_once(dirname(__FILE__).'/inc.php');

# SCRAPE GITHUB CONTRIBUTION GRAPH
yellow("Github Contribution Graph Scraper\n");

$github = [
	'url'      => 'https://www.github.com',
	'username' => gets("Username:"),
	'password' => prompt_silent("Password:"),
];

$b = new browser('chrome');

try {
	$login = $b->open("{$github['url']}/login")
		->wait_for('#login')
		->find('#login')[0];
} catch (Exception $e) {
	return fail();
}

# FILL IN LOGIN FORM
$username = $b->find('#login_field')[0];
$password = $b->find('#password')[0];
$b->type($username, $github['username'])
  ->type($password, $github['password'])
  ->find('#login input.button')[0]->click();

# TEST IF LOGGED IN, NAVIGATE TO CONTRIBUTION GRAPH
try {
	$b->wait_for('a.name')
	  ->open("{$github['url']}/{$github['username']}")
	  ->wait_for('#contributions-calendar rect.day');
} catch (Exception $e) {
	return fail();
}


sleep(4);


try {
	$js = 'var el = document.getElementsByClassName("contrib-info")[0]; el.parentNode.removeChild(el); return true;';
	$out = $b->session->execute_async(['script' => $js, 'args' => []]);
	pr($out);
} catch (Exception $e) {
	pr($e);
	unset($b);
	return fail();
}

# CAPTURE CONTRIBUTION GRAPH
$contributions = $b->find('#contributions-calendar')[0];
$image_name = "github-{$github['username']}-contributions.png";
$b->screenshot_element($contributions, IMAGE_DIR."/{$image_name}");
ok('http:'. BASE_URL ."/img/{$image_name}");
