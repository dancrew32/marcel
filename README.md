# Marcel
**The MVC with Shoes On**

![Marcel](http://i.danmasq.com/marcelmvc.png) 

## Contents *stable*
* [Requirements](#requirements)
* [Install](#install)
* [VirtualHost Setup](#virtualhost-setup)
* [Scripts](#scripts)
* [Routing](#routing)
* [Models](#models-m)
* [Controllers](#controllers-c)
* [Views](#views-v)
   * [Mustache](#mustache)
   * [Markdown](#markdown)
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
* [Mail Parsing](#mail-parsing)
* [Fake Data](#fake-data)
* [CAPTCHA](#captcha)
* [OCR](#ocr)
* [Scraping](#scraping)
* [Phone Calls & Text Messaging](#phone-calls--text-messaging)
* [Interactive Prompt](#interactive-prompt-with-phpsh)
* [Vim Interactivity](#vim-interactivity)
* [WebSocket Server](#websocket-server)
* [Linode](#linode)

### Contents *unstable*
* [Git](#git)
* [Profiling](#profiling-with-xhprof)
* [XDebug](#xdebug)
* [Selenium & Webdriver](#selenium--webdriver)
* [BitTorrent](#bittorrent)

### Contents *future*
* USPS 
* Geolocation
* Stocks
* Cart
   * Stripe
* FFMPEG
* Waveform Generation
* Geometry
* Vimeo/Youtube
* Instagram
* Facebook
* Twitter
* Bitcoin
* Travis CI
* RSS
* Emoji
* Color Manipulation
* IRC/Jabber
* Face Detection
* AWS
* App Engine

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
git submodule update
php script/db_init.php
chmod 777 -R tmp .git
chmod 777 marcel vendor .gitmodules
cat config/api.php.example > config/api.php 
```

After install, it will prompt you to seed the database with defaults
and create your first user. You should also set your 
`public/index.php` and `BASE_URL`.

## VirtualHost Setup
```htaccess
<VirtualHost *:80>
	ServerName site.com
	DocumentRoot /var/www/site/public
	SetEnv ENV "DEV" # or "LIVE"
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

Just run `./m <sitename>` (so maybe `./m marcel` for `site/marcel`) from the root directory to get a menu
of scripts to run! 

When you're comfortable with the
list of scripts you have, use the search shortcut
to immediately run that script `./m <site> <search>` 
(so maybe `./m <site> dbdump` to run `php site/<site>/script/db_dump.php`).

Every script is an easy to use interactive wizard:

### Wizards & Scripts
Wizard | Script Description
--- | ---
`php site/marcel/script/gen_controller.php` | [Controller](#controllers-c)
`php site/marcel/script/gen_model.php` | [Model](#models-m)
`php site/marcel/script/gen_view.php` | [View](#views-v)
`php site/marcel/script/gen_js_class.php` | JavaScript Module
`php site/marcel/script/gen_script.php` | Script/[Cron](#cron)
`php site/marcel/script/db_init.php` | DB initialization (see [Install](#install))
`php site/marcel/script/db_create_mysql_user.php` | Create a new [MySQL](http://dev.mysql.com/doc/refman/5.1/en/adding-users.html) user with permissions to *only this* `DB_NAME`
`php site/marcel/script/db_dump.php` | DB *dump* in `db/dump`	
`php site/marcel/script/db_restore.php` | DB *restore* from `db/dump`
`php site/marcel/script/db_schema_apply.php` | DB *apply* a schema in from `db/schema`
`php site/marcel/script/db_schema_update.php` | DB *update* *all* schemas in from `db/schema`
`php site/marcel/script/create_user.php` | Create `User`s (e.g. Create your first `User` with `role` of `admin`)
`php site/marcel/script/cron.base.php` | Run each `Cron_Job` if `Cron_Job->frequency` matches `time()` and is `active`
`php site/marcel/script/scss_watch.php` | Run `compass watch` as daemon to watch [SCSS](#scss-compass)
`php site/marcel/script/worker.php` | Start a [Worker](#workers) server
`php site/marcel/script/vim.php` | Start an [Interactive Vim](#vim-interactivity) `eval` session
`php site/marcel/script/fake_users.php` | Create `250` fake `User`s with role of `user`

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

Take a look at `class/route.php` to see all of the possibilities.

### Route Keys

Key | Description
--- | ---
`c` | Controller *required*
`m` | Method *required*
`l` | Layout (`foo` would be `view/layout/foo.php`)
`auth` | Authorization `Feature->slug` via `class/auth.php` to gate access with
`name` | Unique name for this route (see `route::get($name, $params)` useful when URL paths change)
`section` | Name for grouping routes together (e.g. `Portfolio`) (see `route::in_sections(['A', 'B']))
`http` | for nested [REST](https://en.wikipedia.org/wiki/Representational_state_transfer) routing (e.g. `get`, `post`, `put`, `delete`)
`nodb` | if `true`, skip any database connections for this execution

### Example Route Implementation

```php
<?
route::$routes = [

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
		'auth' => ['feature_name'], # only users who can do "feature_name"
	],
	'/auth-test-complex' => [ 
		'http' => [
			'get' => [
				'c' => 'common', 'm' => 'auth_test',
				'auth' => ['thing_a'], # only "thing_a" feature-allowed users may GET
			],
			'post' => [
				'c' => 'common', 'm' => 'auth_test',
				'auth' => ['thing_b'], # only "thing_b" feature-allowed users may POST
			],
		],
		'auth' => ['thing_c'], # "thing_c" may GET and POST
	],


	# Named-Routes & Sections (optional)
	'/changes-frequently' =>
		[ 'c' => 'thing' => 'm' => 'index', 'name' => 'Things Home', 'section' => 'Things' ],
		# route::get('Things Home') 
		#   returns '/changes-frequently'

	'/changes-as-well(?:/*)(?P<url_slug>\d+)' =>
		[ 'c' => 'thing' => 'm' => 'secondary', 'name' => 'Things Secondary', 'section' => 'Things' ],
		# route::get('Things Secondary', ['url_slug' => 'true-story']) 
		#   returns '/changes-as-well/true-story'
		# route::get('Things Secondary', ['url_slug' => 'true-story', 'foo' => 'bar']) 
		#   returns '/changes-as-well/true-story?foo=bar'

];
```

## Models (M)
Uses [PHPActiveRecord](http://www.phpactiverecord.org)
(see [Basic CRUD](http://www.phpactiverecord.org/projects/main/wiki/Basic_CRUD) to learn more).
Get started with a new Model using the `php script/gen_model.php` [Script](#scripts).

### Simple Model Example
```php
<?
class Thing extends model {
	static $table_name = 'things';
}

# Create: http://www.phpactiverecord.org/projects/main/wiki/Basic_CRUD#create
$t = new Thing;
$t->stuff = "raisin";
$t->save();

# Read: http://www.phpactiverecord.org/projects/main/wiki/Finders
$b = Thing::find(1);
echo $b->stuff; # "raisin"

# Update: http://www.phpactiverecord.org/projects/main/wiki/Basic_CRUD#update
$b->stuff = "dorito";
$b->save();

# Destroy: http://www.phpactiverecord.org/projects/main/wiki/Basic_CRUD#delete
$b->delete();
```

### Complex Model Example
```php
<?
class Stuff extends model {
	static $table_name = 'stuff';


/*
 * RELATIONSHIPS
 * Read More: http://www.phpactiverecord.org/projects/main/wiki/Associations
 */

	# `thing_id` in `stuff_types` table: $stuff->type (Stuff_Type object)
	static $has_one = [
		[ 'type', 'class_name' => 'Stuff_Type' ], 
	];

	# `thing_id` in `owners` table: $stuff->owners (collection of Owner objects)
	static $has_many = [
		[ 'owners', 'class_name' => 'Owner' ], 
	];

	# `thing_id` in `stuff` table: $stuff->thing (Thing object)
	static $belongs_to = [
		[ 'thing', 'class_name' => 'Thing' ],
	];


/*
 * VALIDATION
 * Read More: http://www.phpactiverecord.org/projects/main/wiki/Validations
 */
	# Existence
	static $validates_presence_of = [
		['name', 
			'message' => 'must be present!'], # "Name must be present!"
	];

	# Length
	static $validates_size_of = [
		# Exact
		['field_a', 
			'is'      => 42, 
			'message' => 'must be exactly 42 chars'], # "Field_a must be exactly 42 chars"

		# Minimum
		['field_b', 
			'minimum'   => 9, 
			'too_short' => 'must be at least 9 characters long'],

		# Maximum
		['field_c', 
			'maximum'  => 20, 
			'too_long' => 'is too long!'],

		# Min/Max
		['field_d', 
			'within'    => [5, 10],
			'too_short' => 'must be longer than 5 (less than 10)',
			'too_long'  => 'must be less than 10 (greater than 5 though)!'
		],
	];

	# Includes
	static $validates_inclusion_of = [
		['categories', 
			'in' => ['list', 'of', 'allowed', 'categories'], ], # "list is not included in the categories"
	];

	# Exclude
	static $validates_exclusion_of = [
		['password', 
			'in'      => ['list', 'of', 'bad', 'passwords'],
			'message' => 'is weak'], # "Password is weak"
	];

	# Numbers
	static $validates_numericality_of = [
		['price',    'greater_than' => 0.01], # Things must be > a penny
		['quantity', 'only_integer' => true], # Prevent ordering 4.199 shoes.
		['shipping', 'greater_than_or_equal_to' => 0], # No negative shipping
		['discount', 
			'less_than_or_equal_to'    => 5, 
			'greater_than_or_equal_to' => 0],
	];

	# Unique
	static $validates_uniqueness_of = [
		['email', 
			'message' => 'Sorry that email is taken'],
	];

	# Regular Expression
	static $validates_format_of = [
		['email', 'with' =>
			'/^[^0-9][A-z0-9_]+([.][A-z0-9_]+)*[@][A-z0-9_]+([.][A-z0-9_]+)*[.][A-z]{2,4}$/'],
		['password', 'with' =>
			'/^.*(?=.{8,})(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).*$/', 
			'message' => 'is too weak'],
	];


/*
 * CALLBACKS
 * Read More: http://www.phpactiverecord.org/projects/main/wiki/Callbacks
 * all take array of instance method names
 */
	static $before_save = ['before_save'];     # called before a model is saved
	static $before_create = [];                # called before a NEW model is to be inserted into the database
	static $before_update = [];                # called before an existing model has been saved
	static $before_validation = [];            # called before running validators
	static $before_validation_on_create = [];  # called before validation on a NEW model being inserted
	static $before_validation_on_update = [];  # same as above except for an existing model being saved
	static $before_destroy = [];               # called after a model has been deleted
	static $after_save = [];                   # called after a model is saved
	static $after_create = [];                 # called after a NEW model has been inserted into the database
	static $after_update = [];                 # called after an existing model has been saved
	static $after_validation = [];             # called after running validators
	static $after_validation_on_create = [];   # called after validation on a NEW model being inserted
	static $after_validation_on_update = [];   # same as above except for an existing model being saved
	static $after_destroy = ['after_destroy']; # called after a model has been deleted

	function before_save() {
		$this->saved++; # increment a saved column
	}

	function after_destroy() {
		$thing = Thing::find($this->thing_id); # find some related thing
		$thing->destroy(); # destroy it
	}


/*
 * GETTERS
 * Read More: http://www.phpactiverecord.org/projects/main/wiki/Utilities#attribute-getters
 * Make sure to use $this->read_attribute($name)
 */
	function &__get($name) { # observe pass-by-reference
		switch ($name) {
			default:
				$out = h($this->read_attribute($name));
		}
		return $out;
	}


/*
 * SETTERS
 * Read More: http://www.phpactiverecord.org/projects/main/wiki/Utilities#attribute-setters
 * Make sure to use $this->assign_attribute($name, $value);
 */
	function __set($name, $value) {
		switch ($name) {
			case 'special_column':
				$value = preg_replace('/[^0-9]/', '', $value); # strip non-numeric
				break;
		}
		return $this->assign_attribute($name, $value);
	}

}
```

## Controllers (C)

* This file would be `controller/yours.php`
* `foo()` would pass variables to `view/yours.foo.php`
* `bar()` would pass variables to `view/yours.bar.php`
* `$o` contains optional parameters passed in the `3rd` argument of `r('controller', 'method', [ 'thing' => 'dorito' ])`
   * if your [route](#routing) captures url parameters, they're available through `$o`

Get started with a new Controller using the `php script/gen_controller.php` [Script](#scripts).

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
		$page_id = take($o, 'id', 1);	
	}
}
```

## Views (V)
Everything you declare in your [controller](#controllers-c) 
with `$this->...` is available in your view.
Based on our [example controller](#controllers-c) above,
these would be some example views:

Get started with a new View using the `php script/gen_view.php` [Script](#scripts).

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
		$required_id = take($o, 'id', false);
		if (!$required_id) 
			return $this->skip(); # skips rendering views/yours.my_view.php

		# ... rest of method ...	
	}
}
```

## Mustache

You may render [Mustache](mustache.github.io) templates 
using [Mustache PHP](https://github.com/bobthecow/mustache.php)
by creating a file in the `view` directory with the the naming convention of:
`controller.method.mustache`.

### Mustache Example

For `controller/mustachetest.php`:

```php
class controller_mustachetest extends controller_base {
	function template() {
		$this->test = date('H:m:s');
		$users = User::all();
		$this->user = [];
		foreach ($users as $u)
			$this->user[] = [
				'first' => $u->first,
				'last'  => $u->last,
			];
		if (AJAX)
			json($this);
	}
}
```

Create `view/mustachetest.template.mustache`:

```mustache
{{test}}
<h3>
	Users are:
</h3>
{{#user}}
<strong>{{first}}</strong>
<em>{{last}}</em>
{{/user}}
<br>
```

Render `mustachetest.template`:
```php
<?= r('mustachetest', 'template') ?>
```

Output looks like
```html
12:00:00
<h3>
	Users are:
</h3>
<strong>Marcel</strong>
<em>Shell</em>
<strong>Dan</strong>
<em>Masquelier</em>
<br>
<script id="mustachetest-template" type="text/mustache">
{{test}}
<h3>
	Users are:
</h3>
{{#user}}
<strong>{{first}}</strong>
<em>{{last}}</em>
{{/user}}
<br>
</script>
```

Now, using [Hogan.js](https://github.com/twitter/hogan.js)
you can take the provided template from the `<script>` tag
and use it to render client-side. You'll only have to 
serve JSON now!

```javascript
var template = Hogan.compile($('#mustachetest-template').html());
$.getJSON('/mustache/template', function(json) {
	var html = template.render(json);
	$(document.body).append(html);
});
```

## Markdown
Using [Markdown PHP](http://michelf.ca/projects/php-markdown/) you may render
[Markdown](http://daringfireball.net/projects/markdown/syntax) in your views
by naming them `controller.method.md`.

### Markdown Example

This file would be `view/markdowntest.main.md`:

```markdown
# Heading 1
## Heading 2

[Link](/home)

* List 1
* List 2
* List 3
```

See `controller/markdowntest.php` and `view/markdowntest.main.md`
for more examples


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
sudo gem install animation --pre
php site/marcel/script/scss_watch.php
```

### SCSS/Compass Example

* Start `compass watch` with `./m marcel scss` or `php site/marcel/script/scss_watch.php`
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
route::$routes = [

	# Uses views/layouts/mylayout.php
	'/section' => ['c' => 'foo', 'm' => 'bar', 'l' => 'mylayout' ],

];
```

**layouts must contain** `<?= $yield ?>`

Layout rendering is automatically *skipped* by [XHR](http://en.wikipedia.org/wiki/XMLHttpRequest)
(AKA [AJAX](https://en.wikipedia.org/wiki/Ajax_(programming)) requests to make
updating [views](#views-v) easier. 

## Auth
Via `auth::can()` in `class/auth.php`, using `model/Feature.php`
in comparison with `model/User.php`'s `user_type_id`,
you may gate access to features (which are loosely defined for
flexibility) via `model/User_Permission.php`.

### Auth Example
Create a `Feature` called `buy_book`,
set what users may access `buy_book`'s `$feature->id`
via `User_Permission`, then test with `auth::can(['buy_book'])`
to allow specific users the ability to buy books.

You may also test in the [routes](#routes) (`routes.php`) with `'auth' => ['buy_book']`

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

# Notes in $_SESSION
note::set('store_in_db', "I'm in the session", true);
note::get('store_in_db', true); # "I'm in the session"
note::get('store_in_db', true); # ""
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
Check out `class/form.php` and `class/field.php` to learn about all of the form/field features.

### Simple Implementation
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

### Real-World Implementation
In this example, we want an `add` and an `edit` form.
We'll render them in our view using:
```php
Add a new user:
<?= r('test', 'add_form') ?>

Edit User id:1
<?= r('test', 'edit_form', ['id' => 1]) ?>
```

Now, this is a very opinionated approach, but it
scales fantastically! The form builder prevents you
from needing to build forms in your views. This
strategy also lets you show your validation, restore
previously submitted values after submission and redirection,
and keeps fields in the controller so that you
can keep track of where you need to conditionally 
show fields.

Keep an eye out for `$model->to_note()` and `$model->from_note()`
for saving/restoring values after redirect as well as displaying
field errors with `$model->error_class('field_name')` and
`$model->take_error('field_name')` to display the model's
validation message.

```php
<?
class controller_test extends controller_base {

	function __construct($o) {
		$this->root_path = route::get('User Home');
		auth::only(['user']); # only people with `user` user_permission may view
		parent::__construct($o);
   	}
 
/*
 * ACTIONS
 */
	function add($o) {
		$user = User::create($_POST);
		if ($user) { # success
			note::set('user:add', $user->id); # set note so we can say, "User Created" after redirect
			$this->redir(); # redirect to $this->root_path
		}

		$user->to_note(); # if there were errors, display them and any values passed after reload
		$this->redir(); # redirects to $this->root_path
	}

	function edit($o) {
		$this->user = User::find_by_id(take($o, 'id'));
		if (!$this->user) $this->redir();
		if (!POST) return;

		# Don't change password if it's blank
		if (!isset($_POST['password']{0}))
			unset($_POST['password']);

		# handle default booleans
		$_POST['active'] = take_post('active', 0);

		$ok = $this->user->update_attributes($_POST);
		if ($ok) {
			note::set('user:edit', $this->user->id); # set note so we can say, "User Created" after redirect
			$this->redir();
		}

		$this->user->to_note(); # if there were errors, display them and any values passed after reload
		app::redir(route::get('User Edit', ['id' => $this->user->id]));
	}

/*
 * FORMS
 */
	# no view file
	function add_form() {
		$user = new User;
		$user = $user->from_note(); # extract errors/prevously submitted values

		$this->form = new form;
		$this->form->open(route::get('User Add'), 'post');
		$this->_build_form($user);
		$this->form->add(new field('submit_add'));
		echo $this->form;
	}

	# no view file
	function edit_form($o) {
		$user = take($o, 'user');
		$user = $user->from_note(); # extract errors/prevously submitted values
		if (!$user) $this->redir();

		$this->form = new form;
		$this->form->open(route::get('User Edit', ['id' => $user->id]), 'post');
		$this->_build_form($user);
		$this->form->add(new field('submit_update'));
		echo $this->form;
	}

	# Form Fields!
	private function _build_form($user) {

		# Email 
		$email_group = [ 'label' => 'Email', 'class' => $user->error_class('email') ]; 
		$email_help  = new field('help', [ 'text' => $user->take_error('email') ]);
		$email_field = new field('email', [ 
			'name'         => 'email', 
			'class'        => 'input-block-level email required',
			'autocomplete' => false,
			'value'        => take($user, 'email'),
		]);

		# Password
		$password_group = [ 'label' => 'Password', 'class' => $user->error_class('password') ];
		$password_help  = new field('help', [ 'text' => $user->take_error('password') ]);
		$password_field = new field('password', [ 
			'name'         => 'password', 
			'class'        => 'input-block-level',
			'autocomplete' => false,
			# don't set password value for security
		]);

		# User Type
		$user_type_group = [ 'label' => 'User Type', 'class' => $user->error_class('user_type_id') ]; 
		$user_type_help  = new field('help', [ 'text' => $user->take_error('user_type_id') ]);
		$user_type_field = new field('select', [ 
			'name'        => 'user_type_id', 
			'class'       => 'input-block-level',
			'options'     => User_Type::options(),
			'value'       => take($user, 'user_type_id') ? $user->user_type_id : User_Type::default_id(),
		]);

		# Active
		$active_field = new field('checkbox', [ 
			'name'    => 'active',
			'label'   => 'Activated',
			'inline'  => true,
			'checked' => take($user, 'active'),
		]);

		# Build Form
		$this->form
			->group($email_group, $email_field, $email_help)
			->group($password_group, $password_field, $password_help)
			->group($user_type_group, $user_type_field, $user_type_help)
			->group($active_field)
			;

	}

}
```

If you want to see everything `class/form.php` and `class/field.php` are capable of,
check out `controller/form_test.php#index` and observe other examples found
in many of the CRUD-style controllers.

## On-The-Fly Image Manipulation (and Caching)
Using a modified version of [TimThumb](http://www.binarymoon.co.uk/projects/timthumb/), 
we can manipulate our images on the fly!

```php
route::$routes = [
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
`r` | Reveilable *default `0` if off* uses `js/class/unveil.js` to fade images in as they enter the viewport

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
* * * * * /usr/bin/php /var/www/site/marcel/script/cron.base.php > /dev/null 2>&1
```
`script/cron.base.php` will be hit every minute running any `script`s
that have matching cron `frequency` entries.

## Workers
Using the `Worker` [model](#models-m), you can add long-running
processes to a "job queue" using `Worker::add`.
To *start* a worker server, run `php site/marcel/script/worker.php <optional-thread-count>`.

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
$m->from     = 'you@example.com';
$m->from_name = 'Marcel';
$m->add_address('user@example.com', 'Example User');
$m->subject  = "Queue Test";
$m->body     = "This concludes the test!";

# Add it to the worker queue
$m->queue();

# Or just send it
$m->send();
```

## Mail Parsing
Using [PHP MIME Mail Parser](https://code.google.com/p/php-mime-mail-parser/)
you may read inbound emails and parse out specific sections of the email
like `subject`, `to`, `cc`, `body` (`html` or `text`) and any other headers
you might like. Check out `class/mail_parse.php` to see everything you can do.

### Mail Parser Install

```bash
sudo pecl install mailparse
sudo apt-get install postfix
sudo echo "\nextension=mailparse.so" >> /etc/php5/cli/php.ini
sudo service apache restart
```

### Mail Parser Setup
Using [Postfix](http://www.postfix.org/) will allow you to create 
[virtual maps](http://www.berkes.ca/guides/postfix_virtual.html] 
that will let you route wildcard email
addresses for specific domain(s) to hit specified aliases 
in your `/etc/aliases`.

`/etc/postfix/virtual` *will be a new file*. In this example,
we route all emails that go to site.com to `/etc/aliases`
alias named `site`

```bash
@site.com site
```

Then you'll edit `/etc/postfix/main.cf` adding your `site.com` domain to
the `mydestination` list and adding the new line

```cf
#...
mydestination = site.com, localhost
#...
virtual_alias_maps = hash:/etc/postfix/virtual
```

Then setup your new `/etc/aliases` alias of `site` and pipe the output
to a script in Marcel. In this example, we route incoming
emails to our `script/email_incoming.php`

```bash
site: "| /usr/bin/php /var/www/site/script/email_incoming.php" 
```

After saving, the last step is to reload `/etc/aliases` and restart Postfix.

```bash
newaliases
service postfix reload
```

Now if you send an email to `foo@site.com`, the contents of that email
will route to `script/email_incoming.php` via `php:://stdin`. In this example,
`$data` becomes the raw contents of the email we just sent.

```php
$data = file_get_contents('php://stdin');
```

### Parsing an Email
Now to actually extract the contents of the email, 
you may leverage `mail_parse` of `class/mail_parse.php`.
See that class for more info.

```php
$mp = new mail_parse(file_get_contents('php://stdin'));
$to        = $mp->to();        # foo@site.com
$from      = $mp->from();      # you@yourmail.com
$from_name = $mp->from_name(); # Your Name (if exists)
$cc        = $mp->cc();        # if you cc'd people it would show up here 
$subject   = $mp->subject(); 
$body      = $mp->body();
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
	$u->password = 'testing';
	$u->save();
});
```

## CAPTCHA

The `captcha` class allows you to generate captcha images
using fonts from the `font` directory, merged on top of
complex background images in the `public/img/captcha` directory.

```php
class controller_captcha extends controller_base {
	function get() {
		$captcha = captcha::get();
		header('Content-type: image/png');
		imagepng($captcha);
	}

	function post() {
		$code = take($_POST, 'code');
		$ok = captcha::test($code);
		pd($ok);
	}
}
```

```html
<h2>Captcha</h2>
<img src="/captcha">

</h2>Solve it</h2>
<form action="/captcha" method="post">
<input name="code" >
<input type="submit" value="solve">
</form>
```

## OCR
You may use `ocr::get($file_path)` to perform 
[OCR](http://en.wikipedia.org/wiki/Optical_character_recognition).
```bash
# install this first
sudo apt-get install tesseract-ocr
```

### OCR Example
```php
$img = file_get_contents('http://.../some_image.jpg');
echo ocr::get($img); # returns text from image
# or use a different tesseract method
echo ocr::get($img, [ 'method' => ocr::SINGLE_COLUMN_VARIABLE_SIZE ]);
```

### OCR Methods
`method` | Description
--- | ---
`ocr::ORIENTATION_SCRIPT_ONLY` | Orientation and script detection (OSD) only
`ocr::AUTO_PAGE_SEG_OSD` | Automatic page segmentation with OSD
`ocr::AUTO_PAGE_SEG_NO_OSD` | Automatic page segmentation, but no OSD, or OCR 
`ocr::FULL_AUTO_NO_OSD` | Fully automatic page segmentation, but no OSD **Default**
`ocr::SINGLE_COLUMN_VARIABLE_SIZE` | Assume a single column of text of variable sizes
`ocr::UNIFORM_BLOCK_VERTICAL` | Assume a single uniform block of vertically aligned text
`ocr::UNIFORM_BLOCK` | Assume a single uniform block of text
`ocr::SINGLE_LINE` | Treat the image as a single text line
`ocr::SINGLE_WORD` | Treat the image as a single word
`ocr::SINGLE_WORD_CIRCLE` | Treat the image as a single word in a circle
`ocr::SINGLE_CHAR` | Treat the image as a single character


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


## Phone Calls & Text Messaging
Using [Twilio](http://www.twilio.com/), you may place phone calls
and send text messages. Once you've set your [API](https://www.twilio.com/user/account)
credentials and Twilio phone number in `config/api.php` for `twilio`, you may use methods in `class/phone.php`
to make calls and send text messages.

See `controller/phonetest.php` for more examples.

### Example Phone Call
```php
<?
class controller_phonetest extends controller_base {

	function call() {
		$phone_number = '555555555';

		# publicly-accessible url where Twilio may parse a TwiML file
		$program_url = route::get_absolute('Twilio Read');

		phone::queue_call($phone_number, $program_url); # or phone::call()
	}

	# This route name would be 'Twilio Read'
	function program() {
		# Only let twilio read this
		auth::check(phone::is_twilio());

		# say random text and hang up
		$text = fake::paragraph(rand(2,3)); # random text
		$p = phone::program();
		$p->say($text);
		$p->hangup();
		die($p); # Twilio reads TwiML (XML)
	}

}
```

### Example Text Message
```php
$phone_number = '5555555555';
$message = "Hi, my name is Marcel!";
phone::queue_text($phone_number, $message); # or phone::text()
```


## Interactive Prompt with PHPSH
Using [PHPSH](https://github.com/facebook/phpsh), 
you may interactively run the framework.
Install PHPSH:
```bash
cd ~
git clone git@github.com:facebook/phpsh.git
cd phpsh
python setup.py build
sudo python setup.py install
```

Then from the site root directory (`ROOT_DIR`) run:

```bash
phpsh site/marcel/script/inc.php
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

1. `:!./m marcel vim` to run our `script/vim.php` 
2. New Vim session opens with `tmp/vim-output.php` buffer
3. Write some code: **See example code below**
4. Save buffer and exit Vim with `ZZ` or `:wq`
5. Observe output: e.g. something like: `admin@example.com`
6. `fg` to get back into your original Vim session

Example `tmp/vim-output.php` in step *3* above:
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
map <silent> <Leader>x :!./m marcel vim<cr><cr>
```
So, if your [Leader](http://vimdoc.sourceforge.net/htmldoc/map.html#<Leader>) key 
is `,` then `,x` will launch the Marcel Vim buffer.


## WebSocket Server
You may create [scripts](#scripts) that run a WebSocket server using
`class/socket_server.php` and `class/socket_user.php`.

### Example Chat Server
In this example, we'll create a chat server called `script/chat_server.php` where
`chat_server` will extend `socket_server`. Run it via `./m marcel chat` or 
`php script/chat_server.php`.

```php
<?
require_once(dirname(__FILE__).'/inc.php');

class chat_server extends socket_server {

	protected $maxBufferSize = size::ONE_MB; # could be anything

	# When a user connects
	protected function connected($user) {
		# Match socket_user to actual User
		$session_id = $user->get_session_id();

		# Apply user to socket user
		$user->try_set_user($session_id);

		# Trigger our connect event
		$this->event_connect($user);
	}
	
	# When a user sends a message
	protected function process($user, $message) {
		$data = json_decode($message);

		switch ($data->event) {
			case 'foo::bar':	
				$this->event_foo_bar($user, $data);
				break;
		}
	}

	# When a user closes their connection
	protected function closed($user) {
		# Tell everyone that this user left
		$this->event_disconnect($user);

		$user->destroy(); # clean up

		# Tell everyone how many users are left
		$this->event_user_total($user);
		$data = $this->get_user_total_data($user);
		$this->send_all(json_encode($data)); # sends message to all users
	}


/*
 * EVENTS
 */
	function event_connect($user) {
		# $user->user is where we store our model/User.php object
		$name = $user->user ? $user->user->full_name() : 'Anonymous';

		$data = [
			'event' => 'foo::bar::response',
			'text'  => "{$name}: Joined the room.",
		];

		# Tell everyone this user joined
		$this->send_all(json_encode($data));

		# Tell only this user who is in the room
		$data = $this->get_user_total_data($user);
		$this->send($user, json_encode($data));
	}

	# Tell everyone when someone disconnects
	function event_disconnect($user) {
		$data = [
			'event' => 'foo::bar::response',
			'text' => "{$user->full_name()}: Left the room.",
		];

		$this->send_all(json_encode($data));
	}

	# Respond to specific event "foo::bar"
	function event_foo_bar($user, $data) {
		$text = h(trim(take($data, 'text'))); # sanitize

		if (!isset($text{0})) return false; # don't send blank messages

		$data = [
			'event' => 'foo::bar::response',
			'text' => "{$user->full_name()} says: {$text}",
		];

		$this->send_all(json_encode($data), [
			'sender' => $user,
			'sender_message' => json_encode($data),
		]);
	}


/*
 * DATA
 */
	# Get socket_user count stats
	function get_user_total_data($user) {
		$user_count = $this->user_count() - 1;

		if ($user_count) {
			$user_list = [];
			foreach ($this->users as $u)
				$user_list[] = $u->full_name();

			$user_list = util::list_english($user_list);

			$user_suffix = $user_count == 1 ? 'person' : 'people';
			$text = "Looks like there's {$user_count} {$user_suffix} here ({$user_list}).";
		} else {
			$text = "Looks like you're the only one here.";
		}

		return [
			'event' => 'foo::bar::response',
			'text'  => $text,
		];
	}
}

# Connect
function connect() {
	# if you were connecting to ws://site.com:7334
	new chat_server('site.com', '7334'); 
}

function shutdown() {
	db::init(); # Handle DB failures gracefully
	connect();
}
register_shutdown_function('shutdown');
```

I'll leave the JavaScript up to you, but here is a simple
example:

```javascript
var ws = new WebSocket('ws://site.com:7334');
ws.onopen = function() { };
ws.onclose = function() {};
ws.onmessage = function(msg) { 
	var data = $.parseJSON(msg.data)

	switch (data.event) {
		case 'foo::bar::response':
			console.log(data.text);
		break;
	}
};
```

## Linode
If you want to create manipulate & destroy servers,
you will enjoy hosting your websites on [Linode](http://www.linode.com/?r=42a13dd06a660e6330f596950ebfaade7f8b2f1d).

[Linode](http://www.linode.com/?r=42a13dd06a660e6330f596950ebfaade7f8b2f1d)'s awesome [API](https://www.linode.com/api/)
allows you to control your servers, load-balancers, setup-scripts, DNS, and general account data
all from marcel via `class/linode.php`.

To get started using the Linode API, you must first 
[obtain your API key](https://manager.linode.com/profile/index#apikey) 
and add it to `linode` in `/config/api.php`. Then you must run a few
`pear` installs:

```bash
sudo pear install Net_URL2-0.3.1
sudo pear install HTTP_Request2-0.5.2
sudo pear channel-discover pear.keremdurmus.com
sudo pear install krmdrms/Services_Linode
```

Now that the setup is complete, you may call linode api methods
through `class/linode.php` like so:

```php
# List of your servers
$linodes        = linode::_list();
$linode_options = ['LinodeID' => 11111];
$linode_configs = linode::config_list($linode_options);
$linode_disks   = linode::disk_list($linode_options);
$linode_ips     = linode::ip_list($linode_options);
$linode_jobs    = linode::job_list($linode_options);

# List of your domains 
$domains          = linode::domain_list();

# Resources for a specific domain (IP, port, domain name, record type, etc...) 
$domain_resources = linode::resource_list(['DomainID' => 111111]);

# List of your load balancers (NodeBalancer)
$balancers        = linode::balance_list();
$balancer_configs = linode::balance_config_list();
$balancer_nodes   = linode::balance_node_list();

# List of your configuration scripts (StackScript)
$scripts = linode::script_list();

# Account Stuff
$plans       = linode::plans();
$datacenters = linode::datacenters();
$distros     = linode::distros();
$kernels     = linode::kernels();
$api_key     = linode::api_key(['username' => '...', 'password' => '...']);
```

There are plenty more things you can do (just see `class/linode.php`) from
creating/deleteing/booting/rebooting/shutting-down/resizing/cloning etc.
See an example implementation of the methods in `controller/linode.php`.


## Git
Marcel loves [Git](http://git-scm.com/) and wants to do version control
work for you. Marcel takes care of most `git`
`fetch`, `pull`, `push`,
`add`, `rm`, `commit`, 
`checkout`, `branch`, 
`submodule`,
`log` and `diff` commands that you would normally have to do 
on the command line.

*Note* that you'll need to have run `chmod 777 -R .git && chmod 777 .gitmodules`
to let the `apache` user run `git` commands without permission issues.

Check out `controller/git.php` to see everything you can do.


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
wget http://xdebug.org/files/xdebug-2.2.3.tgz
tar -xvzf xdebug-2.2.3.tgz
cd xdebug-2.2.3
phpize
./configure
make
sudo cp modules/xdebug.so /usr/lib/php5/20100525+lfs/
# add to /etc/php5/apache2/php.ini:
# zend_extension = /usr/lib/php5/20100525/xdebug.so
```

## Selenium & WebDriver
Use `class/browser`, [Selenium](http://docs.seleniumhq.org/) 
and [WebDriver](https://github.com/Element-34/php-webdriver) 
to automate actual browser interactions (for testing or scraping).


### Install
```bash
wget -q -O - https://dl-ssl.google.com/linux/linux_signing_key.pub | sudo apt-key add -
sudo sh -c 'echo "deb http://dl.google.com/linux/chrome/deb/ stable main" >> /etc/apt/sources.list.d/google.list'
sudo apt-get update
sudo apt-get install xvfb firefox google-chrome-stable
wget http://chromedriver.googlecode.com/files/chromedriver_linux64_23.0.1240.0.zip
unzip chromedriver_linux64_23.0.1240.0.zip
sudo cp chromedriver /usr/local/bin
```

### Start Server
```bash
sudo php script/selenium.start.php
```

### Example Usage
This file could be `script/browser.test.php`

```php
<? require_once dirname(__FILE__).'/inc.php';

# Start a browser session
$b = new browser('firefox');

# See what your browser can do
pr($b->can_do());

# Set browser window size
$b->set_size(1024, 720);

# Navigate to website, capture all h1's
$site = 'http://twitter.github.io/bootstrap';
$h1s = $b->open($site)->wait_for('h1')->find('h1');

foreach ($h1s as $k => $h) {
	# the <h1> text
	echo "{$h->text()}\n";

	# Take a screenshot of each h1 with padding around each element
	$b->screenshot_part($h, IMAGE_DIR."/h1-{$k}.png", ['padding' => 5]);

	echo "http://". BASE_URL ."/img/h1-{$k}.png\n";
}

# Close up!
$b->close(); # or unset($b);
```

### Stop Server
```bash
sudo php script/selenium.stop.php
```


## BitTorrent
BitTorrent is a brilliant protocol for distributed P2P file sharing.
Using 
[Transmission](http://www.transmissionbt.com/)'s
[Tranmission Daemon](http://linux.die.net/man/1/transmission-daemon)
as a backend, over [RPC](http://en.wikipedia.org/wiki/Remote_procedure_call),
we can send `tranmission-daemon` a list of torrents to download to `tmp/torrent/<category>`.

### Setting up `transmission-daemon` (default)
```bash
sudo apt-get install transmission-daemon
```

Once installed, you should make sure the daemon will be secure 
(especially if you want to use Transmission's native web GUI) by
auditing `/etc/transmission-daemon/settings.json`. Make sure to
set things like `rpc-whitelist-enabled` (true), `rpc-whitelist`
(to allow only localhost and maybe your trusted IP's),
`rpc-port` (to something non-standard), and `rpc-password` 
(to something super complex) to name a few.

Setting your password is slightly complex.
Make sure you [follow these steps](http://superuser.com/a/113652). 
If you get stuck setting it up, see [Transmission Help](https://trac.transmissionbt.com/).

### Setting up `rtorrent`
```bash
sudo apt-get install libxmlrpc-c3-dev rtorrent php5-xmlrpc
# restart web server
```


### Proxy Setup

`TODO`: Forwarding `9091` default port to Apache with mod_proxy: http://www.linuxplained.com/transmission-apache-proxy-setup/

More on tunneling transmission through 
[SOCKv5 proxy](http://askubuntu.com/questions/63150/transmission-tracker-and-or-torrent-traffic-through-proxy).
Also need to investigate [TorSocks](https://code.google.com/p/torsocks/)

### Example Torrent Queue

Let's find some Torrent RSS Feeds [here](http://thepiratebay.sx/rss)
like the [UNIX feed](http://rss.thepiratebay.sx/303).

```php
# TRANMISSION SETTINGS
$transmission = [
	'host' => 'http://'. BASE_URL,	
	'port' => 9091,
	'path' => '/transmission/rpc',
];

# CURRENT MODE
$selected_feed = 'ubuntu';

# LIBRARY OF TORRENT FEEDS
$feeds = [
	'ubuntu' => [
		'rss'     => 'http://rss.thepiratebay.sx/303', # UNIX channel
		'search'  => ['12.04'], # version of ubuntu we want to capture
		'formats' => [], # could be ['iso']
	],
];

# ESTABLISH RPC CONNECTION
$t = new torrent([
	'rpc_url'          => "{$transmission['host']}:{$transmission['port']}{$tranmission['path']}",
	'formats_allowed'  => $feeds[$selected_feed]['formats'],
	'total_per_search' => 1, # get a maximum of 1 ubuntu 12.04 iso
	'rss'              => $feeds[$selected_feed]['rss'],
	'username'         => '<transmission username>',
	'password'         => '<transmission password>', 
]);

# START DOWNLOADING
$t->find_in_rss($feeds[$selected_feed]['search'])->init();

# DISPLAY FOUND & STARTED
foreach ($t->get() as $tor)
	echo "{$tor->id}. {$tor->name}\n";

#pr($t->stats());   # show download stats
#pr($t->session()); # show session stats

# $t->stop(); # stop all downloads
```





