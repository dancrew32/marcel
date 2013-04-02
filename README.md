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
