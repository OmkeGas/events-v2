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

    <script src="https://cdn.tiny.cloud/1/covo69f8d480djehl1lv5fwitvu0cls4ljuq13zmp22fv1eq/tinymce/7/tinymce.min.js" referrerpolicy="origin"></script>
    <script src="https://cdn.jsdelivr.net/npm/simple-datatables@9.0.3"></script>
</head>
<body>
    <?= Flasher::render() ?>
    <?php $this->component('dashboard/navbar'); ?>
    <?php $this->component('dashboard/sidebar'); ?>

    <main class="p-4 mt-20 md:ml-64 font-sans bg-gradient-to-br from-blue-50 to-blue-100 min-h-screen">
        <?php require_once '../resources/views/pages/' . $view . '.php'; ?>
    </main>

    <script src="https://cdn.jsdelivr.net/npm/flowbite@3.1.2/dist/flowbite.min.js"></script>
    <script>
        document.querySelectorAll('.tox-promotion-button').forEach(el => el.remove());
    </script>
</body>
</html>
