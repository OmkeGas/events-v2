<?php
use Core\Validator;
?>

<section>
    <!-- Header -->
    <div class="mb-4">
        <div>
            <h1 class="text-2xl font-semibold text-blue-900 mb-1">User Management</h1>
            <p class="text-blue-600">Easily manage users, roles, and access to keep your platform organized.</p>
        </div>
        <div class="mt-4 flex justify-end">
            <button type="button" id="addUserModalButton" data-modal-target="addUserModal"
                    data-modal-toggle="addUserModal"
                    class="text-gray-900 bg-white/70 cursor-pointer border border-gray-300 focus:outline-none hover:bg-gray-100 focus:ring-4 focus:ring-gray-100 font-medium rounded-xl text-sm px-5 py-2.5">
                Create New User
            </button>
        </div>
    </div>

    <!-- Table -->
    <section class=" bg-white/70 backdrop-blur-sm rounded-2xl p-6 border border-blue-100">
        <table id="selection-table">
            <thead>
            <tr>
                <th>
                        <span class="flex items-center">
                            #
                        </span>
                </th>
                <th>
                        <span class="flex items-center">
                            Name
                            <svg class="w-4 h-4 ms-1" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24"
                                 height="24" fill="none" viewBox="0 0 24 24">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                      stroke-width="2" d="m8 15 4 4 4-4m0-6-4-4-4 4" />
                            </svg>
                        </span>
                </th>
                <th>
                        <span class="flex items-center">
                            Username
                            <svg class="w-4 h-4 ms-1" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24"
                                 height="24" fill="none" viewBox="0 0 24 24">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                      stroke-width="2" d="m8 15 4 4 4-4m0-6-4-4-4 4" />
                            </svg>
                        </span>
                </th>
                <th>
                        <span class="flex items-center">
                            Email
                            <svg class="w-4 h-4 ms-1" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24"
                                 height="24" fill="none" viewBox="0 0 24 24">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                      stroke-width="2" d="m8 15 4 4 4-4m0-6-4-4-4 4" />
                            </svg>
                        </span>
                </th>
                <th>
                        <span class="flex items-center">
                            Role
                            <svg class="w-4 h-4 ms-1" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24"
                                 height="24" fill="none" viewBox="0 0 24 24">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                      stroke-width="2" d="m8 15 4 4 4-4m0-6-4-4-4 4" />
                            </svg>
                        </span>
                </th>
                <th>
                        <span class="flex items-center">
                            Joined at
                            <svg class="w-4 h-4 ms-1" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24"
                                 height="24" fill="none" viewBox="0 0 24 24">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                      stroke-width="2" d="m8 15 4 4 4-4m0-6-4-4-4 4" />
                            </svg>
                        </span>
                </th>
                <th>
                        <span class="sr-only">
                            Actions
                        </span>
                </th>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($data['users'] as $index => $user): ?>
                <tr class="hover:bg-blue-50/50">
                    <td class="font-medium text-gray-900 whitespace-nowrap dark:text-white">
                        <?=$index + 1?>
                    </td>
                    <td class="px-6 py-4 flex items-center">
                        <div class="flex-shrink-0 h-8 w-8">
                            <?php if(!empty($user['profile_picture'])): ?>
                                <img class="h-8 w-8 rounded-full object-cover"
                                     src="<?= BASE_URL ?>/images/profiles/<?= $user['profile_picture'] ?>"
                                     alt="<?= $user['full_name'] ?>">
                            <?php else: ?>
                                <img class="h-8 w-8 rounded-full"
                                     src="https://ui-avatars.com/api/?background=random&name=<?= $user['full_name'] ?>"
                                     alt="<?= $user['full_name'] ?>">
                            <?php endif; ?>
                        </div>
                        <div class="ml-4">
                            <div class="text-sm font-medium text-gray-900">
                                <?= $user['full_name'] ?>
                            </div>
                        </div>
                    </td>
                    <td>
                        <span class="line-clamp-2">
                            <?=$user['username']?>
                        </span>
                    </td>
                    <td>
                        <?=$user['email']?>
                    </td>
                    <td>
                        <span
                                class="px-2 py-1 text-xs font-semibold <?= $user['role'] === 'admin' ? 'text-blue-800 bg-blue-100' : 'text-emerald-800 bg-emerald-100' ?> rounded-full">
                            <?= ucfirst($user['role']) ?>
                        </span>
                    </td>
                    <td>
                        <?= date('M d, Y', strtotime($user['created_at'])) ?>
                    </td>
                    <td>
                        <button data-dropdown-offset-skidding="-68" id="dropdownMenuIconHorizontalButton-<?=$index?>"
                                data-dropdown-toggle="dropdownDotsHorizontal-<?=$index?>"
                                class="inline-flex cursor-pointer items-center p-2 text-sm font-medium text-center text-gray-900 rounded-lg hover:bg-white focus:outline-none"
                                type="button">
                            <svg class="w-5 h-5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                                 fill="currentColor" viewBox="0 0 16 3">
                                <path
                                        d="M2 0a1.5 1.5 0 1 1 0 3 1.5 1.5 0 0 1 0-3Zm6.041 0a1.5 1.5 0 1 1 0 3 1.5 1.5 0 0 1 0-3ZM14 0a1.5 1.5 0 1 1 0 3 1.5 1.5 0 0 1 0-3Z" />
                            </svg>
                        </button>
                    </td>
                    <div id="dropdownDotsHorizontal-<?=$index?>"
                         class="z-10 hidden bg-white divide-y divide-gray-100 rounded-lg shadow-sm w-44 dark:bg-gray-700 dark:divide-gray-600">
                        <ul class="py-1" aria-labelledby="dropdownMenuIconHorizontalButton-<?=$index?>">
                            <?php if($user['id'] == $_SESSION['user']['id']): ?>
                                <li>
                                    <a href="<?=BASE_URL?>/dashboard/profile"
                                       class="flex items-center px-4 py-2 text-sm font-semibold text-gray-700 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-900">
                                        My Profile
                                    </a>
                                </li>
                            <?php elseif($user['id'] != $_SESSION['user']['id']): ?>
                            <li>
                                <a href="<?=BASE_URL?>/users/show/<?=$user['id']?>"
                                   class="flex items-center px-4 py-2 text-sm font-semibold text-gray-700 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-900">
                                    <svg class="mr-4 h-4 w-4 text-gray-900 dark:text-gray-300"
                                         xmlns="http://www.w3.org/2000/svg" viewBox="0 0 576 512">
                                        <path
                                                d="M288 80c-65.2 0-118.8 29.6-159.9 67.7C89.6 183.5 63 226 49.4 256c13.6 30 40.2 72.5 78.6 108.3C169.2 402.4 222.8 432 288 432s118.8-29.6 159.9-67.7C486.4 328.5 513 286 526.6 256c-13.6-30-40.2-72.5-78.6-108.3C406.8 109.6 353.2 80 288 80zM95.4 112.6C142.5 68.8 207.2 32 288 32s145.5 36.8 192.6 80.6c46.8 43.5 78.1 95.4 93 131.1c3.3 7.9 3.3 16.7 0 24.6c-14.9 35.7-46.2 87.7-93 131.1C433.5 443.2 368.8 480 288 480s-145.5-36.8-192.6-80.6C48.6 356 17.3 304 2.5 268.3c-3.3-7.9-3.3-16.7 0-24.6C17.3 208 48.6 156 95.4 112.6zM288 336c44.2 0 80-35.8 80-80s-35.8-80-80-80c-.7 0-1.3 0-2 0c1.3 5.1 2 10.5 2 16c0 35.3-28.7 64-64 64c-5.5 0-10.9-.7-16-2c0 .7 0 1.3 0 2c0 44.2 35.8 80 80 80zm0-208a128 128 0 1 1 0 256 128 128 0 1 1 0-256z" />
                                    </svg>
                                    View Details
                                </a>
                            </li>
                            <li>
                                <a href="<?=BASE_URL?>/users/edit/<?=$user['id']?>"
                                   class="flex items-center px-4 py-2 text-sm font-semibold text-gray-700 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-900">
                                    <svg class="mr-4 h-4 w-4 text-gray-900 dark:text-gray-300"
                                         xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512">
                                        <path
                                                d="M441 58.9L453.1 71c9.4 9.4 9.4 24.6 0 33.9L424 134.1 377.9 88 407 58.9c9.4-9.4 24.6-9.4 33.9 0zM209.8 256.2L344 121.9 390.1 168 255.8 302.2c-2.9 2.9-6.5 5-10.4 6.1l-58.5 16.7 16.7-58.5c1.1-3.9 3.2-7.5 6.1-10.4zM373.1 25L175.8 222.2c-8.7 8.7-15 19.4-18.3 31.1l-28.6 100c-2.4 8.4-.1 17.4 6.1 23.6s15.2 8.5 23.6 6.1l100-28.6c11.8-3.4 22.5-9.7 31.1-18.3L487 138.9c28.1-28.1 28.1-73.7 0-101.8L474.9 25C446.8-3.1 401.2-3.1 373.1 25zM88 64C39.4 64 0 103.4 0 152L0 424c0 48.6 39.4 88 88 88l272 0c48.6 0 88-39.4 88-88l0-112c0-13.3-10.7-24-24-24s-24 10.7-24 24l0 112c0 22.1-17.9 40-40 40L88 464c-22.1 0-40-17.9-40-40l0-272c0-22.1 17.9-40 40-40l112 0c13.3 0 24-10.7 24-24s-10.7-24-24-24L88 64z" />
                                    </svg>
                                    Edit User
                                </a>
                            </li>
                        </ul>
                        <div class="py-1">
                            <form action="<?= BASE_URL ?>/users/destroy/<?= $user['id'] ?>" method="post"
                                  onsubmit="return confirm('Are you sure you want to delete this user?');">
                                <button type="submit"
                                        class="flex items-center justify-center w-full cursor-pointer px-4 py-2 text-sm font-semibold text-red-500 hover:bg-gray-100 dark:hover:bg-gray-900">
                                    Delete
                                </button>
                            </form>
                        </div>
                        <?php endif; ?>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </section>

    <!-- Create Modal -->
    <div id="addUserModal" tabindex="-1" aria-hidden="true"
         class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-modal md:h-full">
        <div class="relative p-4 w-full max-w-2xl h-full md:h-auto">
            <!-- Modal content -->
            <div class="relative p-4 bg-white rounded-lg shadow sm:p-5">
                <!-- Modal header -->
                <div class="flex justify-between items-center pb-4 mb-4 rounded-t border-b border-gray-300 sm:mb-5">
                    <h3 class="text-lg font-semibold text-gray-900">
                        Create New User
                    </h3>
                    <button type="button"
                            class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center"
                            data-modal-toggle="addUserModal">
                        <svg aria-hidden="true" class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"
                             xmlns="http://www.w3.org/2000/svg">
                            <path fill-rule="evenodd"
                                  d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
                                  clip-rule="evenodd"></path>
                        </svg>
                        <span class="sr-only">Close modal</span>
                    </button>
                </div>

                <!-- Modal body -->
                <form action="<?= BASE_URL ?>/users/store" method="post" class="space-y-4">
                    <div class="grid gap-6 mb-6 md:grid-cols-2">
                        <div>
                            <label for="full_name"
                                   class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Name</label>
                            <input type="text" id="full_name" name="full_name" value="<?= old('full_name') ?>"
                                   class="bg-gray-50 border <?= Validator::hasError('full_name') ? 'border-red-500' : 'border-gray-300' ?> text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                   placeholder="Name" autocomplete="false" required />
                            <?= error_field('full_name') ?>
                        </div>
                        <div>
                            <label for="username"
                                   class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Username</label>
                            <input type="text" id="username" name="username" value="<?= old('username') ?>"
                                   class="bg-gray-50 border <?= Validator::hasError('username') ? 'border-red-500' : 'border-gray-300' ?> text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                   placeholder="@username" required />
                            <?= error_field('username') ?>
                        </div>
                    </div>
                    <div class="grid gap-6 mb-6 md:grid-cols-2">
                        <div>
                            <label for="email"
                                   class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Email</label>
                            <input type="email" id="email" name="email" value="<?= old('email') ?>"
                                   class="bg-gray-50 border <?= Validator::hasError('email') ? 'border-red-500' : 'border-gray-300' ?> text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                   placeholder="your@mail.com" required />
                            <?= error_field('email') ?>
                        </div>
                        <div>
                            <label for="role"
                                   class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Role</label>
                            <select id="role" name="role"
                                    class="bg-gray-50 border <?= Validator::hasError('role') ? 'border-red-500' : 'border-gray-300' ?> text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5"
                                    required>
                                <option value="">Select role</option>
                                <option value="user" <?=old('role')=="user" ? 'selected' : '' ?>>User</option>
                                <option value="admin" <?=old('role')=="admin" ? 'selected' : '' ?>>Admin</option>
                            </select>
                            <?= error_field('role') ?>
                        </div>
                    </div>
                    <div class="mb-6">
                        <label for="password"
                               class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Password</label>
                        <input type="password" id="password" name="password"
                               class="bg-gray-50 border <?= Validator::hasError('password') ? 'border-red-500' : 'border-gray-300' ?> text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                               placeholder="•••••••••" required />
                        <?= error_field('password') ?>
                    </div>
                    <div class="mb-6">
                        <label for="password_confirmation"
                               class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Confirm
                            password</label>
                        <input type="password" id="password_confirmation" name="password_confirmation"
                               class="bg-gray-50 border <?= Validator::hasError('password_confirmation') ? 'border-red-500' : 'border-gray-300' ?> text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                               placeholder="•••••••••" required />
                        <?= error_field('password_confirmation') ?>
                    </div>
                    <button type="submit"
                            class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center">Create
                        User</button>
                    <button type="button"
                            class="text-gray-500 bg-white hover:bg-gray-100 focus:ring-4 focus:outline-none focus:ring-gray-200 rounded-lg border border-gray-200 text-sm font-medium px-5 py-2.5 hover:text-gray-900 focus:z-10"
                            data-modal-hide="addUserModal">Cancel</button>
                </form>
            </div>
        </div>
    </div>
</section>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        <?php if (isset($_SESSION['modal_open']) && $_SESSION['modal_open']): ?>
        setTimeout(function () {
            const modalToggleButton = document.getElementById('addUserModalButton');
            if (modalToggleButton) {
                modalToggleButton.click();
            }
            <?phpunset($_SESSION['modal_open']);?>
        }, 300);
        <?php endif; ?>
    });
</script>