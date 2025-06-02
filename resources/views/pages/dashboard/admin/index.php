<section>
        <!-- Header -->
        <div class="mb-8">
            <h1 class="text-2xl font-semibold text-blue-900 mb-1">Dashboard Overview</h1>
            <p class="text-blue-600">Welcome back! Here's a summary of your system today.</p>
        </div>

        <!-- Stats Cards -->
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <!-- Total Events -->
            <div class="stat-card bg-white/70 backdrop-blur-sm rounded-2xl p-6 border border-blue-100">
                <a href="<?=BASE_URL?>/dashboard/event" class="flex items-center justify-between mb-4">
                    <div class="icon-container w-12 h-12 rounded-xl blue-gradient flex items-center justify-center">
                        <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                    </div>
                </a>
                <div class="space-y-1">
                    <p class="text-xs font-medium text-blue-500 uppercase tracking-wider">Total Events</p>
                    <p class="text-2xl font-semibold text-blue-900"><?= number_format($data['stats']['totalEvents']) ?></p>
                </div>
            </div>

            <!-- Total Users -->
            <div class="stat-card bg-white/70 backdrop-blur-sm rounded-2xl p-6 border border-blue-100">
                <a href="<?=BASE_URL?>/dashboard" class="flex items-center justify-between mb-4">
                    <div class="icon-container w-12 h-12 rounded-xl blue-gradient flex items-center justify-center">
                        <svg class="w-6 h-6 text-blue-600" stroke="currentColor" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                            <path stroke="currentColor" stroke-linecap="round" stroke-width="2" d="M16 19h4a1 1 0 0 0 1-1v-1a3 3 0 0 0-3-3h-2m-2.236-4a3 3 0 1 0 0-4M3 18v-1a3 3 0 0 1 3-3h4a3 3 0 0 1 3 3v1a1 1 0 0 1-1 1H4a1 1 0 0 1-1-1Zm8-10a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z"/>
                        </svg>

                    </div>
                </a>
                <div class="space-y-1">
                    <p class="text-xs font-medium text-blue-500 uppercase tracking-wider">Total Users</p>
                    <p class="text-2xl font-semibold text-blue-900"><?= number_format($data['stats']['totalUsers']) ?></p>
                </div>
            </div>

            <!-- Total Registrations -->
            <div class="stat-card bg-white/70 backdrop-blur-sm rounded-2xl p-6 border border-blue-100">
                <a href="<?=BASE_URL?>/dashboard" class="flex items-center justify-between mb-4">
                    <div class="icon-container w-12 h-12 rounded-xl blue-gradient flex items-center justify-center">
                        <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                    </div>
                </a>
                <div class="space-y-1">
                    <p class="text-xs font-medium text-blue-500 uppercase tracking-wider">Total Registrations</p>
                    <p class="text-2xl font-semibold text-blue-900"><?= number_format($data['stats']['totalRegistrations']) ?></p>
                </div>
            </div>

            <!-- Upcoming Events -->
            <div class="stat-card bg-white/70 backdrop-blur-sm rounded-2xl p-6 border border-blue-100">
                <a href="<?=BASE_URL?>/dashboard/event" class="flex items-center justify-between mb-4">
                    <div class="icon-container w-12 h-12 rounded-xl blue-gradient flex items-center justify-center">
                        <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                </a>
                <div class="space-y-1">
                    <p class="text-xs font-medium text-blue-500 uppercase tracking-wider">Upcoming Events</p>
                    <p class="text-2xl font-semibold text-blue-900"><?= number_format($data['stats']['upcomingEvents']) ?></p>
                </div>
            </div>
        </div>

        <!-- Content Area -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

            <!-- Quick Actions -->
            <div class="bg-white/70 backdrop-blur-sm rounded-2xl p-6 border border-blue-100">
                <h3 class="text-lg font-semibold text-blue-900 mb-4">Quick Actions</h3>
                <div class="space-y-3">
                    <a href="<?=BASE_URL?>/event/create" class="w-full cursor-pointer flex items-center space-x-3 p-3 rounded-xl bg-blue-50 hover:bg-blue-100 transition-colors group">
                        <div class="w-8 h-8 rounded-lg bg-blue-500 flex items-center justify-center group-hover:scale-105 transition-transform">
                            <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                            </svg>
                        </div>
                        <span class="text-blue-700 font-medium">Create New Event</span>
                    </a>

                    <a href="<?=BASE_URL?>/dashboard/event" class="w-full cursor-pointer flex items-center space-x-3 p-3 rounded-xl bg-blue-50 hover:bg-blue-100 transition-colors group">
                        <div class="w-8 h-8 rounded-lg bg-blue-500 flex items-center justify-center group-hover:scale-105 transition-transform">
                            <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                            </svg>
                        </div>
                        <span class="text-blue-700 font-medium">Manage Events</span>
                    </a>

                    <a href="<?=BASE_URL?>/dashboard" class="w-full cursor-pointer flex items-center space-x-3 p-3 rounded-xl bg-blue-50 hover:bg-blue-100 transition-colors group">
                        <div class="w-8 h-8 rounded-lg bg-blue-500 flex items-center justify-center group-hover:scale-105 transition-transform">
                            <svg  class="w-4 h-4 text-white" stroke="currentColor" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                                <path stroke="currentColor" stroke-linecap="round" stroke-width="2" d="M16 19h4a1 1 0 0 0 1-1v-1a3 3 0 0 0-3-3h-2m-2.236-4a3 3 0 1 0 0-4M3 18v-1a3 3 0 0 1 3-3h4a3 3 0 0 1 3 3v1a1 1 0 0 1-1 1H4a1 1 0 0 1-1-1Zm8-10a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z"/>
                            </svg>

                        </div>
                        <span class="text-blue-700 font-medium">Manage Users</span>
                    </a>
                </div>
            </div>

            <!-- Recent Events -->
            <div class="lg:col-span-2 bg-white/70 backdrop-blur-sm rounded-2xl p-6 border border-blue-100">
                <h3 class="text-lg font-semibold text-blue-900 mb-4">Recent Events</h3>
                <div class="space-y-4">
                    <?php if (empty($data['recentEvents'])): ?>
                        <div class="p-4 text-center bg-gray-50 rounded-lg">
                            <p class="text-gray-500">No events available yet</p>
                        </div>
                    <?php else: ?>
                        <?php foreach ($data['recentEvents'] as $index => $event): ?>
                            <a href="<?=BASE_URL?>/event/show/<?= $event['id'] ?>" class="flex items-center space-x-4 p-3 rounded-xl <?= $index === 0 ? 'bg-blue-50/50' : '' ?> hover:bg-blue-50 transition-colors">
                                <div class="h-10 rounded bg-blue-100 flex items-center justify-center overflow-hidden">
                                    <?php if (!empty($event['thumbnail'])): ?>
                                        <img src="<?= BASE_URL ?>/images/events/<?= $event['thumbnail'] ?>" alt="<?= htmlspecialchars($event['title']) ?>" class="w-full h-full object-cover">
                                    <?php else: ?>
                                        <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                        </svg>
                                    <?php endif; ?>
                                </div>
                                <div class="flex-1">
                                    <p class="text-sm font-medium text-blue-900"><?= htmlspecialchars($event['title']) ?></p>
                                    <p class="text-xs text-blue-600">
                                        <?= date('M d, Y', strtotime($event['start_date'])) ?> •
                                        <?= isset($event['participants_count']) ? $event['participants_count'] : 0 ?> participants
                                    </p>
                                </div>
                            </a>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </section>
