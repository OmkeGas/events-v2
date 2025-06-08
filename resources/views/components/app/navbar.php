<header>
    <nav class="border-gray-200 bg-[#7DBAFF] dark:bg-gray-900">
        <div class="mx-auto flex max-w-screen-xl flex-wrap items-center justify-between p-3">
            <a href="<?= BASE_URL ?>" class="flex items-center space-x-3 rtl:space-x-reverse">
                <img src="<?= BASE_URL ?>/images/logo.png" class="h-14" alt="DIPENS Logo" />
            </a>
            <div class="flex">
                <button
                    type="button"
                    data-collapse-toggle="navbar-search"
                    aria-controls="navbar-search"
                    aria-expanded="false"
                    class="me-1 rounded-lg p-2.5 text-sm text-gray-500 hover:bg-gray-100 focus:ring-4 focus:ring-gray-200 focus:outline-none md:hidden dark:text-gray-400 dark:hover:bg-gray-700 dark:focus:ring-gray-700">
                    <svg class="h-5 w-5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 20">
                        <path
                            stroke="currentColor"
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="m19 19-4-4m0-7A7 7 0 1 1 1 8a7 7 0 0 1 14 0Z" />
                    </svg>
                    <span class="sr-only">Search</span>
                </button>
                <div class="relative hidden w-[40vw] md:block">
                    <form action="<?= BASE_URL ?>/event/search" method="GET">
                        <div class="pointer-events-none absolute inset-y-0 start-0 flex items-center ps-3">
                            <svg
                                class="h-4 w-4 text-gray-500 dark:text-gray-400"
                                aria-hidden="true"
                                xmlns="http://www.w3.org/2000/svg"
                                fill="none"
                                viewBox="0 0 20 20">
                                <path
                                    stroke="currentColor"
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="m19 19-4-4m0-7A7 7 0 1 1 1 8a7 7 0 0 1 14 0Z" />
                            </svg>
                            <span class="sr-only">Search icon</span>
                        </div>
                        <input
                            type="text"
                            id="search-navbar"
                            name="keyword"
                            value="<?= isset($_SESSION['search_keyword']) ? htmlspecialchars($_SESSION['search_keyword']) : '' ?>"
                            class="block w-full rounded-full bg-gray-50 p-3 ps-10 text-sm text-gray-900 focus:outline-0"
                            placeholder="Search events..." />
                    </form>
                </div>
                <button
                    data-collapse-toggle="navbar-search"
                    type="button"
                    class="inline-flex h-10 w-10 items-center justify-center rounded-lg p-2 text-sm text-gray-500 hover:bg-gray-100 focus:ring-2 focus:ring-gray-200 focus:outline-none md:hidden dark:text-gray-400 dark:hover:bg-gray-700 dark:focus:ring-gray-600"
                    aria-controls="navbar-search"
                    aria-expanded="false">
                    <span class="sr-only">Open main menu</span>
                    <svg class="h-5 w-5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 17 14">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M1 1h15M1 7h15M1 13h15" />
                    </svg>
                </button>
            </div>
            <div class="hidden w-full items-center justify-between gap-8 md:flex md:w-auto" id="navbar-search">
                <div class="relative mt-3 md:hidden">
                    <form action="<?= BASE_URL ?>/event/search" method="GET">
                        <div class="pointer-events-none absolute inset-y-0 start-0 flex items-center ps-3">
                            <svg
                                class="h-4 w-4 text-gray-500 dark:text-gray-400"
                                aria-hidden="true"
                                xmlns="http://www.w3.org/2000/svg"
                                fill="none"
                                viewBox="0 0 20 20">
                                <path
                                    stroke="currentColor"
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="m19 19-4-4m0-7A7 7 0 1 1 1 8a7 7 0 0 1 14 0Z" />
                            </svg>
                        </div>
                        <input
                            type="text"
                            id="search-navbar-mobile"
                            name="keyword"
                            value="<?= isset($_SESSION['search_keyword']) ? htmlspecialchars($_SESSION['search_keyword']) : '' ?>"
                            class="block w-full rounded-lg border border-gray-300 bg-gray-50 p-2 ps-10 text-sm text-gray-900 focus:border-blue-500 focus:ring-blue-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white dark:placeholder-gray-400 dark:focus:border-blue-500 dark:focus:ring-blue-500"
                            placeholder="Search events..." />
                    </form>
                </div>
                <ul
                    class="mt-4 flex flex-col space-y-2 rounded-lg bg-blue-200 p-4 font-medium md:mt-0 md:flex-row md:items-center md:space-y-0 md:space-x-8 md:border-0 md:bg-transparent md:p-0 rtl:space-x-reverse dark:border-gray-700 dark:bg-gray-800 md:dark:bg-gray-900">
                    <li>
                        <a
                            href="<?= BASE_URL ?>"
                            class="block rounded-sm <?= $this->isActive('') ? 'bg-blue-500 text-white md:bg-transparent md:text-blue-600' : 'text-white hover:bg-blue-500 md:hover:bg-transparent md:hover:text-blue-700' ?> px-3 py-2 md:p-0"
                            <?= $this->isActive('') ? 'aria-current="page"' : '' ?>
                        >
                            Home
                        </a>
                    </li>
                    <li>
                        <a
                            href="<?= BASE_URL ?>/event"
                            class="block rounded-sm <?= $this->isActive('event') ? 'bg-blue-500 text-white md:bg-transparent md:text-blue-600' : 'text-white hover:bg-blue-500 md:hover:bg-transparent md:hover:text-blue-700' ?> px-3 py-2 md:p-0"
                            <?= $this->isActive('event') ? 'aria-current="page"' : '' ?>
                        >
                            Event
                        </a>
                    </li>
                    <li>
                        <?php if (isset($_SESSION['user'])): ?>
                            <a
                                href="<?= BASE_URL ?>/dashboard"
                                class="block rounded-sm <?= $this->isActive('dashboard') ? 'bg-blue-500 text-white md:bg-transparent md:text-blue-600' : 'text-white hover:bg-blue-500 md:hover:bg-transparent md:hover:text-blue-700' ?> px-3 py-2 md:p-0"
                                <?= $this->isActive('dashboard') ? 'aria-current="page"' : '' ?>
                            >
                                Dashboard
                            </a>
                        <?php else: ?>
                        <a
                            href="<?= BASE_URL ?>/login"
                            class="w-full cursor-pointer rounded-sm border border-white bg-transparent px-4 py-2 text-center text-sm font-medium text-white hover:bg-gray-100 hover:text-black focus:ring-4 focus:ring-blue-300 focus:outline-none dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
                            Log in
                        </a>
                        <?php endif; ?>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
</header>

