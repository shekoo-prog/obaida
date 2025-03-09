<?php

use App\Core\Helpers\Helper;

$view->extends('layouts.app') ?>

<?php $view->section('title') ?>
Home Page
<?php $view->endSection() ?>

<?php $view->section('header') ?>
<nav>
    <a href="/">Home</a>
</nav>
<?php $view->endSection() ?>

<?php $view->section('content') ?>
<div class="container">
    <h1>Welcome to <?= Helper::env('APP_NAME') ?></h1>
    <p>Start building something amazing!</p>
</div>
<?php $view->endSection() ?>