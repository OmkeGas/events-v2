<?php
/**
 * Admin Users Management Page
 */
?>

<div class="p-4 bg-white rounded-lg shadow-md border border-blue-50 mb-4">
    <div class="flex items-center justify-between mb-4">
        <h2 class="text-xl font-semibold text-blue-800">User Management</h2>
        <button type="button" data-modal-target="addUserModal" data-modal-toggle="addUserModal" class="px-3 py-2 text-sm font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-700 focus:ring-4 focus:ring-blue-300">
            <div class="flex items-center">
                <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z" clip-rule="evenodd"></path></svg>
                Add User
            </div>
        </button>
    </div>

    <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
        <table class="w-full text-sm text-left text-gray-700">
            <thead class="text-xs text-blue-900 uppercase bg-blue-50">
                <tr>
                    <th scope="col" class="px-6 py-3">Name</th>
                    <th scope="col" class="px-6 py-3">Username</th>
                    <th scope="col" class="px-6 py-3">Email</th>
                    <th scope="col" class="px-6 py-3">Role</th>
                    <th scope="col" class="px-6 py-3">Created At</th>
                    <th scope="col" class="px-6 py-3 text-right">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if(empty($data['users'])): ?>
                    <tr class="bg-white border-b hover:bg-blue-50">
                        <td colspan="6" class="px-6 py-4 text-center">No users found</td>
                    </tr>
                <?php else: ?>
                    <?php foreach($data['users'] as $user): ?>
                        <tr class="bg-white border-b hover:bg-blue-50">
                            <td class="px-6 py-4 flex items-center">
                                <div class="flex-shrink-0 h-10 w-10">
                                    <?php if(!empty($user['profile_picture'])): ?>
                                        <img class="h-10 w-10 rounded-full object-cover" src="<?= BASE_URL ?>/images/profiles/<?= $user['profile_picture'] ?>" alt="<?= $user['full_name'] ?>">
                                    <?php else: ?>
                                        <img class="h-10 w-10 rounded-full" src="https://ui-avatars.com/api/?background=random&name=<?= $user['full_name'] ?>" alt="<?= $user['full_name'] ?>">
                                    <?php endif; ?>
                                </div>
                                <div class="ml-4">
                                    <div class="text-sm font-medium text-gray-900"><?= $user['full_name'] ?></div>
                                </div>
                            </td>
                            <td class="px-6 py-4"><?= $user['username'] ?></td>
                            <td class="px-6 py-4"><?= $user['email'] ?></td>
                            <td class="px-6 py-4">
                                <span class="px-2 py-1 text-xs font-semibold <?= $user['role'] === 'admin' ? 'text-blue-800 bg-blue-100' : 'text-emerald-800 bg-emerald-100' ?> rounded-full">
                                    <?= ucfirst($user['role']) ?>
                                </span>
                            </td>
                            <td class="px-6 py-4"><?= date('M d, Y', strtotime($user['created_at'])) ?></td>
                            <td class="px-6 py-4 text-right space-x-2">
                                <button data-modal-target="viewUserModal-<?= $user['id'] ?>" data-modal-toggle="viewUserModal-<?= $user['id'] ?>" class="font-medium text-blue-600 hover:underline">View</button>
                                <button data-modal-target="editUserModal-<?= $user['id'] ?>" data-modal-toggle="editUserModal-<?= $user['id'] ?>" class="font-medium text-blue-600 hover:underline">Edit</button>
                                <?php if($user['id'] != $_SESSION['user']['id']): ?>
                                    <button data-modal-target="deleteUserModal-<?= $user['id'] ?>" data-modal-toggle="deleteUserModal-<?= $user['id'] ?>" class="font-medium text-red-600 hover:underline">Delete</button>
                                <?php endif; ?>
                            </td>
                        </tr>

                        <!-- View User Modal -->
                        <div id="viewUserModal-<?= $user['id'] ?>" tabindex="-1" aria-hidden="true" class="fixed top-0 left-0 right-0 z-50 hidden w-full p-4 overflow-x-hidden overflow-y-auto md:inset-0 h-[calc(100%-1rem)] max-h-full">
                            <div class="relative w-full max-w-md max-h-full">
                                <div class="relative bg-white rounded-lg shadow">
                                    <div class="flex items-center justify-between p-4 border-b rounded-t">
                                        <h3 class="text-xl font-semibold text-blue-900">
                                            User Details
                                        </h3>
                                        <button type="button" class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ml-auto inline-flex justify-center items-center" data-modal-hide="viewUserModal-<?= $user['id'] ?>">
                                            <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
                                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6"/>
                                            </svg>
                                            <span class="sr-only">Close modal</span>
                                        </button>
                                    </div>
                                    <div class="p-6 space-y-6">
                                        <div class="flex flex-col items-center">
                                            <?php if(!empty($user['profile_picture'])): ?>
                                                <img class="w-24 h-24 mb-3 rounded-full shadow-lg object-cover" src="<?= BASE_URL ?>/images/profiles/<?= $user['profile_picture'] ?>" alt="<?= $user['full_name'] ?>">
                                            <?php else: ?>
                                                <img class="w-24 h-24 mb-3 rounded-full shadow-lg" src="https://ui-avatars.com/api/?background=random&name=<?= $user['full_name'] ?>" alt="<?= $user['full_name'] ?>">
                                            <?php endif; ?>
                                            <h5 class="mb-1 text-xl font-medium text-gray-900"><?= $user['full_name'] ?></h5>
                                            <span class="text-sm text-gray-600">@<?= $user['username'] ?></span>
                                            <span class="px-2 py-1 mt-2 text-xs font-semibold <?= $user['role'] === 'admin' ? 'text-blue-800 bg-blue-100' : 'text-emerald-800 bg-emerald-100' ?> rounded-full">
                                                <?= ucfirst($user['role']) ?>
                                            </span>
                                        </div>
                                        <div class="space-y-2 mt-4">
                                            <p class="text-base leading-relaxed text-gray-600"><strong>Email:</strong> <?= $user['email'] ?></p>
                                            <p class="text-base leading-relaxed text-gray-600"><strong>Joined:</strong> <?= date('F d, Y', strtotime($user['created_at'])) ?></p>
                                            <p class="text-base leading-relaxed text-gray-600"><strong>Last Updated:</strong> <?= date('F d, Y', strtotime($user['updated_at'])) ?></p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Edit User Modal -->
                        <div id="editUserModal-<?= $user['id'] ?>" tabindex="-1" aria-hidden="true" class="fixed top-0 left-0 right-0 z-50 hidden w-full p-4 overflow-x-hidden overflow-y-auto md:inset-0 h-[calc(100%-1rem)] max-h-full">
                            <div class="relative w-full max-w-md max-h-full">
                                <div class="relative bg-white rounded-lg shadow">
                                    <div class="flex items-center justify-between p-4 border-b rounded-t">
                                        <h3 class="text-xl font-semibold text-blue-900">
                                            Edit User
                                        </h3>
                                        <button type="button" class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ml-auto inline-flex justify-center items-center" data-modal-hide="editUserModal-<?= $user['id'] ?>">
                                            <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
                                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6"/>
                                            </svg>
                                            <span class="sr-only">Close modal</span>
                                        </button>
                                    </div>
                                    <form action="<?= BASE_URL ?>/users/update" method="POST" enctype="multipart/form-data">
                                        <input type="hidden" name="id" value="<?= $user['id'] ?>">
                                        <div class="p-6 space-y-6">
                                            <div class="flex flex-col items-center mb-4">
                                                <?php if(!empty($user['profile_picture'])): ?>
                                                    <img class="w-24 h-24 mb-3 rounded-full shadow-lg object-cover" src="<?= BASE_URL ?>/images/profiles/<?= $user['profile_picture'] ?>" alt="<?= $user['full_name'] ?>">
                                                <?php else: ?>
                                                    <img class="w-24 h-24 mb-3 rounded-full shadow-lg" src="https://ui-avatars.com/api/?background=random&name=<?= $user['full_name'] ?>" alt="<?= $user['full_name'] ?>">
                                                <?php endif; ?>
                                            </div>
                                            <div class="grid gap-4 mb-4">
                                                <div>
                                                    <label for="username-<?= $user['id'] ?>" class="block mb-2 text-sm font-medium text-gray-900">Username</label>
                                                    <input type="text" name="username" id="username-<?= $user['id'] ?>" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" value="<?= $user['username'] ?>" required>
                                                </div>
                                                <div>
                                                    <label for="email-<?= $user['id'] ?>" class="block mb-2 text-sm font-medium text-gray-900">Email</label>
                                                    <input type="email" name="email" id="email-<?= $user['id'] ?>" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" value="<?= $user['email'] ?>" required>
                                                </div>
                                                <div>
                                                    <label for="full_name-<?= $user['id'] ?>" class="block mb-2 text-sm font-medium text-gray-900">Full Name</label>
                                                    <input type="text" name="full_name" id="full_name-<?= $user['id'] ?>" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" value="<?= $user['full_name'] ?>" required>
                                                </div>
                                                <div>
                                                    <label for="password-<?= $user['id'] ?>" class="block mb-2 text-sm font-medium text-gray-900">Password</label>
                                                    <input type="password" name="password" id="password-<?= $user['id'] ?>" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" placeholder="Leave blank to keep current password">
                                                    <p class="mt-1 text-sm text-gray-500">Leave blank to keep current password</p>
                                                </div>
                                                <div>
                                                    <label for="role-<?= $user['id'] ?>" class="block mb-2 text-sm font-medium text-gray-900">Role</label>
                                                    <select name="role" id="role-<?= $user['id'] ?>" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">
                                                        <option value="user" <?= $user['role'] === 'user' ? 'selected' : '' ?>>User</option>
                                                        <option value="admin" <?= $user['role'] === 'admin' ? 'selected' : '' ?>>Admin</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="flex items-center p-4 space-x-2 border-t border-gray-200 rounded-b">
                                            <button type="submit" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center">Save Changes</button>
                                            <button type="button" class="text-gray-500 bg-white hover:bg-gray-100 focus:ring-4 focus:outline-none focus:ring-gray-200 rounded-lg border border-gray-200 text-sm font-medium px-5 py-2.5 hover:text-gray-900 focus:z-10" data-modal-hide="editUserModal-<?= $user['id'] ?>">Cancel</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>

                        <!-- Delete User Modal -->
                        <?php if($user['id'] != $_SESSION['user']['id']): ?>
                            <div id="deleteUserModal-<?= $user['id'] ?>" tabindex="-1" aria-hidden="true" class="fixed top-0 left-0 right-0 z-50 hidden w-full p-4 overflow-x-hidden overflow-y-auto md:inset-0 h-[calc(100%-1rem)] max-h-full">
                                <div class="relative w-full max-w-md max-h-full">
                                    <div class="relative bg-white rounded-lg shadow">
                                        <div class="flex items-center justify-between p-4 border-b rounded-t">
                                            <h3 class="text-xl font-semibold text-red-700">
                                                Delete User
                                            </h3>
                                            <button type="button" class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ml-auto inline-flex justify-center items-center" data-modal-hide="deleteUserModal-<?= $user['id'] ?>">
                                                <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
                                                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6"/>
                                                </svg>
                                                <span class="sr-only">Close modal</span>
                                            </button>
                                        </div>
                                        <div class="p-6 text-center">
                                            <svg class="mx-auto mb-4 text-red-500 w-12 h-12" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 20">
                                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 11V6m0 8h.01M19 10a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/>
                                            </svg>
                                            <h3 class="mb-5 text-lg font-normal text-gray-700">Are you sure you want to delete <span class="font-semibold"><?= $user['full_name'] ?></span>?</h3>
                                            <p class="mb-5 text-sm text-gray-600">This action cannot be undone and will delete all associated data.</p>
                                            <form action="<?= BASE_URL ?>/dashboard/users/delete/<?= $user['id'] ?>" method="POST" class="inline">
                                                <button type="submit" class="text-white bg-red-600 hover:bg-red-800 focus:ring-4 focus:outline-none focus:ring-red-300 font-medium rounded-lg text-sm inline-flex items-center px-5 py-2.5 text-center mr-2">
                                                    Yes, I'm sure
                                                </button>
                                                <button type="button" class="text-gray-500 bg-white hover:bg-gray-100 focus:ring-4 focus:outline-none focus:ring-gray-200 rounded-lg border border-gray-200 text-sm font-medium px-5 py-2.5 hover:text-gray-900 focus:z-10" data-modal-hide="deleteUserModal-<?= $user['id'] ?>">No, cancel</button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php endif; ?>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Add User Modal -->
<div id="addUserModal" tabindex="-1" aria-hidden="true" class="fixed top-0 left-0 right-0 z-50 hidden w-full p-4 overflow-x-hidden overflow-y-auto md:inset-0 h-[calc(100%-1rem)] max-h-full">
    <div class="relative w-full max-w-md max-h-full">
        <div class="relative bg-white rounded-lg shadow">
            <div class="flex items-center justify-between p-4 border-b rounded-t">
                <h3 class="text-xl font-semibold text-blue-900">
                    Add New User
                </h3>
                <button type="button" class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ml-auto inline-flex justify-center items-center" data-modal-hide="addUserModal">
                    <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6"/>
                    </svg>
                    <span class="sr-only">Close modal</span>
                </button>
            </div>
            <form action="<?= BASE_URL ?>/register/store" method="POST" enctype="multipart/form-data">
                <div class="p-6 space-y-6">
                    <div class="grid gap-4 mb-4">
                        <div>
                            <label for="username-new" class="block mb-2 text-sm font-medium text-gray-900">Username</label>
                            <input type="text" name="username" id="username-new" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" required>
                        </div>
                        <div>
                            <label for="email-new" class="block mb-2 text-sm font-medium text-gray-900">Email</label>
                            <input type="email" name="email" id="email-new" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" required>
                        </div>
                        <div>
                            <label for="full_name-new" class="block mb-2 text-sm font-medium text-gray-900">Full Name</label>
                            <input type="text" name="full_name" id="full_name-new" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" required>
                        </div>
                        <div>
                            <label for="password-new" class="block mb-2 text-sm font-medium text-gray-900">Password</label>
                            <input type="password" name="password" id="password-new" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" required>
                        </div>
                        <div>
                            <label for="password_confirmation-new" class="block mb-2 text-sm font-medium text-gray-900">Confirm Password</label>
                            <input type="password" name="password_confirmation" id="password_confirmation-new" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" required>
                        </div>
                        <div>
                            <label for="role-new" class="block mb-2 text-sm font-medium text-gray-900">Role</label>
                            <select name="role" id="role-new" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">
                                <option value="user" selected>User</option>
                                <option value="admin">Admin</option>
                            </select>
                        </div>
                        <div>
                            <label class="block mb-2 text-sm font-medium text-gray-900" for="profile_picture-new">Profile Picture</label>
                            <input type="file" name="profile_picture" id="profile_picture-new" class="block w-full text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 focus:outline-none">
                            <p class="mt-1 text-sm text-gray-500">SVG, PNG, JPG or GIF (MAX. 800x800px).</p>
                        </div>
                    </div>
                </div>
                <div class="flex items-center p-4 space-x-2 border-t border-gray-200 rounded-b">
                    <button type="submit" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center">Create User</button>
                    <button type="button" class="text-gray-500 bg-white hover:bg-gray-100 focus:ring-4 focus:outline-none focus:ring-gray-200 rounded-lg border border-gray-200 text-sm font-medium px-5 py-2.5 hover:text-gray-900 focus:z-10" data-modal-hide="addUserModal">Cancel</button>
                </div>
            </form>
        </div>
    </div>
</div>
