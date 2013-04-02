# Marcel
*The PHP 5.4 MVC with shoes on*

## Requirements
* PHP 5.4
* MySQL

## Install
Clone and run the db init wizard:

```bash
git clone git@github.com:dancrew32/marcel.git site
cd site
php script/db_init.php
```

## Generate
```bash
# Controller
php script/gen_controller.php

# Model
php script/gen_model.php

# View
php script/gen_view.php

# Script
php script/gen_script.php  
```

## Watch SCSS
Changes in `scss` directory reflect in `html/css`
```bash
sudo gem install compass
compass watch &
```

## Create Users
```bash
php script/create_user.php
```

## Models
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

## Routes
In `routes.php`, we send url `$_SERVER['REQUEST_URI']` matches to controllers and views.
You may capture paramters using regular expressions.
```php
app::$routes = [

	# Site base url leads to controller_yours::foo
	'/' => ['c' => 'yours', 'm' => 'foo'],

	# Capture page id, name capture "id" with (?P<capturename>regexp) syntax
	'/page/(?P<id>[0-9]+)' => ['c' => 'yours', 'm' => 'test'],

];
```

## Controllers
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
		$page_id = take($o['m'], 'id', 1);	
	}
}
```

## Views
Everything you declare in your controller with `$this->...` is available in your view.
```php
<div>
	<?= $in_view ?>
</div>
```

You can subrender `bar` in `foo` using `r(controller, view)`
```php
<div>
	<?= $in_view ?>
	<?= r('yours', 'bar') ?>
</div>
```

