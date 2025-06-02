<?php
use Core\Validator;
?>

<div class="space-y-4 p-6 sm:p-8 md:space-y-6">
    <h1 class="text-xl leading-tight font-bold tracking-tight text-gray-900 md:text-2xl dark:text-white">Sign in to your account</h1>
    <form class="space-y-4 md:space-y-6" action="<?= BASE_URL ?>/login/store" method="post">
        <div>
            <label for="username" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Username</label>
            <input type="text" id="username" name="username"
                   value="<?= old('username') ?>"
                   class="bg-gray-50 border <?= Validator::hasError('username') ? 'border-red-500' : 'border-gray-300' ?> text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                   placeholder="@username" required />
            <?= error_field('username') ?>
        </div>
        <div>
            <label for="password" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Password</label>
            <input type="password" id="password" name="password"
                   class="bg-gray-50 border <?= Validator::hasError('password') ? 'border-red-500' : 'border-gray-300' ?> text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                   placeholder="•••••••••" required />
            <?= error_field('password') ?>
        </div>
        <div class="flex items-center justify-between">
            <div class="flex items-start">
                <div class="flex h-5 items-center">
                    <input
                            id="remember"
                            aria-describedby="remember"
                            type="checkbox"
                            class="h-4 w-4 rounded border border-gray-300 bg-gray-50 focus:ring-3 focus:ring-blue-300 dark:border-gray-600 dark:bg-gray-700 dark:ring-offset-gray-800 dark:focus:ring-blue-600"
                    />
                </div>
                <div class="ml-3 text-sm">
                    <label for="remember" class="text-gray-500 dark:text-gray-300">Remember me</label>
                </div>
            </div>
            <a href="#" class="text-sm font-medium text-blue-600 hover:underline dark:text-blue-500">Forgot password?</a>
        </div>
        <button
                type="submit"
                class="w-full cursor-pointer rounded-lg bg-blue-600 px-5 py-2.5 text-center text-sm font-medium text-white hover:bg-blue-700 focus:ring-4 focus:ring-blue-300 focus:outline-none dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
            Sign in
        </button>
        <p class="text-sm font-light text-gray-500 dark:text-gray-400">
            Don’t have an account yet? <a href="<?= BASE_URL ?>/register" class="font-medium text-blue-600 hover:underline dark:text-blue-500">Sign up</a>
        </p>
    </form>
</div>
