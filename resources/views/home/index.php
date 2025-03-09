<?php $this->extends('layouts.app') ?>

<?php $this->section('title') ?>
Home Page
<?php $this->endSection() ?>

<?php $this->section('header') ?>
<nav>
    <a href="/">Home</a>
    <a href="/about">About</a>
    <a href="/contact">Contact</a>
</nav>
<?php $this->endSection() ?>

<?php $this->section('content') ?>
<div class="container">
    <h1>Welcome to <?= $_ENV['APP_NAME'] ?></h1>
    <p>Start building something amazing!</p>
</div>
<?php $this->endSection() ?>