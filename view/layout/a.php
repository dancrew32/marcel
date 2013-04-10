<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title><?= $title ?></title>
<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
<meta content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0" name="viewport" />
<meta name="viewport" content="width=device-width">
<?= $css ?>
<!--[if lt IE 9]><script src="/js/html.shiv.js"></script><![endif]-->
</head>
<body>

<?= r('common', 'nav') ?>

<div class="container">
	<?= $yield ?>
</div>

<div class="container">
	<?= r('common', 'debug') ?>
</div>
<?= $js ?>
</body>
</html>
