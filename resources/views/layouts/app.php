<!DOCTYPE html>
<html lang="<?= $_ENV['APP_LOCALE'] ?? 'en' ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $this->getSection('title') ?> - <?= $_ENV['APP_NAME'] ?></title>
    
    <!-- CSS -->
    <link rel="stylesheet" href="/assets/css/app.css">
    <?= $this->getSection('styles') ?>
</head>
<body>
    <!-- Header -->
    <header>
        <?= $this->getSection('header') ?>
    </header>

    <!-- Main Content -->
    <main>
        <?= $content ?? '' ?>
    </main>

    <!-- Footer -->
    <footer>
        <?= $this->getSection('footer') ?>
    </footer>

    <!-- Scripts -->
    <script src="/assets/js/app.js"></script>
    <?= $this->getSection('scripts') ?>
</body>
</html>