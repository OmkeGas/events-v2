<?php
use Core\Flasher;
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title><?= $data['title'] ?? 'Event App' ?></title>

    <link rel="stylesheet" href="<?= BASE_URL?>/css/style.css">
    <link rel="stylesheet" href="<?= BASE_URL?>/css/wysiwyg.css">
</head>
    <body>
        <?= Flasher::render() ?>
        <?php $this->component('app/navbar'); ?>
        <main><?php require_once '../resources/views/pages/' . $view . '.php'; ?></main>
        <script src="https://cdn.jsdelivr.net/npm/flowbite@3.1.2/dist/flowbite.min.js"></script>
    </body>
</html>