<nav class="fixed top-0 z-50 w-full bg-white/80 backdrop-blur-lg border-b border-blue-100">
    <div class="px-3 py-3 lg:px-5 lg:pl-3">
        <div class="flex items-center justify-between">
            <div class="flex items-center justify-start rtl:justify-end">
                <button data-drawer-target="logo-sidebar" data-drawer-toggle="logo-sidebar" aria-controls="logo-sidebar" type="button" class="inline-flex items-center p-2 text-sm text-gray-500 rounded-lg md:hidden hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-gray-200 dark:text-gray-400 dark:hover:bg-gray-700 dark:focus:ring-gray-600">
                    <span class="sr-only">Open sidebar</span>
                    <svg class="w-6 h-6" aria-hidden="true" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                        <path clip-rule="evenodd" fill-rule="evenodd" d="M2 4.75A.75.75 0 012.75 4h14.5a.75.75 0 010 1.5H2.75A.75.75 0 012 4.75zm0 10.5a.75.75 0 01.75-.75h7.5a.75.75 0 010 1.5h-7.5a.75.75 0 01-.75-.75zM2 10a.75.75 0 01.75-.75h14.5a.75.75 0 010 1.5H2.75A.75.75 0 012 10z"></path>
                    </svg>
                </button>
                <a href="<?=BASE_URL?>/dashboard" class="flex ms-2 md:me-24">
                    <img src="<?=BASE_URL?>/images/logo.png" class="h-14" alt="DIPens Logo" />
                </a>
            </div>
            <div class="flex items-center">
                <div class="flex items-center">
                        <!-- Button Toggle DarkMode & Dropdown User-->
                    <div class="flex items-center">

                        <!-- Button User -->
                        <button
                            type="button"
                            aria-expanded="false"
                            data-dropdown-toggle="dropdown-user"
                            data-dropdown-offset-skidding="-68"
                            class="flex items-center rounded-full pe-2 text-sm font-medium text-gray-900 dark:text-white lg:pe-1">
                            <span class="sr-only">Open user menu</span>
                            <img
                                class="h-8 w-8 rounded-full p-0.5 ring-2 ring-cyan-600 backdrop:bg-transparent dark:ring-sky-300"
                                src="https://ui-avatars.com/api/?background=random&name=<?=$_SESSION['user']['full_name']?>"
                                alt="user photo" />
                            <svg
                                class="ms-2.5 h-2.5 w-2.5 text-gray-800"
                                aria-hidden="true"
                                xmlns="http://www.w3.org/2000/svg"
                                fill="none"
                                viewBox="0 0 10 6">
                                <path
                                    stroke="currentColor"
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="m1 1 4 4 4-4" />
                            </svg>
                        </button>
                    </div>

                    <!-- Content Dropdown User -->
                    <div
                        class="z-50 my-4 hidden max-w-44 list-none divide-y divide-gray-100 rounded-lg bg-white text-base shadow dark:divide-gray-600 dark:bg-primary-900"
                        id="dropdown-user">
                        <div class="px-4 py-3" role="none">
                            <p class="text-sm text-gray-900 dark:text-white" role="none"><?=$_SESSION['user']['full_name']?></p>
                            <p
                                class="truncate text-sm font-medium text-gray-900 dark:text-gray-300"
                                title="<?=$_SESSION['user']['email']?>">
                                <?=$_SESSION['user']['email']?>
                            </p>
                        </div>
                        <ul class="py-1" role="none">
                            <!-- Dashboard -->
                            <li>
                                <a
                                    href="<?=BASE_URL?>/dashboard"
                                    class="flex items-center px-4 py-2 text-sm font-semibold text-gray-700 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-900">
                                    <svg
                                        class="mr-4 h-4 w-4 text-gray-900 dark:text-gray-300"
                                        viewBox="0 0 24 24"
                                        fill="none"
                                        xmlns="http://www.w3.org/2000/svg">
                                        <path
                                            d="M2 12.2039C2 9.91549 2 8.77128 2.5192 7.82274C3.0384 6.87421 3.98695 6.28551 5.88403 5.10813L7.88403 3.86687C9.88939 2.62229 10.8921 2 12 2C13.1079 2 14.1106 2.62229 16.116 3.86687L18.116 5.10812C20.0131 6.28551 20.9616 6.87421 21.4808 7.82274C22 8.77128 22 9.91549 22 12.2039V13.725C22 17.6258 22 19.5763 20.8284 20.7881C19.6569 22 17.7712 22 14 22H10C6.22876 22 4.34315 22 3.17157 20.7881C2 19.5763 2 17.6258 2 13.725V12.2039Z"
                                            stroke="currentColor"
                                            stroke-width="2" />
                                        <path d="M15 18H9" stroke="currentColor" stroke-width="2" stroke-linecap="round" />
                                    </svg>

                                    Dashboard
                                </a>
                            </li>

                            <!-- My Profile -->
                            <li>
                                <a
                                    href="<?=BASE_URL?>/profile"
                                    class="flex items-center px-4 py-2 text-sm font-semibold text-gray-700 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-900">
                                    <svg
                                        class="mr-3 h-5 w-5 text-gray-900 dark:text-gray-300"
                                        viewBox="0 0 24 24"
                                        fill="none"
                                        xmlns="http://www.w3.org/2000/svg">
                                        <path
                                            d="M16 7C16 9.20914 14.2091 11 12 11C9.79086 11 8 9.20914 8 7C8 4.79086 9.79086 3 12 3C14.2091 3 16 4.79086 16 7Z"
                                            stroke="currentColor"
                                            stroke-width="1.6"
                                            stroke-linecap="round"
                                            stroke-linejoin="round" />
                                        <path
                                            d="M12 14C8.13401 14 5 17.134 5 21H19C19 17.134 15.866 14 12 14Z"
                                            stroke="currentColor"
                                            stroke-width="1.6"
                                            stroke-linecap="round"
                                            stroke-linejoin="round" />
                                    </svg>

                                    My Profile
                                </a>
                            </li>

                            <!-- Settings -->
                            <li>
                                <a
                                    href="#"
                                    class="flex items-center px-4 py-2 text-sm font-semibold text-gray-700 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-900">
                                    <svg
                                        class="mr-3 h-5 w-5 text-gray-900 dark:text-gray-300"
                                        viewBox="0 0 1024 1024"
                                        xmlns="http://www.w3.org/2000/svg">
                                        <path
                                            fill="currentColor"
                                            d="M600.704 64a32 32 0 0 1 30.464 22.208l35.2 109.376c14.784 7.232 28.928 15.36 42.432 24.512l112.384-24.192a32 32 0 0 1 34.432 15.36L944.32 364.8a32 32 0 0 1-4.032 37.504l-77.12 85.12a357.12 357.12 0 0 1 0 49.024l77.12 85.248a32 32 0 0 1 4.032 37.504l-88.704 153.6a32 32 0 0 1-34.432 15.296L708.8 803.904c-13.44 9.088-27.648 17.28-42.368 24.512l-35.264 109.376A32 32 0 0 1 600.704 960H423.296a32 32 0 0 1-30.464-22.208L357.696 828.48a351.616 351.616 0 0 1-42.56-24.64l-112.32 24.256a32 32 0 0 1-34.432-15.36L79.68 659.2a32 32 0 0 1 4.032-37.504l77.12-85.248a357.12 357.12 0 0 1 0-48.896l-77.12-85.248A32 32 0 0 1 79.68 364.8l88.704-153.6a32 32 0 0 1 34.432-15.296l112.32 24.256c13.568-9.152 27.776-17.408 42.56-24.64l35.2-109.312A32 32 0 0 1 423.232 64H600.64zm-23.424 64H446.72l-36.352 113.088-24.512 11.968a294.113 294.113 0 0 0-34.816 20.096l-22.656 15.36-116.224-25.088-65.28 113.152 79.68 88.192-1.92 27.136a293.12 293.12 0 0 0 0 40.192l1.92 27.136-79.808 88.192 65.344 113.152 116.224-25.024 22.656 15.296a294.113 294.113 0 0 0 34.816 20.096l24.512 11.968L446.72 896h130.688l36.48-113.152 24.448-11.904a288.282 288.282 0 0 0 34.752-20.096l22.592-15.296 116.288 25.024 65.28-113.152-79.744-88.192 1.92-27.136a293.12 293.12 0 0 0 0-40.256l-1.92-27.136 79.808-88.128-65.344-113.152-116.288 24.96-22.592-15.232a287.616 287.616 0 0 0-34.752-20.096l-24.448-11.904L577.344 128zM512 320a192 192 0 1 1 0 384 192 192 0 0 1 0-384zm0 64a128 128 0 1 0 0 256 128 128 0 0 0 0-256z" />
                                    </svg>
                                    Settings
                                </a>
                            </li>
                        </ul>

                        <!-- Sign out -->
                        <div class="py-1">

                            <form action="<?=BASE_URL?>/dashboard/logout" method="post">
                                <button
                                    type="submit"
                                    class="flex items-center w-full cursor-pointer px-4 py-2 text-sm font-semibold text-red-500 hover:bg-gray-100 dark:hover:bg-gray-900">
                                    <svg class="mr-3 h-5 w-5" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path
                                            d="M12 3.25C12.4142 3.25 12.75 3.58579 12.75 4C12.75 4.41421 12.4142 4.75 12 4.75C7.99594 4.75 4.75 7.99594 4.75 12C4.75 16.0041 7.99594 19.25 12 19.25C12.4142 19.25 12.75 19.5858 12.75 20C12.75 20.4142 12.4142 20.75 12 20.75C7.16751 20.75 3.25 16.8325 3.25 12C3.25 7.16751 7.16751 3.25 12 3.25Z"
                                            fill="currentColor" />
                                        <path
                                            d="M16.4697 9.53033C16.1768 9.23744 16.1768 8.76256 16.4697 8.46967C16.7626 8.17678 17.2374 8.17678 17.5303 8.46967L20.5303 11.4697C20.8232 11.7626 20.8232 12.2374 20.5303 12.5303L17.5303 15.5303C17.2374 15.8232 16.7626 15.8232 16.4697 15.5303C16.1768 15.2374 16.1768 14.7626 16.4697 14.4697L18.1893 12.75H10C9.58579 12.75 9.25 12.4142 9.25 12C9.25 11.5858 9.58579 11.25 10 11.25H18.1893L16.4697 9.53033Z"
                                            fill="currentColor" />
                                    </svg>
                                    Sign out
                                </button>
                            </form>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</nav>
