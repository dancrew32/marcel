<?
require_once(dirname(__FILE__).'/inc.php');

$config = config::$setting;


$clients = [
	# TRANSMISSION SETTINGS
	'transmission' => [
		'host' => "http://{$config['base_url']}",
		'port' => 3333,
		'path' => '/_transmission/rpc',
	],
	# RTORRENT SETTINGS
	'rtorrent' => [
		'host' => "http://{$config['base_url']}",
		'port' => 5000,
		'path' => '',
	],
];

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


# CURRENT MODE
$selected_feed = 'ubuntu';
$selected_mode = 'rtorrent';


# ESTABLISH RPC CONNECTION
$t = new torrent([
	'mode'             => $selected_mode,
	'rpc_url'          => "{$clients[$selected_mode]['host']}:{$clients[$selected_mode]['port']}{$clients[$selected_mode]['path']}",
	'formats_allowed'  => $feeds[$selected_feed]['formats'],
	'total_per_search' => 1,
	'rss'              => $feeds[$selected_feed]['rss'],
	'username'         => gets("Enter {$selected_mode} username"),
	'password'         => prompt_silent("Enter {$selected_mode} password"), 
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
