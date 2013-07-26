<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title><?= $title ?></title>
<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
<meta content="width=device-width,initial-scale=1.0,maximum-scale=1.0,user-scalable=0" name="viewport">
<meta name="viewport" content="width=device-width">
<meta name="referrer" content="always">
</head>
<body class="<?= implode(' ', $body_classes) ?>">
<div><?= $yield ?></div>
</body>
</html>
