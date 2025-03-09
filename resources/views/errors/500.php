<?php $view->extends('layouts.app') ?>

<?php $view->section('title') ?>
Server Error
<?php $view->endSection() ?>

<?php $view->section('content') ?>
<div class="error-500">
    <div class="error-code">500</div>
    <h1>Server Error</h1>
    <p>Sorry, something went wrong on our servers.</p>
    <a href="/" class="back-home">Back to Home</a>
</div>
<?php $view->endSection() ?>