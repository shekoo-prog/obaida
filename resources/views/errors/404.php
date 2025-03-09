<?php $view->extends('layouts.app') ?>

<?php $view->section('title') ?>
Page Not Found
<?php $view->endSection() ?>

<?php $view->section('content') ?>
<div class="error-404">
    <div class="error-code">404</div>
    <h1>Page Not Found</h1>
    <p>The page you are looking for might have been removed or is temporarily unavailable.</p>
    <a href="/" class="back-home">Back to Home</a>
</div>
<?php $view->endSection() ?>