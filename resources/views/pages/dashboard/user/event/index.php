<section class="py-8">
    <div class="container max-w-screen-xl mx-auto px-4">
        <header class="mb-8 flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-gray-900 mb-2">My Events</h1>
                <p class="text-lg text-gray-600">Manage your event registrations and tickets</p>
            </div>
            <div class="mt-4">
                <a
                        href="<?= BASE_URL ?>/event"
                        class="inline-flex items-center px-5 py-2.5 text-sm font-medium text-center text-white bg-blue-700 rounded-lg hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300"
                >
                    Browse Events
                    <svg
                            class="w-3.5 h-3.5 ml-2"
                            aria-hidden="true"
                            xmlns="http://www.w3.org/2000/svg"
                            fill="none"
                            viewBox="0 0 14 10"
                    >
                        <path
                                stroke="currentColor"
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M1 5h12m0 0L9 1m4 4L9 9"
                        />
                    </svg>
                </a>
        </header>

        <!-- Tab navigation -->
        <div class="mb-6 border-b border-gray-200">
            <ul class="flex flex-wrap -mb-px text-sm font-medium text-center" id="eventTabs" role="tablist">
                <li class="mr-2" role="presentation">
                    <button
                            class="inline-block p-4 border-b-2 border-blue-600 rounded-t-lg text-blue-600"
                            id="upcoming-tab"
                            data-tabs-target="#upcoming"
                            type="button"
                            role="tab"
                            aria-controls="upcoming"
                            aria-selected="true"
                    >
                        Upcoming Events
                    </button>
                </li>
                <li class="mr-2" role="presentation">
                    <button
                            class="inline-block p-4 border-b-2 border-transparent rounded-t-lg hover:text-gray-600 hover:border-gray-300"
                            id="past-tab"
                            data-tabs-target="#past"
                            type="button"
                            role="tab"
                            aria-controls="past"
                            aria-selected="false"
                    >
                        Past Events
                    </button>
                </li>
                <li role="presentation">
                    <button
                            class="inline-block p-4 border-b-2 border-transparent rounded-t-lg hover:text-gray-600 hover:border-gray-300"
                            id="canceled-tab"
                            data-tabs-target="#canceled"
                            type="button"
                            role="tab"
                            aria-controls="canceled"
                            aria-selected="false"
                    >
                        Canceled
                    </button>
                </li>
            </ul>
        </div>

        <!-- Flash messages -->
        <?php if (isset($_SESSION['flash'])): ?>
            <div
                    id="flashMessage"
                    class="flex items-center p-4 mb-6 <?= ($_SESSION['flash']['type'] === 'success') ? 'text-green-800 bg-green-50' : (($_SESSION['flash']['type'] === 'error') ? 'text-red-800 bg-red-50' : 'text-yellow-800 bg-yellow-50') ?> rounded-lg"
                    role="alert"
            >
                <svg
                        class="flex-shrink-0 w-4 h-4"
                        aria-hidden="true"
                        xmlns="http://www.w3.org/2000/svg"
                        fill="currentColor"
                        viewBox="0 0 20 20"
                >
                    <path
                            d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5ZM9.5 4a1.5 1.5 0 1 1 0 3 1.5 1.5 0 0 1 0-3ZM12 15H8a1 1 0 0 1 0-2h1v-3H8a1 1 0 0 1 0-2h2a1 1 0 0 1 1 1v4h1a1 1 0 0 1 0 2Z"
                    />
                </svg>
                <span class="sr-only"><?= ucfirst($_SESSION['flash']['type']) ?></span>
                <div class="ml-3 text-sm font-medium">
                    <strong><?= $_SESSION['flash']['title'] ?>:</strong>
                    <?= $_SESSION['flash']['message'] ?>
                </div>
                <button
                        type="button"
                        class="ml-auto -mx-1.5 -my-1.5 rounded-lg focus:ring-2 p-1.5 inline-flex items-center justify-center h-8 w-8 <?= ($_SESSION['flash']['type'] === 'success') ? 'text-green-500 bg-green-50 hover:bg-green-200' : (($_SESSION['flash']['type'] === 'error') ? 'text-red-500 bg-red-50 hover:bg-red-200' : 'text-yellow-500 bg-yellow-50 hover:bg-yellow-200') ?>"
                        data-dismiss-target="#flashMessage"
                        aria-label="Close"
                >
                    <span class="sr-only">Close</span>
                    <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
                        <path
                                stroke="currentColor"
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6"
                        />
                    </svg>
                </button>
            </div>
        <?php endif; ?>

        <?php if (empty($data['registrations'])): ?>
            <!-- No registrations -->
            <div class="text-center py-12 bg-white rounded-lg shadow-sm border border-gray-200">
                <svg
                        class="w-16 h-16 mx-auto mb-4 text-gray-400"
                        aria-hidden="true"
                        xmlns="http://www.w3.org/2000/svg"
                        fill="none"
                        viewBox="0 0 24 24"
                >
                    <path
                            stroke="currentColor"
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="1.5"
                            d="M3.35 5.47 5 7.12m15.65-1.65L19 7.12M15 7c0-1.1-.9-2-2-2s-2 .9-2 2M8.9 14.7 6.2 17.5m8.9-2.8 2.7 2.8M12 12v2M8 16a4 4 0 1 0 8 0"
                    />
                </svg>
                <h3 class="text-lg font-semibold text-gray-900 mb-2">No events registered yet</h3>
                <p class="text-gray-600 mb-6">You haven't registered for any events. Start by exploring our available events.</p>
                <a
                        href="<?= BASE_URL ?>/event"
                        class="inline-flex items-center px-5 py-2.5 text-sm font-medium text-center text-white bg-blue-700 rounded-lg hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300"
                >
                    Browse Events
                    <svg
                            class="w-3.5 h-3.5 ml-2"
                            aria-hidden="true"
                            xmlns="http://www.w3.org/2000/svg"
                            fill="none"
                            viewBox="0 0 14 10"
                    >
                        <path
                                stroke="currentColor"
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M1 5h12m0 0L9 1m4 4L9 9"
                        />
                    </svg>
                </a>
            </div>
        <?php else: ?>
            <!-- Tab content -->
            <div id="eventTabContent">
                <!-- Upcoming Events -->
                <div class="block" id="upcoming" role="tabpanel" aria-labelledby="upcoming-tab">
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        <?php
                        $hasUpcoming = false;
                        foreach ($data['registrations'] as $registration):
                            // Skip if status is canceled or if event has already ended
                            if ($registration['status'] === 'canceled' || strtotime($registration['end_date'] . ' ' . $registration['end_time']) < time()) continue;
                            $hasUpcoming = true;
                            ?>
                            <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden flex flex-col">
                                <!-- Event Image -->
                                <div class="relative h-52 bg-gray-100">
                                    <?php if (!empty($registration['thumbnail'])): ?>
                                        <img
                                                src="<?= BASE_URL ?>/images/events/<?= $registration['thumbnail'] ?>"
                                                alt="<?= $registration['title'] ?>"
                                                class="w-full h-full object-cover"
                                        />
                                    <?php else: ?>
                                        <div class="flex items-center justify-center h-full bg-gray-200">
                                            <svg
                                                    class="w-12 h-12 text-gray-400"
                                                    aria-hidden="true"
                                                    xmlns="http://www.w3.org/2000/svg"
                                                    fill="none"
                                                    viewBox="0 0 24 24"
                                            >
                                                <path
                                                        stroke="currentColor"
                                                        stroke-linecap="round"
                                                        stroke-linejoin="round"
                                                        stroke-width="1.5"
                                                        d="M9 5H7a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2V7a2 2 0 0 0-2-2h-2M9 5a2 2 0 0 0 2 2h2a2 2 0 0 0 2-2M9 5a2 2 0 0 1 2-2h2a2 2 0 0 1 2 2"
                                                />
                                            </svg>
                                        </div>
                                    <?php endif; ?>
                                    <!-- Category Badge -->
                                    <span
                                            class="absolute top-2 left-2 bg-blue-100 text-blue-800 text-xs font-medium px-2.5 py-0.5 rounded-full"
                                    >
								<?= $registration['category_name'] ?>
							</span>
                                </div>

                                <!-- Event Content -->
                                <div class="p-5 pb-2 flex-grow">
                                    <h3 class="text-lg font-bold line-clamp-2 text-gray-900 mb-2"><?= $registration['title'] ?></h3>

                                    <!-- Event Details -->
                                    <div class="mb-4 mt-4 space-y-3">
                                        <!-- Date and Time -->
                                        <div class="flex items-start text-sm">
                                            <svg
                                                    class="w-4 h-4 text-gray-500 mr-3 mt-0.5 flex-shrink-0"
                                                    aria-hidden="true"
                                                    xmlns="http://www.w3.org/2000/svg"
                                                    fill="none"
                                                    viewBox="0 0 20 20"
                                            >
                                                <path
                                                        stroke="currentColor"
                                                        stroke-linecap="round"
                                                        stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M5 1v3m5-3v3m5-3v3M1 7h18M5 11h10M2 3h16a1 1 0 0 1 1 1v14a1 1 0 0 1-1 1H2a1 1 0 0 1-1-1V4a1 1 0 0 1 1-1Z"
                                                />
                                            </svg>
                                            <div>
                                                <p class="text-gray-900 font-medium">
                                                    <?= date('D, d M Y', strtotime($registration['start_date'])) ?>
                                                </p>
                                                <p class="text-gray-600">
                                                    <?= date('H:i', strtotime($registration['start_time'])) ?>
                                                    -
                                                    <?= date('H:i', strtotime($registration['end_time'])) ?>
                                                </p>
                                            </div>
                                        </div>

                                        <!-- Location -->
                                        <div class="flex items-start text-sm">
                                            <svg
                                                    class="w-4 h-4 text-gray-500 mr-3 mt-0.5 flex-shrink-0"
                                                    aria-hidden="true"
                                                    xmlns="http://www.w3.org/2000/svg"
                                                    fill="none"
                                                    viewBox="0 0 17 21"
                                            >
                                                <path
                                                        stroke="currentColor"
                                                        stroke-linecap="round"
                                                        stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M8 12a3 3 0 1 0 0-6 3 3 0 0 0 0 6Z"
                                                />
                                                <path
                                                        stroke="currentColor"
                                                        stroke-linecap="round"
                                                        stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M13.8 12.938h-.01a7 7 0 1 0-11.465.144h-.016l.141.171c.1.127.2.25.312.367l4.12 4.53a1 1 0 0 0 1.475 0l4.12-4.53c.112-.116.212-.24.313-.367l.141-.17Z"
                                                />
                                            </svg>
                                            <span class="text-gray-900 font-medium"><?= $registration['location_name'] ?></span>
                                        </div>
                                    </div>

                                    <!-- Registration Code -->
                                    <div class="mb-4 p-2 bg-gray-50 rounded-lg border border-gray-200">
                                        <p class="text-xs text-gray-500 mb-1">Registration Code:</p>
                                        <p class="text-sm font-mono font-medium"><?= $registration['registration_code'] ?></p>
                                    </div>
                                </div>

                                <!-- Action Buttons -->
                                <div class="border-t border-gray-100 p-4">
                                    <div class="flex gap-2">
                                        <a
                                                href="<?= BASE_URL ?>/event/show/<?= $registration['id_event'] ?>"
                                                class="flex-1 text-center text-gray-900 bg-white border border-gray-300 hover:bg-gray-100 focus:ring-4 focus:outline-none focus:ring-gray-200 rounded-lg text-sm px-5 py-2.5"
                                        >
                                            View Event
                                        </a>
                                        <form
                                                action="<?= BASE_URL ?>/registration/destroy/<?= $registration['id'] ?>"
                                                method="post"
                                                class="flex-1"
                                                onsubmit="return confirm('Are you sure you want to cancel this registration?');"
                                        >
                                            <button
                                                    type="submit"
                                                    class="w-full text-white bg-red-700 hover:bg-red-800 focus:ring-4 focus:outline-none focus:ring-red-300 rounded-lg text-sm px-5 py-2.5"
                                            >
                                                Cancel
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>

                        <?php if (!$hasUpcoming): ?>
                            <div class="col-span-full text-center py-12 bg-white rounded-lg shadow-sm border border-gray-200">
                                <svg
                                        class="w-12 h-12 mx-auto mb-4 text-gray-400"
                                        aria-hidden="true"
                                        xmlns="http://www.w3.org/2000/svg"
                                        fill="none"
                                        viewBox="0 0 20 20"
                                >
                                    <path
                                            stroke="currentColor"
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            stroke-width="2"
                                            d="M5 1v3m5-3v3m5-3v3M1 7h18M5 11h10M2 3h16a1 1 0 0 1 1 1v14a1 1 0 0 1-1 1H2a1 1 0 0 1-1-1V4a1 1 0 0 1 1-1Z"
                                    />
                                </svg>
                                <h3 class="text-lg font-semibold text-gray-900 mb-2">No upcoming events</h3>
                                <p class="text-gray-600 mb-6">You don't have any upcoming event registrations.</p>
                                <a
                                        href="<?= BASE_URL ?>/event"
                                        class="inline-flex items-center px-5 py-2.5 text-sm font-medium text-center text-white bg-blue-700 rounded-lg hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300"
                                >
                                    Browse Events
                                    <svg
                                            class="w-3.5 h-3.5 ml-2"
                                            aria-hidden="true"
                                            xmlns="http://www.w3.org/2000/svg"
                                            fill="none"
                                            viewBox="0 0 14 10"
                                    >
                                        <path
                                                stroke="currentColor"
                                                stroke-linecap="round"
                                                stroke-linejoin="round"
                                                stroke-width="2"
                                                d="M1 5h12m0 0L9 1m4 4L9 9"
                                        />
                                    </svg>
                                </a>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Past Events -->
                <div class="hidden" id="past" role="tabpanel" aria-labelledby="past-tab">
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        <?php
                        $hasPast = false;
                        foreach ($data['registrations'] as $registration):
                            // Only show events that have ended and are not canceled
                            if ($registration['status'] === 'canceled' || strtotime($registration['end_date'] . ' ' . $registration['end_time']) >=
                                time()) continue; $hasPast = true; ?>
                            <div
                                    class="bg-white rounded-xl shadow-lg border border-gray-200 overflow-hidden transition-all duration-300 hover:shadow-xl"
                            >
                                <!-- Event Image -->
                                <div class="relative h-48 bg-gradient-to-br from-gray-100 to-gray-200">
                                    <?php if (!empty($registration['thumbnail'])): ?>
                                        <img
                                                src="<?= BASE_URL ?>/images/events/<?= $registration['thumbnail'] ?>"
                                                alt="<?= htmlspecialchars($registration['title']) ?>"
                                                class="w-full h-full object-cover"
                                        />
                                    <?php else: ?>
                                        <div class="flex items-center justify-center h-full w-full">
                                            <svg class="w-16 h-16 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path
                                                        stroke-linecap="round"
                                                        stroke-linejoin="round"
                                                        stroke-width="1.5"
                                                        d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"
                                                />
                                            </svg>
                                        </div>
                                    <?php endif; ?>

                                    <!-- Overlay gradient -->
                                    <div class="absolute inset-0 bg-gradient-to-t from-black/20 to-transparent"></div>

                                    <!-- Category Badge -->
                                    <div class="absolute top-4 left-4">
								<span
                                        class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-blue-500 text-white shadow-lg"
                                >
									<?= htmlspecialchars($registration['category_name']) ?>
								</span>
                                    </div>

                                    <!-- Status Badge -->
                                    <div class="absolute top-4 right-4">
								<span
                                        class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-red-500 text-white shadow-lg"
                                >
									Canceled
								</span>
                                    </div>
                                </div>

                                <!-- Event Content -->
                                <div class="p-6">
                                    <!-- Title -->
                                    <h3 class="text-xl font-bold text-gray-900 mb-3 leading-tight">
                                        <?= htmlspecialchars($registration['title']) ?>
                                    </h3>

                                    <!-- Description -->
                                    <?php if (!empty($registration['description'])): ?>
                                        <div class="mb-4">
                                            <p class="text-gray-600 text-sm leading-relaxed">
                                                <?= htmlspecialchars(substr(strip_tags($registration['description']), 0, 120)) ?>
                                                <?php if (strlen(strip_tags($registration['description'])) >
                                                    120): ?>...<?php endif; ?>
                                            </p>
                                        </div>
                                    <?php endif; ?>

                                    <!-- Event Details -->
                                    <div class="space-y-3 mb-5">
                                        <!-- Date and Time -->
                                        <div class="flex items-center text-sm text-gray-700">
                                            <div class="flex-shrink-0 w-5 h-5 mr-3 text-gray-400">
                                                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path
                                                            stroke-linecap="round"
                                                            stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"
                                                    />
                                                </svg>
                                            </div>
                                            <div>
                                                <div class="font-medium text-gray-900">
                                                    <?= date('l, F j, Y', strtotime($registration['start_date'])) ?>
                                                </div>
                                                <div class="text-gray-600">
                                                    <?= date('g:i A', strtotime($registration['start_time'])) ?>
                                                    -
                                                    <?= date('g:i A', strtotime($registration['end_time'])) ?>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Location -->
                                        <div class="flex items-center text-sm text-gray-700">
                                            <div class="flex-shrink-0 w-5 h-5 mr-3 text-gray-400">
                                                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path
                                                            stroke-linecap="round"
                                                            stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"
                                                    />
                                                    <path
                                                            stroke-linecap="round"
                                                            stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"
                                                    />
                                                </svg>
                                            </div>
                                            <span class="font-medium text-gray-900">
										<?= htmlspecialchars($registration['location_name']) ?>
									</span>
                                        </div>
                                    </div>

                                    <!-- Registration Code -->
                                    <div class="bg-gray-50 rounded-lg p-4 mb-5">
                                        <div class="flex items-center justify-between">
                                            <div>
                                                <p class="text-xs font-medium text-gray-500 uppercase tracking-wide mb-1">Registration Code</p>
                                                <p class="font-mono text-sm font-semibold text-gray-900 tracking-wider">
                                                    <?= htmlspecialchars($registration['registration_code']) ?>
                                                </p>
                                            </div>
                                            <div class="ml-4">
                                                <div class="w-8 h-8 bg-gray-200 rounded-full flex items-center justify-center">
                                                    <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path
                                                                stroke-linecap="round"
                                                                stroke-linejoin="round"
                                                                stroke-width="2"
                                                                d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"
                                                        />
                                                    </svg>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Action Button -->
                                    <div class="pt-2">
                                        <a
                                                href="<?= BASE_URL ?>/event/<?= $registration['id_event'] ?>"
                                                class="w-full inline-flex items-center justify-center px-6 py-3 border border-gray-300 shadow-sm text-sm font-medium rounded-lg text-gray-700 bg-white hover:bg-gray-50 hover:border-gray-400 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-all duration-200"
                                        >
                                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path
                                                        stroke-linecap="round"
                                                        stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"
                                                />
                                                <path
                                                        stroke-linecap="round"
                                                        stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"
                                                />
                                            </svg>
                                            View Event Details
                                        </a>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>

                        <?php if (!$hasPast): ?>
                            <div class="col-span-full text-center py-12 bg-white rounded-lg shadow-sm border border-gray-200">
                                <svg
                                        class="w-12 h-12 mx-auto mb-4 text-gray-400"
                                        aria-hidden="true"
                                        xmlns="http://www.w3.org/2000/svg"
                                        fill="none"
                                        viewBox="0 0 20 20"
                                >
                                    <path
                                            stroke="currentColor"
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            stroke-width="2"
                                            d="M5 1v3m5-3v3m5-3v3M1 7h18M5 11h10M2 3h16a1 1 0 0 1 1 1v14a1 1 0 0 1-1 1H2a1 1 0 0 1-1-1V4a1 1 0 0 1 1-1Z"
                                    />
                                </svg>
                                <h3 class="text-lg font-semibold text-gray-900 mb-2">No past events</h3>
                                <p class="text-gray-600">You haven't attended any events yet.</p>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Canceled Events -->
                <div class="hidden" id="canceled" role="tabpanel" aria-labelledby="canceled-tab">
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        <?php
                        $hasCanceled = false;
                        foreach ($data['registrations'] as $registration):
                            // Only show canceled registrations
                            if ($registration['status'] !== 'canceled') continue;
                            $hasCanceled = true;
                            ?>
                            <div
                                    class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden flex flex-col opacity-75 hover:opacity-100 transition-opacity duration-300"
                            >
                                <!-- Event Image -->
                                <div class="relative h-48 bg-gray-100">
                                    <?php if (!empty($registration['thumbnail'])): ?>
                                        <img
                                                src="<?= BASE_URL ?>/images/events/<?= $registration['thumbnail'] ?>"
                                                alt="<?= $registration['title'] ?>"
                                                class="w-full h-full object-cover"
                                        />
                                    <?php else: ?>
                                        <div class="flex items-center justify-center h-full w-full bg-gray-200">
                                            <svg
                                                    class="w-12 h-12 text-gray-400"
                                                    aria-hidden="true"
                                                    xmlns="http://www.w3.org/2000/svg"
                                                    fill="none"
                                                    viewBox="0 0 24 24"
                                            >
                                                <path
                                                        stroke="currentColor"
                                                        stroke-linecap="round"
                                                        stroke-linejoin="round"
                                                        stroke-width="1.5"
                                                        d="M9 5H7a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2V7a2 2 0 0 0-2-2h-2M9 5a2 2 0 0 0 2 2h2a2 2 0 0 0 2-2M9 5a2 2 0 0 1 2-2h2a2 2 0 0 1 2 2"
                                                />
                                            </svg>
                                        </div>
                                    <?php endif; ?>

                                    <!-- Category Badge -->
                                    <span
                                            class="absolute top-3 left-3 bg-blue-100 text-blue-800 text-xs font-medium px-3 py-1 rounded-full shadow-sm"
                                    >
								<?= $registration['category_name'] ?>
							</span>

                                    <!-- Canceled Badge -->
                                    <span
                                            class="absolute top-3 right-3 bg-red-100 text-red-800 text-xs font-medium px-3 py-1 rounded-full shadow-sm"
                                    >
								Canceled
							</span>
                                </div>

                                <!-- Event Content -->
                                <div class="p-6 flex-grow">
                                    <!-- Title -->
                                    <h3 class="text-xl font-bold text-gray-900 mb-3 line-clamp-2"><?= $registration['title'] ?></h3>

                                    <!-- Description -->
                                    <?php if (!empty($registration['description'])): ?>
                                        <div class="mb-4">
                                            <p class="text-gray-600 text-sm leading-relaxed line-clamp-3">
                                                <?= strip_tags($registration['description']) ?>
                                            </p>
                                        </div>
                                    <?php endif; ?>

                                    <!-- Event Details -->
                                    <div class="mb-4 space-y-3">
                                        <!-- Date and Time -->
                                        <div class="flex items-start text-sm">
                                            <svg
                                                    class="w-4 h-4 text-gray-500 mr-3 mt-0.5 flex-shrink-0"
                                                    aria-hidden="true"
                                                    xmlns="http://www.w3.org/2000/svg"
                                                    fill="none"
                                                    viewBox="0 0 20 20"
                                            >
                                                <path
                                                        stroke="currentColor"
                                                        stroke-linecap="round"
                                                        stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M5 1v3m5-3v3m5-3v3M1 7h18M5 11h10M2 3h16a1 1 0 0 1 1 1v14a1 1 0 0 1-1 1H2a1 1 0 0 1-1-1V4a1 1 0 0 1 1-1Z"
                                                />
                                            </svg>
                                            <div>
                                                <p class="text-gray-900 font-medium">
                                                    <?= date('D, d M Y', strtotime($registration['start_date'])) ?>
                                                </p>
                                                <p class="text-gray-600">
                                                    <?= date('H:i', strtotime($registration['start_time'])) ?>
                                                    -
                                                    <?= date('H:i', strtotime($registration['end_time'])) ?>
                                                </p>
                                            </div>
                                        </div>

                                        <!-- Location -->
                                        <div class="flex items-start text-sm">
                                            <svg
                                                    class="w-4 h-4 text-gray-500 mr-3 mt-0.5 flex-shrink-0"
                                                    aria-hidden="true"
                                                    xmlns="http://www.w3.org/2000/svg"
                                                    fill="none"
                                                    viewBox="0 0 17 21"
                                            >
                                                <path
                                                        stroke="currentColor"
                                                        stroke-linecap="round"
                                                        stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M8 12a3 3 0 1 0 0-6 3 3 0 0 0 0 6Z"
                                                />
                                                <path
                                                        stroke="currentColor"
                                                        stroke-linecap="round"
                                                        stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M13.8 12.938h-.01a7 7 0 1 0-11.465.144h-.016l.141.171c.1.127.2.25.312.367l4.12 4.53a1 1 0 0 0 1.475 0l4.12-4.53c.112-.116.212-.24.313-.367l.141-.17Z"
                                                />
                                            </svg>
                                            <span class="text-gray-900 font-medium"><?= $registration['location_name'] ?></span>
                                        </div>
                                    </div>

                                    <!-- Registration Code -->
                                    <div class="mb-4 p-3 bg-gray-50 rounded-lg border border-gray-200">
                                        <p class="text-xs text-gray-500 mb-1 font-medium">Registration Code</p>
                                        <p class="text-sm font-mono font-semibold text-gray-900 tracking-wider">
                                            <?= $registration['registration_code'] ?>
                                        </p>
                                    </div>
                                </div>

                                <!-- Action Buttons -->
                                <div class="border-t border-gray-100 p-4 bg-gray-50">
                                    <a
                                            href="<?= BASE_URL ?>/event/<?= $registration['id_event'] ?>"
                                            class="block w-full text-center text-gray-700 bg-white border border-gray-300 hover:bg-gray-100 hover:border-gray-400 focus:ring-4 focus:outline-none focus:ring-gray-200 rounded-lg text-sm px-5 py-2.5 font-medium transition-colors duration-200"
                                    >
                                        View Event Details
                                    </a>
                                </div>
                            </div>
                        <?php endforeach; ?>

                        <?php if (!$hasCanceled): ?>
                            <div class="col-span-full text-center py-12 bg-white rounded-lg shadow-sm border border-gray-200">
                                <svg
                                        class="w-12 h-12 mx-auto mb-4 text-gray-400"
                                        aria-hidden="true"
                                        xmlns="http://www.w3.org/2000/svg"
                                        fill="none"
                                        viewBox="0 0 20 20"
                                >
                                    <path
                                            stroke="currentColor"
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            stroke-width="2"
                                            d="M6 14H2a1 1 0 0 0-1 1v4h6v-4a1 1 0 0 0-1-1Zm8 0h-4a1 1 0 0 0-1 1v4h6v-4a1 1 0 0 0-1-1Zm-4-6h4a1 1 0 0 0 1-1V1h-6v6a1 1 0 0 0 1 1ZM5 8h1a1 1 0 0 0 1-1V1H1v6a1 1 0 0 0 1 1h3Z"
                                    />
                                </svg>
                                <h3 class="text-lg font-semibold text-gray-900 mb-2">No canceled registrations</h3>
                                <p class="text-gray-600">You don't have any canceled event registrations.</p>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        <?php endif; ?>
    </div>
</section>

<script>
    document.addEventListener("DOMContentLoaded", function () {
        // Tab functionality
        const tabs = document.querySelectorAll('[role="tab"]');
        const tabContents = document.querySelectorAll('[role="tabpanel"]');

        tabs.forEach((tab) => {
            tab.addEventListener("click", () => {
                // Deactivate all tabs
                tabs.forEach((t) => {
                    t.classList.remove("text-blue-600", "border-blue-600");
                    t.classList.add("border-transparent");
                    t.setAttribute("aria-selected", "false");
                });

                // Hide all tab contents
                tabContents.forEach((content) => {
                    content.classList.add("hidden");
                });

                // Activate clicked tab
                tab.classList.add("text-blue-600", "border-blue-600");
                tab.classList.remove("border-transparent");
                tab.setAttribute("aria-selected", "true");

                // Show corresponding content
                const contentId = tab.getAttribute("data-tabs-target").substring(1);
                document.getElementById(contentId).classList.remove("hidden");
            });
        });

        // Dismiss flash message
        const dismissButtons = document.querySelectorAll("[data-dismiss-target]");
        dismissButtons.forEach((button) => {
            button.addEventListener("click", () => {
                const targetId = button.getAttribute("data-dismiss-target");
                document.querySelector(targetId).remove();
            });
        });
    });
</script>
