<section class="mb-8">
    <!-- Header -->
    <div class="mb-6">
        <h1 class="text-2xl font-semibold text-blue-900 mb-1">Welcome, <?= htmlspecialchars($data['user']['full_name']) ?>!</h1>
        <p class="text-blue-600">Here's your personal event dashboard</p>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <!-- Total Registered Events -->
        <div class="bg-white/70 backdrop-blur-sm rounded-2xl p-6 border border-blue-100 shadow-sm hover:shadow-md transition-shadow duration-300">
            <div class="flex items-center justify-between mb-4">
                <div class="w-12 h-12 rounded-xl blue-gradient flex items-center justify-center">
                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
            </div>
            <div class="space-y-1">
                <p class="text-xs font-medium text-blue-500 uppercase tracking-wider">Total Events</p>
                <p class="text-2xl font-semibold text-blue-900"><?= number_format($data['stats']['totalRegistered']) ?></p>
                <p class="text-xs text-gray-500">Events you've registered for</p>
            </div>
        </div>

        <!-- Upcoming Events -->
        <div class="bg-white/70 backdrop-blur-sm rounded-2xl p-6 border border-blue-100 shadow-sm hover:shadow-md transition-shadow duration-300">
            <div class="flex items-center justify-between mb-4">
                <div class="w-12 h-12 rounded-xl blue-gradient flex items-center justify-center">
                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
            </div>
            <div class="space-y-1">
                <p class="text-xs font-medium text-blue-500 uppercase tracking-wider">Upcoming</p>
                <p class="text-2xl font-semibold text-blue-900"><?= number_format($data['stats']['upcomingEvents']) ?></p>
                <p class="text-xs text-gray-500">Events you'll be attending</p>
            </div>
        </div>

        <!-- Completed Events -->
        <div class="bg-white/70 backdrop-blur-sm rounded-2xl p-6 border border-blue-100 shadow-sm hover:shadow-md transition-shadow duration-300">
            <div class="flex items-center justify-between mb-4">
                <div class="w-12 h-12 rounded-xl blue-gradient flex items-center justify-center">
                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                </div>
            </div>
            <div class="space-y-1">
                <p class="text-xs font-medium text-blue-500 uppercase tracking-wider">Completed</p>
                <p class="text-2xl font-semibold text-blue-900"><?= number_format($data['stats']['completedEvents']) ?></p>
                <p class="text-xs text-gray-500">Events you've attended</p>
            </div>
        </div>
    </div>

    <!-- Content Area -->
    <div class="grid grid-cols-1 lg:grid-cols-5 gap-6">
        <!-- Next Event Card -->
        <div class="bg-white/70 w-full col-span-2 backdrop-blur-sm rounded-2xl border border-blue-100 shadow-sm overflow-hidden">
            <div class="p-6 pb-4">
                <h3 class="text-lg font-semibold text-blue-900 mb-4">Your Next Event</h3>

                <?php if (!$data['nextEvent']): ?>
                    <div class="flex flex-col items-center h-fit justify-center py-6 text-center">
                        <svg class="w-16 h-16 text-gray-300 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                        <p class="text-gray-500 mb-2">No upcoming events</p>
                        <a href="<?= BASE_URL ?>/event" class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                            Browse available events
                        </a>
                    </div>
                <?php else: ?>
                    <div class="relative space-y-4">
                        <!-- Event Image -->
                        <div class="h-full bg-gray-100 rounded-xl overflow-hidden mb-4">
                            <?php if (!empty($data['nextEvent']['thumbnail'])): ?>
                                <img src="<?= BASE_URL ?>/images/events/<?= $data['nextEvent']['thumbnail'] ?>" alt="<?= htmlspecialchars($data['nextEvent']['title']) ?>" class="w-full h-full object-cover">
                            <?php else: ?>
                                <div class="flex items-center justify-center h-full bg-blue-50">
                                    <svg class="w-12 h-12 text-blue-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                    </svg>
                                </div>
                            <?php endif; ?>

                            <!-- Category Badge -->
                            <span class="absolute top-3 left-3 bg-blue-100 text-blue-800 text-xs font-medium px-2.5 py-0.5 rounded-full">
                                <?= htmlspecialchars($data['nextEvent']['category_name']) ?>
                            </span>
                        </div>

                        <!-- Event Details -->
                        <div>
                            <h4 class="text-xl font-semibold text-blue-900 line-clamp-2 mb-2"><?= htmlspecialchars($data['nextEvent']['title']) ?></h4>
                        </div>

                        <div class="grid grid-cols-2 gap-4 mb-4">
                            <!-- Date and Time -->
                            <div class="flex items-start">
                                <svg class="w-5 h-5 text-blue-500 mr-2 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                </svg>
                                <div>
                                    <p class="text-sm font-medium text-gray-700"><?= date('D, d M Y', strtotime($data['nextEvent']['start_date'])) ?></p>
                                    <p class="text-xs text-gray-500"><?= date('H:i', strtotime($data['nextEvent']['start_time'])) ?> - <?= date('H:i', strtotime($data['nextEvent']['end_time'])) ?></p>
                                </div>
                            </div>

                            <!-- Location -->
                            <div class="flex items-start justify-end">
                                <svg class="w-5 h-5 text-blue-500 mr-2 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                </svg>
                                <div>
                                    <p class="text-sm font-medium text-gray-700"><?= htmlspecialchars($data['nextEvent']['location_name']) ?></p>
                                    <p class="text-xs text-gray-500 truncate max-w-[150px]">
                                        <?= !empty($data['nextEvent']['location_address']) ? htmlspecialchars($data['nextEvent']['location_address']) : '' ?>
                                    </p>
                                </div>
                            </div>
                        </div>

                        <!-- Action Button -->
                        <a href="<?= BASE_URL ?>/event/show/<?= $data['nextEvent']['id_event'] ?>" class="block w-full text-center bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg py-2.5 transition-colors duration-200">
                            View Details
                        </a>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <!-- Recommended Events -->
        <div class="bg-white/70 p-6 col-span-3 backdrop-blur-sm rounded-2xl border border-blue-100 shadow-sm">
            <div class="flex items-center justify-between mb-5">
                <h3 class="text-lg font-semibold text-blue-900">Recommended For You</h3>
                <a href="<?= BASE_URL ?>/event" class="text-xs font-medium text-blue-600 hover:text-blue-800 flex items-center">
                    View all
                    <svg class="w-3 h-3 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                    </svg>
                </a>
            </div>

            <div class="flex flex-col h-full justify-center">
                <?php if (empty($data['recommendedEvents'])): ?>
                    <div class="flex flex-col items-center justify-center py-8 text-center bg-blue-50/50 rounded-xl">
                        <div class="w-16 h-16 mb-3 flex items-center justify-center rounded-full bg-blue-100">
                            <svg class="w-8 h-8 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                            </svg>
                        </div>
                        <h4 class="text-base font-semibold text-blue-900 mb-1">No recommendations at the moment</h4>
                        <p class="text-sm text-blue-600 mb-4">We'll suggest events based on your interests</p>
                        <a href="<?= BASE_URL ?>/event" class="inline-flex items-center px-4 py-2 text-sm font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-700 transition-colors">
                            Explore all events
                            <svg class="w-3.5 h-3.5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/>
                            </svg>
                        </a>
                    </div>
                <?php else: ?>
                    <!-- Two-column grid for recommended events -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-3 mb-4">
                        <?php foreach ($data['recommendedEvents'] as $index => $event): ?>
                            <div class="bg-white rounded-xl overflow-hidden shadow-sm hover:shadow-md transition-all duration-300 border border-gray-100 group hover:border-blue-200">
                                <a href="<?= BASE_URL ?>/event/show/<?= $event['id'] ?>" class="block h-full">
                                    <!-- Event Image with Overlay -->
                                    <div class="relative h-52 overflow-hidden">
                                        <?php if (!empty($event['thumbnail'])): ?>
                                            <img src="<?= BASE_URL ?>/images/events/<?= $event['thumbnail'] ?>"
                                                 alt="<?= htmlspecialchars($event['title']) ?>"
                                                 class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105">
                                        <?php else: ?>
                                            <div class="w-full h-full flex items-center justify-center bg-blue-50">
                                                <svg class="w-12 h-12 text-blue-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                                </svg>
                                            </div>
                                        <?php endif; ?>

                                        <!-- Dark overlay with gradient -->
                                        <div class="absolute inset-0 bg-gradient-to-t from-black/60 to-transparent"></div>

                                        <!-- Category badge on top left -->
                                        <span class="absolute top-2 left-2 z-10 text-xs font-medium px-2 py-0.5 rounded-full bg-blue-100 text-blue-800">
                                            <?= htmlspecialchars($event['category_name']) ?>
                                        </span>

                                        <!-- Date and participants at bottom -->
                                        <div class="absolute bottom-2 left-2 right-2 flex justify-between items-center z-10">
                                            <div class="flex items-center text-xs text-white">
                                                <svg class="w-3 h-3 mr-1 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                                </svg>
                                                <?= date('d M Y', strtotime($event['start_date'])) ?>
                                            </div>
                                            <div class="flex items-center text-xs text-white font-medium">
                                                <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                    <path d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z"></path>
                                                </svg>
                                                <?= isset($event['participants_count']) ? $event['participants_count'] : 0 ?> joined
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Event Content -->
                                    <div class="p-3">
                                        <div class="flex justify-between items-start mb-2">
                                            <h4 class="text-sm font-semibold text-gray-900 group-hover:text-blue-700 line-clamp-1 transition-colors flex-1">
                                                <?= htmlspecialchars($event['title']) ?>
                                            </h4>

                                            <!-- Event timing badge -->
                                            <?php
                                            $now = new DateTime();
                                            $eventDateTime = new DateTime($event['start_date'] . ' ' . $event['start_time']);
                                            $interval = $now->diff($eventDateTime);
                                            $daysUntil = (int)$interval->format('%r%a');
                                            ?>
                                            <?php if ($daysUntil === 0 && $now->format('Y-m-d') === $event['start_date']): ?>
                                                <span class="text-xs font-medium px-2 py-0.5 bg-blue-100 text-blue-700 rounded-full ml-2 whitespace-nowrap">
                                                    Today
                                                </span>
                                            <?php elseif ($daysUntil === 1): ?>
                                                <span class="text-xs font-medium px-2 py-0.5 bg-orange-100 text-orange-700 rounded-full ml-2 whitespace-nowrap">
                                                    Tomorrow
                                                </span>
                                            <?php elseif ($daysUntil < 0): ?>
                                                <span class="text-xs font-medium px-2 py-0.5 bg-gray-100 text-gray-500 rounded-full ml-2 whitespace-nowrap">
                                                    Passed
                                                </span>
                                            <?php else: ?>
                                                <span class="text-xs font-medium px-2 py-0.5 bg-green-100 text-green-700 rounded-full ml-2 whitespace-nowrap">
                                                    <?= $daysUntil ?> days
                                                </span>
                                            <?php endif; ?>
                                        </div>

                                        <!-- Short description -->
                                        <p class="text-xs text-gray-600 line-clamp-2 mb-2">
                                            <?= htmlspecialchars($event['short_description']) ?>
                                        </p>

                                        <!-- Location -->
                                        <div class="flex items-center text-xs text-gray-500">
                                            <svg class="w-3 h-3 mr-1 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                            </svg>
                                            <span class="truncate"><?= htmlspecialchars($event['location_name']) ?></span>
                                        </div>
                                    </div>
                                </a>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</section>

<style>
    .blue-gradient {
        background: linear-gradient(135deg, #e0f2fe 0%, #bfdbfe 100%);
    }
</style>
