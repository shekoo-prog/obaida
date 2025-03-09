<?php
use App\Core\Helpers\Helper;
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $view->getContent('title') ?></title>
    <link rel="stylesheet" href="<?= Helper::env('APP_URL') ?>/assets/css/style.css">
    <link rel="stylesheet" href="<?= Helper::env('APP_URL') ?>/assets/css/error-404.css">
    <link rel="stylesheet" href="<?= Helper::env('APP_URL') ?>/assets/css/error-500.css">
</head>
<body>
    <div class="container">
        <header>
            <?= $view->getContent('header') ?>
        </header>

        <main>
            <?= $view->getContent('content') ?>
        </main>

        <footer>
            <p>&copy; <?= date('Y') ?> <?= Helper::env('APP_NAME') ?>. All rights reserved.</p>
        </footer>
    </div>
</body>
</html>