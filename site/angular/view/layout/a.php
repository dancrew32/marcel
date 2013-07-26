<!doctype html>
<html xmlns:ng="http://angularjs.org">
<head>
<meta charset="utf-8">
<title><?= $title ?></title>
<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
<meta content="width=device-width,initial-scale=1.0,maximum-scale=1.0,user-scalable=0" name="viewport">
<meta name="viewport" content="width=device-width">
<meta name="referrer" content="always">
<!--[if lte IE 8]><script src="/js/json3.js"></script><![endif]-->
<link href="//netdna.bootstrapcdn.com/twitter-bootstrap/2.3.2/css/bootstrap-combined.min.css" rel="stylesheet">
<script src="//ajax.googleapis.com/ajax/libs/angularjs/1.1.5/angular.min.js"></script>
<script src="/js/ui.bootstrap.js"></script>
<script src="/js/lab.js"></script>
<script>$LAB.script(['/js/app.js']).wait(function() { angular.bootstrap(document, ['app']); });</script>
</head>
<body class="<?= implode(' ', $body_classes) ?>">
<?= r('main', 'nav') ?>
<div ng-view></div>
</body>
</html>
