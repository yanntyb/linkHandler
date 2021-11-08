<!doctype html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link href="/assets/Css/reset.css" rel="stylesheet">
    <script src="/node_modules/chart.js/dist/chart.js"></script>
    <title><?= $title ?></title>
</head>
<body>
<?php
    if(isset($var)){
        if(isset($var["error"])){?>
            <div id="error"><?= $var["error"] ?></div><?php
        }
    }
?>
<?= $html ?>
</body>
<script src="https://kit.fontawesome.com/78e483bd6f.js" crossorigin="anonymous"></script>
</html>

