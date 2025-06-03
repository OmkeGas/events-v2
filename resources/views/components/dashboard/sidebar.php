<aside id="logo-sidebar" class="fixed top-0 left-0 z-40 w-64 h-screen pt-26 transition-transform -translate-x-full bg-white/80 backdrop-blur-lg border-r border-blue-100 md:translate-x-0" aria-label="Sidebar">
    <div class="h-full px-3 pb-4 overflow-y-auto">
        <ul class="space-y-2 font-medium">
            <li>
                <a href="<?= BASE_URL ?>/dashboard"
                   class="flex items-center p-3 rounded-xl group transition-colors <?= $this->isActive('dashboard') ? 'bg-blue-50 text-blue-900' : 'text-blue-900 hover:bg-blue-50' ?>">
                    <svg class="w-5 h-5 transition duration-75 <?= $this->isActive('dashboard') ? 'text-blue-600' : 'text-blue-500 group-hover:text-blue-600' ?>"
                         aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 22 21">
                        <path d="M16.975 11H10V4.025a1 1 0 0 0-1.066-.998 8.5 8.5 0 1 0 9.039 9.039.999.999 0 0 0-1-1.066h.002Z" />
                        <path d="M12.5 0c-.157 0-.311.01-.565.027A1 1 0 0 0 11 1.02V10h8.975a1 1 0 0 0 1-.935c.013-.188.028-.374.028-.565A8.51 8.51 0 0 0 12.5 0Z" />
                    </svg>
                    <span class="ms-3">Dashboard</span>
                </a>
            </li>
            <li>
                <a href="<?= BASE_URL ?>/dashboard/event"
                       class="flex items-center p-3 rounded-xl group transition-colors <?= $this->isActive('dashboard/event') || $this->isActiveSub('event') ? 'bg-blue-50 text-blue-900' : 'text-blue-700 hover:bg-blue-50' ?>">
                    <svg
                            class="shrink-0 w-5 h-5 transition duration-75 <?= $this->isActive('dashboard/event') || $this->isActiveSub('event') ? 'text-blue-600' : 'text-blue-500 group-hover:text-blue-600' ?>"
                            fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                    <span class="flex-1 ms-3 whitespace-nowrap">Events</span>
                </a>
            </li>
            <?php if($_SESSION['user']['role'] === "admin"): ?>
            <li>
                <a href="<?= BASE_URL ?>/users"
                   class="flex items-center p-3 rounded-xl group transition-colors <?= $this->isActive('/dashboard/users')  ? 'bg-blue-50 text-blue-900' : 'text-blue-700 hover:bg-blue-50' ?>">
                    <svg
                            class="shrink-0 w-5 h-5 transition duration-75 <?= $this->isActive('/dashboard/users') ? 'text-blue-600' : 'text-blue-500 group-hover:text-blue-600' ?>"
                            fill="none" stroke="currentColor" xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                            viewBox="0 0 24 24">
                        <path stroke="currentColor" stroke-linecap="round" stroke-width="2"
                              d="M16 19h4a1 1 0 0 0 1-1v-1a3 3 0 0 0-3-3h-2m-2.236-4a3 3 0 1 0 0-4M3 18v-1a3 3 0 0 1 3-3h4a3 3 0 0 1 3 3v1a1 1 0 0 1-1 1H4a1 1 0 0 1-1-1Zm8-10a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                    </svg>
                    <span class="flex-1 ms-3 whitespace-nowrap">Users</span>
                </a>
            </li>
            <?php endif; ?>
        </ul>
    </div>
</aside>