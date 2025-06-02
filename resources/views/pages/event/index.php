<?php
// This page displays all published events in a card layout
?>

<section class="bg-white/30 backdrop-blur-sm py-8">
    <div class="container max-w-screen-xl mx-auto px-4">
        <header class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900 mb-2">Upcoming Events</h1>
            <p class="text-lg text-gray-600">Browse our upcoming events and register to participate</p>
        </header>

        <!-- Flash messages -->
        <?php if (isset($_SESSION['flash'])): ?>
            <div id="flashMessage" class="flex items-center p-4 mb-6 <?= ($_SESSION['flash']['type'] === 'success') ? 'text-green-800 bg-green-50' : (($_SESSION['flash']['type'] === 'error') ? 'text-red-800 bg-red-50' : 'text-yellow-800 bg-yellow-50') ?> rounded-lg" role="alert">
                <svg class="flex-shrink-0 w-4 h-4" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5ZM9.5 4a1.5 1.5 0 1 1 0 3 1.5 1.5 0 0 1 0-3ZM12 15H8a1 1 0 0 1 0-2h1v-3H8a1 1 0 0 1 0-2h2a1 1 0 0 1 1 1v4h1a1 1 0 0 1 0 2Z"/>
                </svg>
                <span class="sr-only"><?= ucfirst($_SESSION['flash']['type']) ?></span>
                <div class="ml-3 text-sm font-medium">
                    <strong><?= $_SESSION['flash']['title'] ?>:</strong> <?= $_SESSION['flash']['message'] ?>
                </div>
                <button type="button" class="ml-auto -mx-1.5 -my-1.5 rounded-lg focus:ring-2 p-1.5 inline-flex items-center justify-center h-8 w-8 <?= ($_SESSION['flash']['type'] === 'success') ? 'text-green-500 bg-green-50 hover:bg-green-200' : (($_SESSION['flash']['type'] === 'error') ? 'text-red-500 bg-red-50 hover:bg-red-200' : 'text-yellow-500 bg-yellow-50 hover:bg-yellow-200') ?>" data-dismiss-target="#flashMessage" aria-label="Close">
                    <span class="sr-only">Close</span>
                    <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6"/>
                    </svg>
                </button>
            </div>
        <?php endif; ?>

        <?php if (empty($data['events'])): ?>
            <!-- No events -->
            <div class="text-center py-12 bg-white rounded-lg shadow-sm border border-gray-200">
                <svg class="w-16 h-16 mx-auto mb-4 text-gray-400" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3.35 5.47 5 7.12m15.65-1.65L19 7.12M15 7c0-1.1-.9-2-2-2s-2 .9-2 2M8.9 14.7 6.2 17.5m8.9-2.8 2.7 2.8M12 12v2M8 16a4 4 0 1 0 8 0"/>
                </svg>
                <h3 class="text-lg font-semibold text-gray-900 mb-2">No events available</h3>
                <p class="text-gray-600 mb-6">There are currently no published events. Please check back later.</p>
                <?php if (isset($_SESSION['user'])): ?>
                    <a href="<?= BASE_URL ?>/dashboard" class="inline-flex items-center px-5 py-2.5 text-sm font-medium text-center text-white bg-blue-700 rounded-lg hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300">
                        Go to Dashboard
                        <svg class="w-3.5 h-3.5 ml-2" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 10">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M1 5h12m0 0L9 1m4 4L9 9"/>
                        </svg>
                    </a>
                <?php else: ?>
                    <a href="<?= BASE_URL ?>/login" class="inline-flex items-center px-5 py-2.5 text-sm font-medium text-center text-white bg-blue-700 rounded-lg hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300">
                        Login
                        <svg class="w-3.5 h-3.5 ml-2" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 10">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M1 5h12m0 0L9 1m4 4L9 9"/>
                        </svg>
                    </a>
                <?php endif; ?>
            </div>
        <?php else: ?>
            <!-- Events Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 ">
                <?php foreach ($data['events'] as $event): ?>
                    <div class="bg-white flex flex-col justify-between rounded-xl shadow-sm border border-gray-100 overflow-hidden hover:shadow-lg transition-shadow duration-300">
                        <!-- Event Image (Clickable) -->
                        <a href="<?= BASE_URL ?>/event/show/<?= $event['id'] ?>" class="block relative h-48 bg-gray-100">
                            <?php if (!empty($event['thumbnail'])): ?>
                                <img src="<?= BASE_URL ?>/images/events/<?= $event['thumbnail'] ?>"
                                     alt="<?= htmlspecialchars($event['title']) ?>"
                                     class="w-full h-full object-cover">
                            <?php else: ?>
                                <div class="flex items-center justify-center h-full bg-gray-200">
                                    <svg class="w-12 h-12 text-gray-400" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2V7a2 2 0 0 0-2-2h-2M9 5a2 2 0 0 0 2 2h2a2 2 0 0 0 2-2M9 5a2 2 0 0 1 2-2h2a2 2 0 0 1 2 2"/>
                                    </svg>
                                </div>
                            <?php endif; ?>

                            <!-- Category Badge -->
                            <span class="absolute top-3 left-3 bg-blue-100 text-blue-800 text-xs font-medium px-2.5 py-0.5 rounded-full shadow-sm">
                                <?= htmlspecialchars($event['category_name']) ?>
                            </span>

                            <!-- Date badge on top right with remaining days -->
                            <div class="absolute top-3 right-3 bg-white/90 backdrop-blur-sm text-gray-800 text-xs font-medium px-3 py-1 rounded-full shadow-sm">
                                <?php
                                // Calculate remaining days
                                $today = new DateTime();
                                $eventDate = new DateTime($event['start_date']);
                                $daysRemaining = $today->diff($eventDate)->days;
                                $isPast = $today > $eventDate;
                                ?>

                                <?php if ($isPast): ?>
                                    <span class="text-red-600">Passed</span>
                                <?php elseif ($daysRemaining == 0): ?>
                                    <span class="text-orange-600">Today!</span>
                                <?php elseif ($daysRemaining == 1): ?>
                                    <span class="text-orange-600">Tomorrow!</span>
                                <?php else: ?>
                                    <span class="text-gray-900"><?= $daysRemaining ?> days</span>
                                <?php endif; ?>
                            </div>
                        </a>

                        <!-- Event Content -->
                        <div class="p-4">
                            <!-- Speaker and Location (above title) -->
                            <div class="mb-3 flex items-center justify-end text-sm text-gray-600">
                                <!-- Location -->
                                <div class="flex items-center text-sm text-gray-600">
                                    <svg class="w-4 h-4 mr-1.5 flex-shrink-0" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 17 21">
                                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12a3 3 0 1 0 0-6 3 3 0 0 0 0 6Z"/>
                                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.8 12.938h-.01a7 7 0 1 0-11.465.144h-.016l.141.171c.1.127.2.25.312.367l4.12 4.53a1 1 0 0 0 1.475 0l4.12-4.53c.112-.116.212-.24.313-.367l.141-.17Z"/>
                                    </svg>
                                    <span class="truncate"><?= htmlspecialchars($event['location_name']) ?></span>
                                </div>
                            </div>

                            <!-- Clickable Title -->
                            <a href="<?= BASE_URL ?>/event/show/<?= $event['id'] ?>" class="block">
                                <h3 class="text-xl font-bold text-gray-900 mb-3 line-clamp-2 hover:text-blue-700 transition-colors">
                                    <?= htmlspecialchars($event['title']) ?>
                                </h3>
                            </a>

                            <!-- Short Description -->
                            <p class="text-gray-600 text-sm mb-4 line-clamp-3">
                                <?= htmlspecialchars($event['short_description']) ?>
                            </p>

                            <!-- Check available seats -->
                            <?php
                            $eventModel = new \App\Models\Event();
                            $quotaInfo = $eventModel->checkQuota($event['id']);
                            $registrationClosed = strtotime($event['registration_deadline']) < time();
                            $eventEnded = strtotime($event['end_date'] . ' ' . $event['end_time']) < time();
                            $isRegistered = false;

                            if (isset($_SESSION['user'])) {
                                $isRegistered = $eventModel->isUserRegistered($event['id'], $_SESSION['user']['id']);
                            }
                            ?>
                        </div>

                        <!-- Bottom Section with Status and Available Seats -->
                        <div class="px-4 py-3 bg-gray-50 border-t border-gray-100 flex items-center justify-between">
                            <!-- Registration Status -->
                            <div>
                                <?php if ($eventEnded): ?>
                                    <span class="inline-flex items-center px-2 py-1 rounded-md text-xs font-medium bg-gray-100 text-gray-800">
                                        <svg class="w-3 h-3 mr-1" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 20">
                                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6v4l3.276 3.276M19 10a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/>
                                        </svg>
                                        Event Ended
                                    </span>
                                <?php elseif ($registrationClosed): ?>
                                    <span class="inline-flex items-center px-2 py-1 rounded-md text-xs font-medium bg-yellow-100 text-yellow-800">
                                        <svg class="w-3 h-3 mr-1" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 20">
                                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6v4l3.276 3.276M19 10a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/>
                                        </svg>
                                        Registration Closed
                                    </span>
                                <?php elseif ($isRegistered): ?>
                                    <span class="inline-flex items-center px-2 py-1 rounded-md text-xs font-medium bg-green-100 text-green-800">
                                        <svg class="w-3 h-3 mr-1" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 20">
                                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m7 10 2 2 4-4m6 2a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/>
                                        </svg>
                                        Registered
                                    </span>
                                <?php elseif ($quotaInfo && $quotaInfo['available'] <= 0): ?>
                                    <span class="inline-flex items-center px-2 py-1 rounded-md text-xs font-medium bg-red-100 text-red-800">
                                        <svg class="w-3 h-3 mr-1" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 20">
                                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6v4l3.276 3.276M19 10a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/>
                                        </svg>
                                        Fully Booked
                                    </span>
                                <?php else: ?>
                                    <span class="inline-flex items-center px-2 py-1 rounded-md text-xs font-medium bg-blue-100 text-blue-800">
                                        <svg class="w-3 h-3 mr-1" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 20">
                                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 9h2v5m-2 0h4M9.408 5.5h.01M19 10a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/>
                                        </svg>
                                        Open
                                    </span>
                                <?php endif; ?>
                            </div>

                            <!-- Available Seats -->
                            <?php if ($quotaInfo): ?>
                                <div class="text-xs font-medium <?= ($quotaInfo['available'] > 0) ? 'text-green-600' : 'text-red-600' ?>">
                                    <?= $quotaInfo['available'] ?>/<?= $quotaInfo['quota'] ?> seats
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</section>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Dismiss flash message
    const dismissButtons = document.querySelectorAll('[data-dismiss-target]');
    dismissButtons.forEach(button => {
        button.addEventListener('click', () => {
            const targetId = button.getAttribute('data-dismiss-target');
            document.querySelector(targetId).remove();
        });
    });
});
</script>
