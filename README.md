# Marcel
**The MVC with Shoes On**

![Marcel](http://i.danmasq.com/marcel.jpg)

## Contents (stable)
* [Requirements](#requirements)
* [Install](#install)
* [VirtualHost Setup](#virtualhost-setup)
* [Scripts](#scripts)
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
* [Utilities](#utilities)
* [Helpers](#helpers)
* [Cron](#cron)
* [Workers](#workers)
* [Mail](#mail)
* [Fake Data](#fake-data)
* [Scraping](#scraping)
* [Interactive Prompt](#interactive-prompt-with-phpsh)
* [Vim Interactivity](#vim-interactivity)

### Contents (unstable)
* [WebSocket Server](#websocket-server)
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
chmod 777 marcel
```
After install, you may want to create your first user: `php script/create_user.php`

## VirtualHost Setup
```htaccess
<VirtualHost *:80>
	ServerName site.com
	DocumentRoot /var/www/site/public
	SetEnv ENV "DEV" # or "PROD"
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

## Scripts
Marcel has a ton of scripts available to automate development.

Just run `./marcel` from the root directory to get a menu
of scripts to run! 

When you're comfortable with the
list of scripts you have, use the search shortcut
to immediately run that script `./marcel <search>` 
(so maybe `./marcel dbdump` to run `php script/db_dump.php`).

Every script is an easy to use interactive wizard:

### Wizards & Scripts
Wizard | Script Description
--- | ---
`php script/gen_controller.php` | [Controller](#controllers-c)
`php script/gen_model.php` | [Model](#models-m)
`php script/gen_view.php` | [View](#views-v)
`php script/gen_js_class.php` | JavaScript Module
`php script/gen_script.php` | Script/[Cron](#cron)
`php script/db_init.php` | DB initialization (see [Install](#install))
`php script/db_dump.php` | DB *dump* in `db/dump`	
`php script/db_restore.php` | DB *restore* from `db/dump`
`php script/db_create_mysql_user.php` | Create a new [MySQL](http://dev.mysql.com/doc/refman/5.1/en/adding-users.html) user with permissions to *only this* `DB_NAME`
`php script/create_user.php` | Create `User`s (e.g. Create your first `User` with `role` of `admin`)
`php script/cron.base.php` | Run each `Cron_Job` if `Cron_Job->frequency` matches `time()` and is `active`
`php script/scss_watch.php` | Run `compass watch` as daemon to watch [SCSS](#scss-compass)
`php script/worker.php` | Start a [Worker](#workers) server
`php script/vim.php` | Start an [Interactive Vim](#vim-interactivity) `eval` session

## Routing
In `routes.php`, we send url `$_SERVER['REQUEST_URI']` 
[preg_match](http://php.net/manual/en/function.preg-match.php)es 
to a specified method in a [controller](#controllers-c).

By default, routing is simple, but you may increase the complexity if you would like
[`HTTP`](http://en.wikipedia.org/wiki/Hypertext_Transfer_Protocol) method granularity 
and/or [`auth`](#auth) class permissions handled at the router 
(instead of the [controller](#controllers-c)).

You may capture parameters using regular expressions with 
[named subpatterns](http://us1.php.net/manual/en/function.preg-match.php#example-4666)
e.g. `'/(?P<word>\w+)/(?P<digit>\d+)'` would match `/blogs/2`.

### Route Keys

Key | Description
--- | ---
`c` | Controller *required*
`m` | Method *required*
`l` | Layout (`foo` would be `view/layout/foo.php`)
`auth` | Authorization method in `class/auth.php` to gate access with
`name` | Unique name for this route (see `app::get_path($name)` useful when url paths change)
`section` | Name for grouping routes together (e.g. `Portfolio`)
`http` | for nested [REST](https://en.wikipedia.org/wiki/Representational_state_transfer) routing (e.g. `get`, `post`, `put`, `delete`)
`nodb` | if `true`, skip any database connections for this execution

### Example Route Implementation

```php
<?
app::$routes = [

	# Site base url leads to controller_yours::foo
	'/' => ['c' => 'yours', 'm' => 'foo'],


	# Capture page id, name capture "id" with (?P<capturename>regexp) syntax
	'/page/(?P<id>\d+)' => ['c' => 'yours', 'm' => 'test'],


	# HTTP method-specific (Optional)
	'/http' => [
		'http' => [
			'get'    => [ 'c' => 'http_test', 'm' => 'get' ],
			'post'   => [ 'c' => 'http_test', 'm' => 'post' ],
			'put'    => [ 'c' => 'http_test', 'm' => 'put' ],
			'delete' => [ 'c' => 'http_test', 'm' => 'delete' ],
		],
	],

	
	# Skip Database (`nodb` avoids database & user session initialization)
	'/i' => [ 'c' => 'image', 'm' => 'process', 'nodb' => true, 'name' => "Image Process" ],

	
	# Auth (optional)
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


	# Named-Routes & Sections (optional)
	'/changes-frequently' =>
		[ 'c' => 'thing' => 'm' => 'index', 'name' => 'Things Home', 'section' => 'Things' ],
		# app::get_path('Things Home') returns '/changes-frequently'
	'/changes-as-well(?:/*)(?P<url_slug>\d+)' =>
		[ 'c' => 'thing' => 'm' => 'secondary', 'name' => 'Things Secondary', 'section' => 'Things' ],
		# app::get_path('Things Secondary') returns '/changes-as-well'

];
```

## Models (M)
Uses [PHPActiveRecord](http://www.phpactiverecord.org)
(see [Basic CRUD](http://www.phpactiverecord.org/projects/main/wiki/Basic_CRUD) to learn more).
Get started with a new Model using the `php script/gen_model.php` [Script](#scripts).
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

* This file would be `controller/yours.php`
* `foo()` would pass variables to `view/yours.foo.php`
* `bar()` would pass variables to `view/yours.bar.php`
* `$o` contains optional parameters passed in the `3rd` argument of `r('controller', 'method', [ 'thing' => 'dorito' ])`
   * if your [route](#routing) captures url parameters, they're available through `$o['params']`

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
Everything you declare in your [controller](#controllers-c) 
with `$this->...` is available in your view.
Based on our [example controller](#controllers-c) above,
these would be some example views:

```php
# views/yours.foo.php
<div>
	<?= $in_view ?>
</div>
```

```php
# views/yours.bar.php
<div>
	<?= $other_stuff ?>
</div>
```

### Subrendering
You can subrender `bar` in `foo` using `r(controller, view)`
```php
# views/yours.foo.php
<div>
	<?= $in_view ?>
	<?= r('yours', 'bar') ?>
</div>
```
**Outputs:**
```html
<div>
	I'm available to the view
	<div>
		Stuff
	</div>
</div>
```

You may *skip* the rendering of any view by calling `$this->skip()`
in the view's [controller](#controllers-c) method.
```php
class controller_yours extends controller_base {
	function my_view($o) {
		$required_id = take($o['params'], 'id', false);
		if (!$required_id) 
			return $this->skip(); # skips rendering views/yours.my_view.php

		# ... rest of method ...	
	}
}
```


## Assets
Assets are loaded per [view](#views-v), in order with duplicates ignored.
Here's an example [view](#views-v) (`view/foo.bar.php`) with its 
own [JavaScript](http://en.wikipedia.org/wiki/JavaScript)
in `public/js/foo.bar.js` and [CSS](https://en.wikipedia.org/wiki/Cascading_Style_Sheets) 
in `public/css/foo.bar.css`
```php
<? app::asset('foo.bar', 'css') ?>
<? app::asset('foo.bar', 'js') ?>
<div>...</div>
```

## SCSS (Compass)
Write some [SCSS](http://sass-lang.com/) with [Compass](http://compass-style.org).
Changes in `scss` directory automatically reflect in `public/css` when you run:
```bash
sudo gem install compass compass_twitter_bootstrap
php script/scss_watch.php
```

### SCSS/Compass Example

* Start `compass watch` with `./marcel scss` or `script/scss_watch.php`
* Create a new `.scss` file in the `scss` directory

```scss
// scss/test.scss
#foo {
	a {
		color: blue;
		&:hover {
			color: green;
		}
	}
}
```

* On `scss/test.scss` save, `SASS` compiles that file to `public/css/test.css`

```css
#foo a {color:blue} #foo a:hover {color:green}
```

* Add `asset::add('test', 'css')` to your view to load `public/css/test.css`


## Layouts
`views/layouts/a.php` is the default layout. 
If you want to use an alternative layout, 
request the "layout name" (`l` in [Routes](#routes)) in `routes.php`:
```php
<?
app::$routes = [

	# Uses views/layouts/mylayout.php
	'/section' => ['c' => 'foo', 'm' => 'bar', 'l' => 'mylayout' ],

];
```

**layouts must contain** `<?= $yield ?>`

Layout rendering is automatically *skipped* by [XHR](http://en.wikipedia.org/wiki/XMLHttpRequest)
(AKA [AJAX](https://en.wikipedia.org/wiki/Ajax_(programming)) requests to make
updating [views](#views-v) easier. 

## Auth
In `class/auth.php`, you may *optionally* define `User` permissions for 
use in [controllers](#controllers-c) or [routes](#routes).

To test, use `model.User`'s `role` attribute 
(like the example auth class below) to gate [controller](#controllers-c) method access.

A scalable paradigm would be to write *feature-named* methods
that contain `User` `role` tests.
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

	# Users that may send email
	static function email_send() {
		# only admins and managers may send email
		return self::manager();
	}
}
```
Now you can test in the [controller](#controllers-c) for `auth::email_send()`
to keep non-managers/non-admins from sending email.

You may also test in the [routes](#routes) (`routes.php`) with `'auth' => ['email_send']`

## Cookies (and Notes)
Standard [API](http://en.wikipedia.org/wiki/Application_programming_interface) 
for cookie [CRUD](http://en.wikipedia.org/wiki/Create,_read,_update_and_delete) 
and `note` is available for one-time use.
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
Designed to use [Memcached](http://php.net/manual/en/book.memcached.php) 
on port `11211`. Here's a typical *get-if-set* pattern:
```php
$data = cache::get('cachekey', $found);
if (!$found) {
	$data = getData(); # arbitrary method
	cache::set('cachekey', $data, time::ONE_DAY);
}
echo $data;
```

### Generating Unique Cache Keys
Using `class/cache.php`'s `keygen` method, 
you can safely generate `SITE_NAME` specific, non-conflicting cache keys
for methods. Here is an arbitrary `get_user_data` function example:

```php
class example {
	static function get_user_data($id) {
		$safe_key = cache::keygen(__CLASS__, __FUNCTION__, $id);
		$data = cache::get($safe_key, $found, true); # true deserializes (since we're caching an object)
		if (!$found) {
			$data = User::find($id);
			cache::set($safe_key, $data, time::ONE_HOUR, true); # true serializes (since it's an object)
		}
	}
}

# invoke
$user = example::get_user_data(26);
```
If `SITE_NAME` is `define('SITE_NAME', 'Marcel')`,
`$safe_key` ends up looking like the `md5()` of `'Marcel::example::get_user_data::26'`

## Form Fields
Building forms from scratch is tedious. Let's use a 
[twitter bootstrap form](http://twitter.github.io/bootstrap/base-css.html#forms) builder! 
Here's an example:
```php
<?
$form = new form;
$form->open('/login', 'post')
->add('Username', new field('text', [
	'name' => 'username', 
	'placeholder' => 'Username'
]))
->add('Password', new field('password', [
	'name' => 'password', 
	'placeholder' => 'Password'
]))
->action(
	new field('submit', ['text' => 'Login'])
);

echo $form;
```
If you want to see everything `class/form.php` and `class/field.php` are capable of,
check out `controller/form_test.php#index`.

## On-The-Fly Image Manipulation (and Caching)
Using a modified version of [TimThumb](http://www.binarymoon.co.uk/projects/timthumb/), 
we can manipulate our images on the fly!

```php
app::$routes = [
	'/i' => ['c' => 'image', 'm' => 'process', 'nodb' => true, 'name' => 'Image Process' ],
	# Note: if you change 'name' from "Image Process"
	# make sure you update image::$process_path
];
```

Now any [view](#views-v), use `image::get()`

```php
# Path render
<?= image::get([
	'src' => '/img/drwho.jpg',
	'w'   => 100,
	'h'   => 100,
]) ?>

# http://site.com/i?src=%2Fimg%2Fdrwho.jpg&q=85&w=100&height=100&sig=ae52cd7c3f8792dcfec01180b37c5ea5

# Tag render
<?= image::get([
	'src' => '/img/drwho.jpg',
	'w'   => 200,
	'h'   => 200,
], true) ?>

# <img src="http://site.com/i?src=%2Fimg%2Fdrwho.jpg&q=85&w=200&h=200&sig=fea0f1f6fede90bd0a925b4194deac11" width="200" height="200" />

```

This new image will be cached and served from now on!
Using `nodb => true` in the route prevents unnecessary classes from loading
(since these images won't need database interaction).

The `sig` parameter prevents end-users (hackers, specifically) from creating their own resized
versions of images (e.g. hacker tries generating 10000 different-sized 
versions of the same image by updating `w` parameter to 10000 different values).

### `image::get` Parameters

Key | Description
--- | ---
`src` | Source: *default `''`* **required**
`w` | Width: *default `null`*, **required**
`h` | Height: *default `null`* **required**
`q` | Quality *default `85`*
`a` | Crop Alignment: *default `null`* `c`, `t`, `l`, `r`, `b`, `tl`, `tr`, `bl`, `br` *chainable* 
`zc` | Scale & Crop: *default `null`*  `0` size to fit (ugly), `1` crop resize (default), `2` proportional fit, `3` fill proportional
`f` | Filters: *default `null`* `1` invert, `2` grey, `3,<%>` Brightness, `4,<%>` Contrast, `5,<rgba>` Colorize, `6` Edges `7` Emboss `8` Gaussian, `9` Selective Blur, `10` sketch, `11` Smooth
`s` | Sharpen: *default `null`*
`cc` | Canvas Hex Color: *default `null`* (e.g. `'#ffffff'`)
`ct` | Canvas Transparency: *default `false`* (ignores `cc`)

## Utilities
In `class/util.php`, `class/html.php`, `class/size.php`, and `class/time.php`
you'll find many convenient methods to use from everything to obtaining
time in seconds, human readable byte sizes, string manipulation, debuggers and 
other shortcuts to use in this system.

Some utilities have shortcuts defined in [Helpers](#helpers).


## Helpers
Shortcuts for utility functions may be defined in `class/helpers.php`.

### Useful Helpers
Helper | Description
--- | ---
`r($controller, $method, [])` | Alias for `util::render`
`take($arrOrObj, 'key', 'fallback')` | Return the value of `$arrOrObj` by `key`. If not set, return `fallback`
`echoif($condition, $output)` | If `$condition` is `true`, `echo` `$output`
`ifset($a, $elseb, $elsec, ...)` | Return first argument that `isset`
`times(200, 'function_name')` | Repeat `function_name($i)` `200` times
`h('<script>alert('unsafe')</script>')` | Alias for [`htmlentities`](http://php.net/manual/en/function.htmlentities.php)
`pr($mixed)` | Alias for [`print_r`](http://php.net/manual/en/function.print-r.php)
`pp($mixed)` | Pretty-Print `print_r` (wrap with `<pre>`)
`pd($mixed)` | Pretty-Print that [`die`](http://php.net/manual/en/function.die.php)s after
`json($mixed)` | Safely `die()` out [`json_encode`](http://php.net/manual/en/function.json-encode.php) data
`_403()` | Redirect to `controller/status_code.php#forbidden`
`_404()` | Redirect to `controller/status_code.php#not_found`
`_500()` | Redirect to `controller/status_code.php#fatal_error`


## Cron
To register new cron jobs, add entries through `model/Cron_Job.php`
or use the gui for `controller/cron_job.php`
and add this to your system's crontab: (to edit your crontab, `sudo crontab -e`)
```crontab
* * * * * /usr/bin/php /var/www/site/script/cron.base.php > /dev/null 2>&1
```
`script/cron.base.php` will be hit every minute running any `script`s
that have matching cron `frequency` entries.

## Workers
Using the `Worker` [model](#models-m), you can add long-running
processes to a "job queue" using `Worker::add`.
To *start* a worker server, run `php script/worker.php <optional-thread-count>`.

### Worker Example
`example::long_running` takes 10 seconds to complete
each time it is invoked. If you called `example::long_running`
`10000` times, it would take over almost `28` hours to 
execute them all. With `Worker::add`, you can queue them up
and execute them in paralell as background processes.
```php
class example {
	static function long_running(array $args) {
		sleep(10);
		echo take($args, 'foo');
	}
}

# Spawn 10000, slow running jobs
times(10000, function($i) {
	Worker::add([
		'class'  => 'example', 
		'method' => 'long_running',
		'args'   => [
			'foo' => $i,
		],
	]);
});
```

<!--
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
-->

## Mail
Uses [PHPMailer](https://github.com/Synchro/PHPMailer) via the `class/mail`. 
Check out `class/mail.php` to see everything you can do.

```php
$m = new mail;
$m->From     = 'you@example.com';
$m->FromName = 'Marcel';
$m->AddAddress('user@example.com', 'Example User');
$m->Subject  = "Queue Test";
$m->Body     = "This concludes the test!";

# Add it to the worker queue
Worker::add([
	'class'  => 'mail',
	'method' => 'queue',
	'args'   => [
		'email' => $m, #serialize email
	],
]);

# Or just send it
$m->Send();
```

## Fake Data
Using [Faker](https://github.com/fzaninotto/Faker) via the `class/fake.php` class,
you can generate fake (aka "dummy") data for testing your app. 
```php
# Generate 250 fake users
times(250, function() {
	$u = new User;
	$u->first    = fake::firstName(); # Marcel
	$u->last     = fake::lastName(); # Shellington
	$u->email    = fake::safeEmail(); # marcel@example.com
	$u->username = fake::userName(); # dorito_hanglider7
	$u->role     = 'user';
	$u->password = User::spass('testing');
	$u->save();
});
```

## Scraping
Using [PHPSimpleDom](http://simplehtmldom.sourceforge.net) via `class/dom.php`,
you can scrape any webpage for data and parse specific sections with
jQuery/Sizzle style selectors.
```php
$html = dom::get_html('http://www.danmasq.com');
$images = $html->find('img');
$sources = [];
foreach ($images as $i)
	$sources[] = $i->src;

pr($sources); # array of <img> "src" attribute values
```


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

## Vim Interactivity
Marcel loves [Vim](http://en.wikipedia.org/wiki/Vim_(text_editor) and knows
that interactive prompts can be annoying to use (one line at a time), so
we made a way to quickly `eval` data through a vim session:

To use interactive Vim, `php script/vim.php`. This will start a
new Vim session with `tmp/vim-output.php` open. In this file, you'll
automatically have access to all of the framework classes/variables/etc.

By default (the first time you open it), `tmp/vim-output.php` looks like this:
```php
<?
echo "Hello, Vim!\n";
```

When you save and exit (`ZZ` or `:wq`) this Vim buffer, 
the contents of `tmp/vim-output.php` will be evaluted.

### Vim usage example
While in your current Vim session: 

1. `:!./marcel vim` to run our `script/vim.php` 
2. New Vim session opens with `tmp/vim-output.php` buffer
3. Write some code: **See example code below**
4. Save buffer and exit Vim with `ZZ` or `:wq`
5. Observe output: e.g. something like: `admin@example.com`
6. `fg` to get back into your original Vim session

Example `tmp/vim-output.php` in step *4* above:
```php
<?
$users = User::find('all', [
	'select' => 'email', 
	'limit'  => 1,
]);

foreach ($users as $u)
	echo "{$user->email}\n";
```

A usefil `~/.vimrc` addition might be:

```viml
map <silent> <Leader>x :!./marcel vim<cr><cr>
```
So, if your [Leader](http://vimdoc.sourceforge.net/htmldoc/map.html#<Leader>) key 
is `,` then `,x` will launch the Marcel Vim buffer.



## WebSocket Server
Running `php -q script/socket_server.php` will start up a websocket server
that has access to all of the framework methods. 
TODO: working on getting User sessions data in the `socket_user` constructor.

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
