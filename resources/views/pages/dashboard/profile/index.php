<?php
/**
 * User Profile Page
 */
?>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <!-- Profile Summary Card -->
    <div class="lg:col-span-1">
        <div class="bg-white rounded-lg shadow-md border border-blue-50 p-6">
            <div class="flex flex-col items-center">
                <?php if(!empty($data['userProfile']['profile_picture'])): ?>
                    <img class="w-32 h-32 mb-4 rounded-full shadow-lg object-cover" src="<?= BASE_URL ?>/images/profiles/<?= $data['userProfile']['profile_picture'] ?>" alt="<?= $data['userProfile']['full_name'] ?>">
                <?php else: ?>
                    <img class="w-32 h-32 mb-4 rounded-full shadow-lg" src="https://ui-avatars.com/api/?background=random&name=<?= $data['userProfile']['full_name'] ?>" alt="<?= $data['userProfile']['full_name'] ?>">
                <?php endif; ?>
                <h5 class="mb-1 text-xl font-medium text-gray-900"><?= $data['userProfile']['full_name'] ?></h5>
                <span class="text-sm text-gray-500">@<?= $data['userProfile']['username'] ?></span>
                <span class="px-2 py-1 mt-2 text-xs font-semibold <?= $data['userProfile']['role'] === 'admin' ? 'text-blue-800 bg-blue-100' : 'text-emerald-800 bg-emerald-100' ?> rounded-full">
                    <?= ucfirst($data['userProfile']['role']) ?>
                </span>
            </div>
            <div class="mt-6 space-y-3">
                <div class="flex items-center text-gray-700">
                    <svg class="w-5 h-5 mr-2 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                    </svg>
                    <span><?= $data['userProfile']['email'] ?></span>
                </div>
                <div class="flex items-center text-gray-700">
                    <svg class="w-5 h-5 mr-2 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                    </svg>
                    <span>Joined <?= date('F d, Y', strtotime($data['userProfile']['created_at'])) ?></span>
                </div>
            </div>
        </div>
    </div>

    <!-- Profile Edit Form -->
    <div class="lg:col-span-2">
        <div class="bg-white rounded-lg shadow-md border border-blue-50 p-6">
            <h2 class="text-xl font-semibold text-blue-800 mb-6">Edit Profile</h2>

            <form action="<?= BASE_URL ?>/profile/update" method="POST" enctype="multipart/form-data">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <div>
                        <label for="username" class="block mb-2 text-sm font-medium text-gray-900">Username</label>
                        <input type="text" name="username" id="username" value="<?= $data['userProfile']['username'] ?>" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" required>
                        <?php if(isset($_SESSION['validation_errors']['username'])): ?>
                            <p class="mt-1 text-sm text-red-600"><?= $_SESSION['validation_errors']['username'][0] ?></p>
                        <?php endif; ?>
                    </div>
                    <div>
                        <label for="email" class="block mb-2 text-sm font-medium text-gray-900">Email Address</label>
                        <input type="email" name="email" id="email" value="<?= $data['userProfile']['email'] ?>" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" required>
                        <?php if(isset($_SESSION['validation_errors']['email'])): ?>
                            <p class="mt-1 text-sm text-red-600"><?= $_SESSION['validation_errors']['email'][0] ?></p>
                        <?php endif; ?>
                    </div>
                    <div class="md:col-span-2">
                        <label for="full_name" class="block mb-2 text-sm font-medium text-gray-900">Full Name</label>
                        <input type="text" name="full_name" id="full_name" value="<?= $data['userProfile']['full_name'] ?>" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" required>
                        <?php if(isset($_SESSION['validation_errors']['full_name'])): ?>
                            <p class="mt-1 text-sm text-red-600"><?= $_SESSION['validation_errors']['full_name'][0] ?></p>
                        <?php endif; ?>
                    </div>
                </div>

                <div class="mb-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Change Password</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="password" class="block mb-2 text-sm font-medium text-gray-900">New Password</label>
                            <input type="password" name="password" id="password" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">
                            <p class="mt-1 text-sm text-gray-500">Leave blank to keep current password</p>
                        </div>
                        <div>
                            <label for="password_confirmation" class="block mb-2 text-sm font-medium text-gray-900">Confirm New Password</label>
                            <input type="password" name="password_confirmation" id="password_confirmation" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">
                            <?php if(isset($_SESSION['validation_errors']['password_confirmation'])): ?>
                                <p class="mt-1 text-sm text-red-600"><?= $_SESSION['validation_errors']['password_confirmation'][0] ?></p>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
                <div class="flex justify-end">
                    <button type="submit" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 focus:outline-none">
                        Save Changes
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

