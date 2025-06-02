<?php
/**
 * Event Detail Page
 */

// Check if event has ended
$eventEnded = strtotime($data['event']['end_date'] . ' ' . $data['event']['end_time']) < time();
// Check if registration is closed
$registrationClosed = strtotime($data['event']['registration_deadline']) < time();
?>

<section class="bg-white/30 backdrop-blur-sm pt-8 pb-16">
    <div class="container max-w-screen-xl mx-auto px-4">

        <!-- Header -->
        <header class="mb-8">
            <nav class="flex" aria-label="Breadcrumb">
                <ol class="inline-flex items-center space-x-1 md:space-x-3">
                    <li class="inline-flex items-center">
                        <a href="<?= BASE_URL ?>" class="inline-flex items-center text-sm font-medium text-gray-700 hover:text-blue-600">
                            <svg class="w-3 h-3 mr-2.5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                                <path d="m19.707 9.293-2-2-7-7a1 1 0 0 0-1.414 0l-7 7-2 2a1 1 0 0 0 1.414 1.414L2 10.414V18a2 2 0 0 0 2 2h3a1 1 0 0 0 1-1v-4a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1v4a1 1 0 0 0 1 1h3a2 2 0 0 0 2-2v-7.586l.293.293a1 1 0 0 0 1.414-1.414Z"/>
                            </svg>
                            Home
                        </a>
                    </li>
                    <li>
                        <div class="flex items-center">
                            <svg class="w-3 h-3 text-gray-400 mx-1" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 6 10">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 9 4-4-4-4"/>
                            </svg>
                            <a href="<?= BASE_URL ?>/event" class="ml-1 text-sm font-medium text-gray-700 hover:text-blue-600 md:ml-2">Events</a>
                        </div>
                    </li>
                    <li aria-current="page">
                        <div class="flex items-center">
                            <svg class="w-3 h-3 text-gray-400 mx-1" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 6 10">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 9 4-4-4-4"/>
                            </svg>
                            <span class="ml-1 text-sm font-medium text-gray-500 md:ml-2"><?= $data['event']['title'] ?></span>
                        </div>
                    </li>
                </ol>
            </nav>
        </header>

        <!-- Main Content -->
        <div class="flex flex-col lg:flex-row gap-8">
            <!-- Main Article Section -->
            <article class="lg:w-2/3 bg-white rounded-xl shadow-sm p-6 border border-gray-100">
                <!-- Event Header -->
                <header class="mb-6">
                    <div class="flex flex-wrap items-center gap-2 mb-4">
                        <span class="bg-blue-100 text-blue-800 text-xs font-medium px-2.5 py-0.5 rounded-full">
                            <?= $data['event']['category_name'] ?>
                        </span>
                        <?php if ($eventEnded): ?>
                            <span class="bg-gray-100 text-gray-800 text-xs font-medium px-2.5 py-0.5 rounded-full">
                                Event Done
                            </span>
                        <?php elseif ($registrationClosed): ?>
                            <span class="bg-red-100 text-red-800 text-xs font-medium px-2.5 py-0.5 rounded-full">
                                Registration Closed
                            </span>
                        <?php else: ?>
                            <span class="bg-green-100 text-green-800 text-xs font-medium px-2.5 py-0.5 rounded-full">
                                Registration Open
                            </span>
                        <?php endif; ?>
                    </div>
                    <h1 class="text-3xl font-bold text-gray-900 mb-2"><?= $data['event']['title'] ?></h1>
                    <p class="text-md text-gray-600 mb-4"><?= $data['event']['short_description'] ?></p>
                </header>

                <!-- Featured Image -->
                <div class="mb-8">
                    <img src="<?= BASE_URL ?>/images/events/<?= $data['event']['thumbnail'] ?>"
                         alt="<?= $data['event']['title'] ?>"
                         class="w-full h-auto rounded-lg object-cover max-h-[400px]">
                </div>

                <!-- Speaker Info -->
                <div class="mb-6 flex items-center">
                    <div class="bg-blue-50 text-blue-800 px-3 py-1 rounded-lg mr-2 text-sm font-medium">Speaker</div>
                    <span class="text-gray-900 font-medium"><?= $data['event']['speaker'] ?></span>
                </div>

                <!-- Event Content -->
                <div class="wysiwyg-content max-w-none mb-8">
                    <?= $data['event']['full_description'] ?>
                </div>

                <!-- Admin Only: Participant List -->
                <?php if (isset($_SESSION['user']) && $_SESSION['user']['role'] === 'admin' && !empty($data['participants'])): ?>
                <div class="border-t border-gray-200 pt-6">
                    <h2 class="text-xl font-bold text-gray-900 mb-4">Participants (<?= count($data['participants']) ?>)</h2>

                    <div class="relative overflow-x-auto rounded-lg">
                        <table class="w-full text-sm text-left rtl:text-right text-gray-500">
                            <thead class="text-xs text-gray-700 uppercase bg-gray-100">
                                <tr>
                                    <th scope="col" class="px-6 py-3">Name</th>
                                    <th scope="col" class="px-6 py-3">Email</th>
                                    <th scope="col" class="px-6 py-3">Registration Date</th>
                                    <th scope="col" class="px-6 py-3">Status</th>
                                    <th scope="col" class="px-6 py-3">Attended</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach($data['participants'] as $participant): ?>
                                <tr class="bg-white border-b">
                                    <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap">
                                        <?= $participant['full_name'] ?>
                                    </th>
                                    <td class="px-6 py-4"><?= $participant['email'] ?></td>
                                    <td class="px-6 py-4"><?= date('d M Y, H:i', strtotime($participant['registration_date'])) ?></td>
                                    <td class="px-6 py-4">
                                        <?php if($participant['status'] === 'validated'): ?>
                                            <span class="bg-green-100 text-green-800 text-xs font-medium px-2.5 py-0.5 rounded-full">
                                                Validated
                                            </span>
                                        <?php elseif($participant['status'] === 'pending'): ?>
                                            <span class="bg-yellow-100 text-yellow-800 text-xs font-medium px-2.5 py-0.5 rounded-full">
                                                Pending
                                            </span>
                                        <?php else: ?>
                                            <span class="bg-red-100 text-red-800 text-xs font-medium px-2.5 py-0.5 rounded-full">
                                                Canceled
                                            </span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="px-6 py-4">
                                        <?php if($participant['attended']): ?>
                                            <span class="bg-green-100 text-green-800 text-xs font-medium px-2.5 py-0.5 rounded-full">
                                                Yes
                                            </span>
                                        <?php else: ?>
                                            <span class="bg-gray-100 text-gray-800 text-xs font-medium px-2.5 py-0.5 rounded-full">
                                                No
                                            </span>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
                <?php endif; ?>
            </article>

            <!-- Sidebar -->
            <aside class="lg:w-1/3">
                <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100 sticky top-4">
                    <!-- Event Status -->
                    <div class="mb-4">
                        <?php if ($eventEnded): ?>
                            <span class="inline-block bg-gray-100 text-gray-800 text-sm font-medium px-3 py-1.5 rounded-lg">
                                Event Done
                            </span>
                        <?php elseif ($registrationClosed): ?>
                            <span class="inline-block bg-red-100 text-red-800 text-sm font-medium px-3 py-1.5 rounded-lg">
                                Registration Closed
                            </span>
                        <?php else: ?>
                            <span class="inline-block bg-green-100 text-green-800 text-sm font-medium px-3 py-1.5 rounded-lg">
                                Registration Open
                            </span>
                        <?php endif; ?>
                    </div>

                    <!-- Event Details -->
                    <div class="mb-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Event Details</h3>

                        <!-- Date and Time -->
                        <div class="flex items-start gap-4 mb-4">
                            <div class="text-blue-600">
                                <svg class="w-5 h-5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 20">
                                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 1v3m5-3v3m5-3v3M1 7h18M5 11h10M2 3h16a1 1 0 0 1 1 1v14a1 1 0 0 1-1 1H2a1 1 0 0 1-1-1V4a1 1 0 0 1 1-1Z"/>
                                </svg>
                            </div>
                            <div>
                                <p class="font-medium text-gray-900">Date & Time</p>
                                <p class="text-sm text-gray-600">
                                    <?= date('l, d F Y', strtotime($data['event']['start_date'])) ?>
                                </p>
                                <p class="text-sm text-gray-600">
                                    <?= date('H:i', strtotime($data['event']['start_time'])) ?> -
                                    <?= date('H:i', strtotime($data['event']['end_time'])) ?> WIB
                                </p>
                                <?php if ($data['event']['start_date'] !== $data['event']['end_date']): ?>
                                <p class="text-xs text-gray-500 mt-1">
                                    Until <?= date('l, d F Y', strtotime($data['event']['end_date'])) ?>
                                </p>
                                <?php endif; ?>
                            </div>
                        </div>

                        <!-- Location -->
                        <div class="flex items-start gap-4 mb-4">
                            <div class="text-blue-600">
                                <svg class="w-5 h-5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 17 21">
                                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12a3 3 0 1 0 0-6 3 3 0 0 0 0 6Z"/>
                                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.8 12.938h-.01a7 7 0 1 0-11.465.144h-.016l.141.171c.1.127.2.25.312.367l4.12 4.53a1 1 0 0 0 1.475 0l4.12-4.53c.112-.116.212-.24.313-.367l.141-.17Z"/>
                                </svg>
                            </div>
                            <div>
                                <p class="font-medium text-gray-900">Location</p>
                                <p class="text-sm text-gray-600"><?= $data['event']['location_name'] ?></p>
                                <?php if (!empty($data['event']['location_address'])): ?>
                                <p class="text-sm text-gray-600"><?= $data['event']['location_address'] ?></p>
                                <?php endif; ?>
                                <?php if (!empty($data['event']['location_link'])): ?>
                                <a href="<?= $data['event']['location_link'] ?>" target="_blank" class="text-xs text-blue-600 hover:underline mt-1 inline-block">
                                    View on map
                                </a>
                                <?php endif; ?>
                            </div>
                        </div>

                        <!-- Quota -->
                        <div class="flex items-start gap-4 mb-4">
                            <div class="text-blue-600">
                                <svg class="w-5 h-5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 18">
                                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 3a3 3 0 1 1-1.614 5.53M15 12a4 4 0 0 1 4 4v1h-3.5M10 4.5a3.5 3.5 0 1 1-7 0 3.5 3.5 0 0 1 7 0ZM5 11h3a4 4 0 0 1 4 4v2H1v-2a4 4 0 0 1 4-4Z"/>
                                </svg>
                            </div>
                            <div>
                                <p class="font-medium text-gray-900">Quota</p>
                                <?php if (isset($data['quotaInfo']) && $data['quotaInfo']): ?>
                                <p class="text-sm text-gray-600">
                                    <?= $data['quotaInfo']['registered'] ?> / <?= $data['quotaInfo']['quota'] ?> participants
                                </p>
                                <div class="w-full bg-gray-200 rounded-full h-2.5 mt-2">
                                    <div class="bg-blue-600 h-2.5 rounded-full" style="width: <?= ($data['quotaInfo']['registered'] / $data['quotaInfo']['quota']) * 100 ?>%"></div>
                                </div>
                                <p class="text-xs text-gray-500 mt-1">
                                    <?= $data['quotaInfo']['available'] ?> spots remaining
                                </p>
                                <?php else: ?>
                                <p class="text-sm text-gray-600"><?= $data['event']['quota'] ?> participants</p>
                                <?php endif; ?>
                            </div>
                        </div>

                        <!-- Registration Deadline -->
                        <div class="flex items-start gap-4">
                            <div class="text-blue-600">
                                <svg class="w-5 h-5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 20">
                                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 10 5.5 5.5m4.5 4.5 4.5 4.5m-4.5-4.5 4.5-4.5m-4.5 4.5L5.5 15M19 10a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/>
                                </svg>
                            </div>
                            <div>
                                <p class="font-medium text-gray-900">Registration Deadline</p>
                                <p class="text-sm text-gray-600">
                                    <?= date('l, d F Y', strtotime($data['event']['registration_deadline'])) ?>
                                </p>
                                <?php if (!$registrationClosed): ?>
                                <p class="text-xs text-gray-500 mt-1">
                                    <?= ceil((strtotime($data['event']['registration_deadline']) - time()) / (60 * 60 * 24)) ?> days remaining
                                </p>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>

                    <!-- Registration Button - Only show for non-admin users -->
                    <?php if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin'): ?>
                        <?php if (isset($_SESSION['user'])): ?>
                            <?php if ($data['isRegistered']): ?>
                                <!-- Already registered -->
                                <div class="mb-4">
                                    <button disabled type="button" class="w-full text-white bg-gray-400 focus:ring-4 focus:outline-none focus:ring-gray-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center">
                                        Already Registered
                                    </button>
                                    <a href="<?= BASE_URL ?>/dashboard/event" class="mt-2 w-full text-center text-sm text-blue-600 hover:underline inline-block">
                                        View My Registration
                                    </a>
                                </div>
                            <?php elseif (!$registrationClosed && !$eventEnded &&
                                (
                                    !isset($data['quotaInfo']) || // If quotaInfo is not set at all, this part allows registration
                                    (is_array($data['quotaInfo']) && isset($data['quotaInfo']['available']) && $data['quotaInfo']['available'] > 0) // Otherwise, if it is set, it must be an array with available spots
                                )
                            ): ?>
                                <!-- Can register -->
                                <form action="<?= BASE_URL ?>/registration/store/<?= $data['event']['id'] ?>" method="post" class="mb-4">
                                    <button type="submit" class="w-full text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center">
                                        Register
                                    </button>
                                </form>
                            <?php else: ?>
                                <!-- Cannot register -->
                                <div class="mb-4">
                                    <button disabled type="button" class="w-full text-white bg-gray-400 focus:ring-4 focus:outline-none focus:ring-gray-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center">
                                        <?php if ($eventEnded): ?>
                                            Event Done
                                        <?php elseif ($registrationClosed): ?>
                                            Registration Closed
                                        <?php else: ?>
                                            No Spots Available
                                        <?php endif; ?>
                                    </button>
                                </div>
                            <?php endif; ?>
                        <?php else: ?>
                            <!-- Not logged in -->
                            <div class="mb-4">
                                <a href="<?= BASE_URL ?>/login" class="w-full text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center inline-block">
                                    Login to Register
                                </a>
                                <p class="text-xs text-center mt-2 text-gray-500">You need to login first to register</p>
                            </div>
                        <?php endif; ?>
                    <?php endif; ?>
                </div>
            </aside>
        </div>
    </div>
</section>
