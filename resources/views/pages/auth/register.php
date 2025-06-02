<?php
use Core\Validator;
?>

<div class="space-y-4 p-6 sm:p-8 md:space-y-6">
    <h1 class="text-xl leading-tight font-bold tracking-tight text-gray-900 md:text-2xl dark:text-white">Create an account</h1>

    <?php if (isset($_SESSION['error'])): ?>
        <div class="mb-4 rounded-lg bg-red-50 p-4 text-sm text-red-800">
            <?= $_SESSION['error']; ?>
            <?php unset($_SESSION['error']); ?>
        </div>
    <?php endif; ?>

    <form class="space-y-4 md:space-y-6" action="<?= BASE_URL ?>/register/store" method="post" enctype="multipart/form-data">
        <div class="mb-6">
            <label for="full_name" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Full Name</label>
            <input type="text" id="full_name" name="full_name"
                   value="<?= old('full_name') ?>"
                   class="bg-gray-50 border <?= Validator::hasError('full_name') ? 'border-red-500' : 'border-gray-300' ?> text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                   placeholder="Your name" required />
            <?= error_field('full_name') ?>
        </div>
        <div class="grid gap-6 mb-6 md:grid-cols-2">
            <div>
                <label for="username" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Username</label>
                <input type="text" id="username" name="username"
                       value="<?= old('username') ?>"
                       class="bg-gray-50 border <?= Validator::hasError('username') ? 'border-red-500' : 'border-gray-300' ?> text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                       placeholder="@username" required />
                <?= error_field('username') ?>
            </div>
            <div>
                <label for="email" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Email</label>
                <input type="email" id="email" name="email"
                       value="<?= old('email') ?>"
                       class="bg-gray-50 border <?= Validator::hasError('email') ? 'border-red-500' : 'border-gray-300' ?> text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                       placeholder="your@mail.com" required />
                <?= error_field('email') ?>
            </div>
        </div>
        <div class="mb-6">
            <label for="password" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Password</label>
            <input type="password" id="password" name="password"
                   class="bg-gray-50 border <?= Validator::hasError('password') ? 'border-red-500' : 'border-gray-300' ?> text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                   placeholder="•••••••••" required />
            <?= error_field('password') ?>
        </div>
        <div class="mb-6">
            <label for="password_confirmation" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Confirm password</label>
            <input type="password" id="password_confirmation" name="password_confirmation"
                   class="bg-gray-50 border <?= Validator::hasError('password_confirmation') ? 'border-red-500' : 'border-gray-300' ?> text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                   placeholder="•••••••••" required />
            <?= error_field('password_confirmation') ?>
        </div>

        <div class="flex items-start">
            <div class="flex h-5 items-center">
                <input
                    id="terms"
                    aria-describedby="terms"
                    type="checkbox"
                    class="h-4 w-4 rounded border border-gray-300 bg-gray-50 focus:ring-3 focus:ring-blue-300 dark:border-gray-600 dark:bg-gray-700 dark:ring-offset-gray-800 dark:focus:ring-blue-600"
                ="" />
            </div>
            <div class="ml-3 text-sm">
                <label for="terms" class="font-light text-gray-500 dark:text-gray-300">I accept the <a class="font-medium text-blue-600 hover:underline dark:text-blue-500" href="#">Terms and Conditions</a></label>
            </div>
        </div>
        <button
            type="submit"
            class="w-full rounded-lg bg-blue-600 px-5 py-2.5 text-center text-sm font-medium text-white hover:bg-blue-700 focus:ring-4 focus:ring-blue-300 focus:outline-none dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
            Create an account
        </button>
        <p class="text-sm font-light text-gray-500 dark:text-gray-400">
            Already have an account?
            <a href="<?= BASE_URL ?>/login" class="font-medium text-blue-600 hover:underline dark:text-blue-500">Login here</a>
        </p>
    </form>
</div>
