<?
require_once(dirname(__FILE__).'/inc.php');



# TRANMISSION SETTINGS
$transmission = [
	'host' => 'http://'. BASE_URL,	
	'port' => 3333,
	'path' => '/_transmission/rpc',
];



# CURRENT MODE
$selected_feed = 'ubuntu';



# LIBRARY OF TORRENT FEEDS
$feeds = [
	'dexter' => [
		'rss'     => 'http://www.ezrss.it/search/index.php?simple&show_name=Dexter&mode=rss',
		'search'  => torrent::tv_show_range(8, 12), # [s01e01, s01e02, ... s08e07]
		'formats' => [],
	],
	'ubuntu' => [
		'rss'     => 'http://rss.thepiratebay.sx/303', # UNIX channel
		'search'  => ['12.04'], # UNIX channel
		'formats' => [],
	],
];



# ESTABLISH RPC CONNECTION
$t = new torrent([
	'rpc_url'          => "{$transmission['host']}:{$transmission['port']}{$tranmission['path']}",
	'formats_allowed'  => $feeds[$selected_feed]['formats'],
	'total_per_search' => 1,
	'rss'              => $feeds[$selected_feed]['rss'],
	'username'         => gets('Enter Transmission username'),
	'password'         => prompt_silent('Enter Transmission password'), 
]);



# START DOWNLOADING
$t->find_in_rss($feeds[$selected_feed]['search'])->init();


# DISPLAY FOUND & STARTED
foreach ($t->get() as $tor)
	echo "{$tor->id}. {$tor->name}\n";


//pr($t->stats());
//pr($t->session());


# STOP ALL DOWNLOADS
$t->stop(); // stop all
