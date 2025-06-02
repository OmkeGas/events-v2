<!-- Recommended Events -->
<div class="bg-white/70 p-6 col-span-3 backdrop-blur-sm rounded-2xl border border-blue-100 shadow-sm">
    <div class="flex items-center justify-between mb-4">
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
            <div class="space-y-3">
                <?php foreach ($data['recommendedEvents'] as $index => $event): ?>
                    <div class="bg-white group hover:bg-blue-50/70 transition-colors duration-300 rounded-xl border border-gray-100 overflow-hidden shadow-sm hover:shadow-md">
                        <a href="<?= BASE_URL ?>/event/show/<?= $event['id'] ?>" class="flex p-0">
                            <!-- Event Thumbnail -->
                            <div class="w-24 h-24 bg-gray-100 flex-shrink-0 overflow-hidden">
                                <?php if (!empty($event['thumbnail'])): ?>
                                    <img src="<?= BASE_URL ?>/images/events/<?= $event['thumbnail'] ?>" alt="<?= htmlspecialchars($event['title']) ?>" class="w-full h-full object-cover">
                                <?php else: ?>
                                    <div class="flex items-center justify-center h-full bg-blue-50">
                                        <svg class="w-6 h-6 text-blue-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                        </svg>
                                    </div>
                                <?php endif; ?>
                            </div>

                            <!-- Event Info -->
                            <div class="flex-1 p-3 flex flex-col justify-between">
                                <div>
                                    <div class="flex items-center justify-between">
                                        <span class="text-xs px-2 py-0.5 bg-blue-100 text-blue-700 rounded-full">
                                            <?= htmlspecialchars($event['category_name']) ?>
                                        </span>
                                        <span class="text-xs text-blue-600 font-medium">
                                            <?= isset($event['participants_count']) ? $event['participants_count'] : 0 ?> joined
                                        </span>
                                    </div>
                                    <h4 class="text-sm font-semibold text-blue-900 group-hover:text-blue-700 transition-colors mt-1 mb-1 line-clamp-1">
                                        <?= htmlspecialchars($event['title']) ?>
                                    </h4>
                                </div>

                                <div class="flex items-center justify-between">
                                    <div class="flex items-center text-xs text-gray-500">
                                        <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                        </svg>
                                        <?= date('d M Y', strtotime($event['start_date'])) ?>
                                    </div>

                                    <!-- Event Status Badge -->
                                    <?php
                                    $today = date('Y-m-d');
                                    $daysUntil = round((strtotime($event['start_date']) - strtotime($today)) / (60 * 60 * 24));
                                    ?>
                                    <?php if ($daysUntil <= 7 && $daysUntil > 0): ?>
                                        <span class="text-xs font-medium px-2 py-0.5 bg-red-100 text-red-700 rounded-full">
                                            Soon
                                        </span>
                                    <?php elseif ($daysUntil <= 14 && $daysUntil > 0): ?>
                                        <span class="text-xs font-medium px-2 py-0.5 bg-orange-100 text-orange-700 rounded-full">
                                            Coming up
                                        </span>
                                    <?php else: ?>
                                        <span class="text-xs font-medium px-2 py-0.5 bg-green-100 text-green-700 rounded-full">
                                            <?= $daysUntil ?> days away
                                        </span>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </a>
                    </div>
                <?php endforeach; ?>

                <div class="pt-4 text-center">
                    <a href="<?= BASE_URL ?>/event" class="inline-flex items-center px-4 py-2 text-sm font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-700 transition-colors">
                        Browse all events
                        <svg class="w-3.5 h-3.5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/>
                        </svg>
                    </a>
                </div>
            </div>
        <?php endif; ?>
    </div>
</div>
