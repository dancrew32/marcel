# Marcel
**The MVC with Shoes On**

![Marcel](http://i.danmasq.com/marcel.jpg)

## Contents
* [Requirements](#requirements)
* [Install](#install)
* [VirtualHost Setup](#virtualhost-setup)
* [Generator Scripts](#generator-scripts)
* [Routing](#routing)
* [Models](#models-m)
* [Controllers](#controllers-c)
* [Views](#views-v)
* [Assets](#assets)
* [SCSS & Compass](#scss-compass)
* [Cookies & Notes](#cookies-and-notes)
* [Cache](#cache)
* [Form Fields](#form-fields)
* [Image Manipulation](#on-the-fly-image-manipulation-and-caching)
* [Helpers & Utils](#helpers-utils-and-more)
* [Interactive Prompt](#interactive-prompt-with-phpsh)
* [Profiling](#profiling-with-xhprof)
* [XDebug](#xdebug)

## Requirements
* PHP 5.4
* MySQL 5.5
* Apache 2.2
* Ruby Gems

### I only have PHP 5.3
C'mon! Upgrading is easy:
```bash
sudo apt-get install python-software-properties
sudo add-apt-repository ppa:ondrej/php5
sudo apt-get update
sudo apt-get upgrade
# To update APC (if you use that instead of x-cache)
# sudo pecl install apc
```

## Install
Clone and run the db init wizard:

```bash
git clone git@github.com:dancrew32/marcel.git site
cd site
php script/db_init.php
chmod 777 -R tmp
```

## VirtualHost Setup
```htaccess
<VirtualHost *:80>
	ServerName site.com
	DocumentRoot /var/www/site/public
</VirtualHost>

# SSL Version
<VirtualHost *:443>
	ServerName site.com
	DocumentRoot /var/www/site/public
	SSLEngine on
	SSLCertificateFile /path/to/your.crt
	SSLCertificateKeyFile /path/to/your.key
</VirtualHost>
```

## Generator Scripts
Every script is an easy to use interactive wizard:
```bash
# Controller
php script/gen_controller.php

# Model
php script/gen_model.php

# View
php script/gen_view.php

# Script
php script/gen_script.php  

# DB Dump
php script/db_dump.php

# DB Restore
php script/db_restore.php

# Create Users (e.g. Create your first admin user)
php script/create_user.php
```

## Routing
In `routes.php`, we send url `$_SERVER['REQUEST_URI']` matches to a specified method in a controller.
By default, routing is simple, but you may increase the complexity if you would like
HTTP method granularity and/or auth class permissions handled at the router 
(instead of the controller).
You may capture parameters using regular expressions.
```php
<?
app::$routes = [

	# Site base url leads to controller_yours::foo
	'/' => ['c' => 'yours', 'm' => 'foo'],

	# Capture page id, name capture "id" with (?P<capturename>regexp) syntax
	'/page/(?P<id>[0-9]+)' => ['c' => 'yours', 'm' => 'test'],

	# Optional HTTP Method routing
	'/http' => [
		'http' => [
			'get'    => [ 'c' => 'http_test', 'm' => 'get' ],
			'post'   => [ 'c' => 'http_test', 'm' => 'post' ],
			'put'    => [ 'c' => 'http_test', 'm' => 'put' ],
			'delete' => [ 'c' => 'http_test', 'm' => 'delete' ],
		],
	],
	
	# On-the-fly Image Processing
	'/i' => [ 'c' => 'image', 'm' => 'process' ],

	# Login/Logout
	'/login'  => [ 'c' => 'common', 'm' => 'login' ],
	'/logout' => [ 'c' => 'common', 'm' => 'logout' ],
	
	# Optional Auth
	'/auth-test-simple' => [ 
		'c' => 'common', 'm' => 'auth_test',
		'auth' => ['user'], # users only
	],
	'/auth-test-complex' => [ 
		'http' => [
			'get' => [
				'c' => 'common', 'm' => 'auth_test',
				'auth' => ['anon'], # users and anons may GET
			],
			'post' => [
				'c' => 'common', 'm' => 'auth_test',
				'auth' => ['user'], # only users may POST
			],
		],
		'auth' => ['manager'], # managers may GET and POST
	],

];
```

## Models (M)
Uses [PHPActiveRecord](http://www.phpactiverecord.org)
See [Basic CRUD](http://www.phpactiverecord.org/projects/main/wiki/Basic_CRUD) to get started
```php
<?
class Thing extends ActiveRecord\Model {
	static $table_name = 'things';
}

# Create
$t = new Thing;
$t->stuff = "raisin";
$t->save();

# Read
$b = Thing::find(1);
echo $b->stuff; # "raisin"

# Update
$b->stuff = "dorito";
$b->save();

# Destroy
$b->delete();
```

## Controllers (C)
Simple business logic only please. This would be `controller/yours.php`
`foo()` would pass variables to `view/yours.foo.php`
`bar()` would pass variables to `view/yours.bar.php`
`$o` contains parameters passed in the 3rd argument of `r()`
```php
<?
class controller_yours extends controller_base {
	function foo($o) {
		$not_in_view = "I'm not available to the view";	
		$this->in_view = "I'm available to the view";
	}

	function bar($o) {
		$this->other_stuff = "Stuff";	
	}

	function test($o) {
		# Obtain captured page "id" from Routes example
		$page_id = take($o['params'], 'id', 1);	
	}
}
```

## Views (V)
Everything you declare in your controller with `$this->...` is available in your view.
```php
# views/yours.foo.php
<div>
	<?= $in_view ?>
</div>
```

You can subrender `bar` in `foo` using `r(controller, view)`
```php
# views/yours.foo.php
<div>
	<?= $in_view ?>
	<?= r('yours', 'bar') ?>
</div>
```

## Assets
Assets are loaded per view and in order (duplicates ignored).
Here's an example view (`view/foo.bar.php`) with its own JavaScript in `public/js/foo.bar.js` and CSS in `public/css/foo.bar.css`
```php
<? app::asset('foo.bar', 'css') ?>
<? app::asset('foo.bar', 'js') ?>
<div>...</div>
```

## SCSS (Compass)
Write some [SCSS](http://sass-lang.com/) with [Compass](http://compass-style.org).
Changes in `scss` directory automatically reflect in `public/css` when you run:
```bash
sudo gem install compass
sudo gem install compass_twitter_bootstrap
compass watch &
```

## Layouts
`views/layouts/a.php` is the default layout. 
If you want to use an alternative, request the layout name in `routes.php`:
```php
<?
app::$routes = [

	# Uses views/layouts/mylayout.php
	'/section' => ['c' => 'foo', 'm' => 'bar', 'l' => 'mylayout' ],

];
```
Layout rendering is automatically skipped by AJAX requests.

## Auth
In `class/auth.php`, you may optionally define user permissions for 
use in controllers or routes.
One way to test may be to use `model.User`'s `role` attribute 
(like the example auth class) to gate controller method access.
A scalable paradigm would be to write feature-named methods
that contain user role testing.
```php
<?
class auth {

/*
 * USERS
 */
	static function admin() {
		return take(User::$user, 'role') == 'admin';
	}	

	static function manager() {
		$role = take(User::$user, 'role');
		return in_array($role, ['manager', 'admin']);
	}

	static function user() {
		$role = take(User::$user, 'role');
		return in_array($role, ['user', 'manager', 'admin']);
	}

	static function anon() {
		return !User::$logged_in;
	}	


/*
 * FEATURES
 */

	// Users that may send email
	static function email_send() {
		// only admins and managers may send email
		return self::manager();
	}
}
```
Now you can test in the controller for `auth::email_send()`
or even better, test in `routes.php` `auth => ['email_send']`
to keep non-managers/non-admins from sending email.

## Cookies (and Notes)
Standard API for cookie CRUD and `note` is available for one-time use.
```php
# Cookies
cookie::set('shoes', 'on', time::ONE_YEAR);
echo cookie::get('shoes'); # "on"

# Notes
note::set('success_message', 'Message Sent');
echo note::get('success_message'); # "Message Sent"
echo note::get('success_message'); # ""
```

## Cache
Designed to use Memcached on `11211`. Here's a typical get-if-set pattern:
```php
$data = cache::get('cachekey', $found);
if (!$found) {
	$data = getData(); # arbitrary method
	cache::set('cachekey', $data, time::ONE_DAY);
}
echo $data;
```

## Form Fields
Building forms from scratch is tedious. Let's use a twitter bootstrap customized
form builder! Here's an example:
```php
<?
$form = new form;
$form->open('/login', 'post')
->add('Username', new field('text', ['name' => 'username', 'placeholder' => 'Username']))
->add('Password', new field('password', ['name' => 'password', 'placeholder' => 'Password']))
->action(
	new field('submit', ['text' => 'Login'])
);

echo $form;
```
If you want to see everything `class/form.php` and `class/field.php` are capable of,
check out `controller/form_test.php#index`.

## On-The-Fly Image Manipulation (and Caching)
Using a modified version of TimThumb, we can maniuplate our images on the fly!
```php
app::$routes = [
	'/i' => ['c' => 'image', 'm' => 'process' ],
];
```
Now any view: 
```php
<?= image::get([
	'src' => '/img/drwho.jpg',
	'w'   => 100,
	'h'   => 100,
], true) ?>
```
Will generate: `http://site.com/i?src=%2Fimg%2Fdrwho.jpg&q=85`.
This new image will be cached and served from now on!

## Helpers, Utils and more
In `class/helper.php`, `class/util.php`, `class/size.php`, and `class/time.php`
you'll find many convenient methods to use from everything to obtaining
time in seconds, human readable byte sizes, string manipulation, debuggers and 
other shortcuts to use in this system.

## Workers
[Gearman](http://gearman.org/getting_started)
```bash
# install gearman
bzr branch lp:gearmand
cd gearmand
./config/autorun.sh
./configure
make
make install
sudo pecl install gearman
# add extension="gearman.so" to /etc/php5/apache2/php.ini
```

## WebSocket Server
Running `php -q script/socket_server.php` will start up a websocket server
that has access to all of the framework methods. 
TODO: working on getting User sessions data in the `socket_user` constructor.

## Interactive Prompt with PHPSH
Using [PHPSH](https://github.com/facebook/phpsh), 
you may interactively run the framework.
Install PHPSH:
```bash
cd ~
git@github.com:facebook/phpsh.git
cd phpsh
python setup.py build
sudo python setup.py install
```

Then from the site root directory (`ROOT_DIR`) run:

```bash
phpsh script/inc.php
```

## Profiling with XHProf
**TODO**: create the interface for running tests
```bash
wget https://github.com/facebook/xhprof/archive/master.zip
unzip master.zip
cd xhprof-master/extension/
phpize
./configure
make
sudo make install
# update xhprof.ini
# extension=xhprof.so
# xhprof.output_dir="/home/<you>/www/xhprof"</you>
```

## XDebug
```bash
wget http://xdebug.org/files/xdebug-2.2.1.tgz
tar -xvzf xdebug-2.2.1.tgz
cd xdebug-2.2.1
phpize
./configure
make
sudo cp modules/xdebug.so /usr/lib/php5/20100525
# zend_extension = /usr/lib/php5/20100525/xdebug.so
```
