<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title><?= $title ?></title>
<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
<meta content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0" name="viewport" />
<meta name="viewport" content="width=device-width">
<meta name="referrer" content="always">
<?= r('common', 'css') ?>
<!--[if lt IE 9]><script src="<?= config::$setting['js_dir'] ?>/html.shiv.js"></script><![endif]-->
</head>
<body class="<?= implode(' ', $body_classes) ?>">
<div class="container">
	<?= r('common', 'nav') ?>
</div>
<div class="container alpha-container">
	<?= $yield ?>
</div>
<hr>
<div class="container">
	<?= r('common', 'debug') ?>
</div>
<?= r('common', 'js') ?>
</body>
</html>
