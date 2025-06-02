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
    <title><?= $data['title'] ?? 'Authentication' ?></title>

    <link rel="stylesheet" href="<?= BASE_URL?>/css/style.css">
</head>
    <body>
        <?= Flasher::render() ?>
        <main>
            <div class="mx-auto flex h-screen flex-col items-center justify-center px-6 py-8 lg:py-0">
                <a href="<?= BASE_URL ?>" class="mb-6 flex items-center text-2xl font-semibold text-gray-900 dark:text-white">
                    <img class="h-18" src="<?= BASE_URL ?>/images/logo.png" alt="logo" />
                </a>
                <div class="w-full rounded-lg bg-white shadow-2xl sm:max-w-[32rem] md:mt-0 xl:p-0 dark:border dark:border-gray-700 dark:bg-gray-800">
                    <?php require_once '../resources/views/pages/' . $view . '.php'; ?>
                </div>
            </div>
        </main>

        <script src="https://cdn.jsdelivr.net/npm/flowbite@3.1.2/dist/flowbite.min.js"></script>
    </body>
</html>
