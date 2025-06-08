<section>
    <!-- Header -->
    <div class="mb-4">
        <div>
            <h1 class="text-2xl font-semibold text-blue-900 mb-1">Event Management</h1>
            <p class="text-blue-600">Plan and manage your events efficiently.</p>
        </div>
        <div class="mt-4 flex justify-end">
            <a href="<?= BASE_URL ?>/event/create" class="text-gray-900 bg-white/70 border border-gray-300 focus:outline-none hover:bg-gray-100 focus:ring-4 focus:ring-gray-100 font-medium rounded-xl text-sm px-5 py-2.5">
                Create New Event
            </a>
        </div>
    </div>

    <!-- Table -->
    <section class=" bg-white/70 backdrop-blur-sm rounded-2xl p-6 border border-blue-100">
        <table id="selection-table">
            <thead>
                <tr>
                <th>
                    <span class="flex items-center">
                        #
                    </span>
                </th>
                    <th>
                    <span class="flex items-center">
                        Thumbnail
                    </span>
                    </th>
                <th>
                    <span class="flex items-center">
                        Title
                        <svg class="w-4 h-4 ms-1" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m8 15 4 4 4-4m0-6-4-4-4 4"/>
                        </svg>
                    </span>
                </th>
                <th>
                    <span class="flex items-center">
                        Speaker
                        <svg class="w-4 h-4 ms-1" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m8 15 4 4 4-4m0-6-4-4-4 4"/>
                        </svg>
                    </span>
                </th>
                <th>
                    <span class="flex items-center">
                        Date & Time
                        <svg class="w-4 h-4 ms-1" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m8 15 4 4 4-4m0-6-4-4-4 4"/>
                        </svg>
                    </span>
                </th>
                <th>
                    <span class="flex items-center">
                        Location
                        <svg class="w-4 h-4 ms-1" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m8 15 4 4 4-4m0-6-4-4-4 4"/>
                        </svg>
                    </span>
                </th>
                <th>
                    <span class="flex items-center">
                        Quota
                        <svg class="w-4 h-4 ms-1" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m8 15 4 4 4-4m0-6-4-4-4 4"/>
                        </svg>
                    </span>
                </th>
                <th>
                    <span class="flex items-center">
                        Category
                        <svg class="w-4 h-4 ms-1" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m8 15 4 4 4-4m0-6-4-4-4 4"/>
                        </svg>
                    </span>
                </th>
                <th>
                    <span class="flex items-center">
                        Status
                        <svg class="w-4 h-4 ms-1" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m8 15 4 4 4-4m0-6-4-4-4 4"/>
                        </svg>
                    </span>
                </th>
                <th>
                    <span class="sr-only">
                        Actions
                    </span>
                </th>
            </tr>
            </thead>
            <tbody>
                <?php foreach ($data['events'] as $index => $event): ?>
                <tr class="hover:bg-blue-50/50">
                    <td class="font-medium text-gray-900 whitespace-nowrap dark:text-white"><?=$index + 1?></td>
                    <td>
                        <img class="w-full h-full rounded-xl" src="<?=BASE_URL?>/images/events/<?=$event['thumbnail']?>"  alt="<?=$event['title']?>"/>
                    </td>
                    <td>
                        <span class="line-clamp-2">
                             <?=$event['title']?>
                        </span>
                    </td>
                    <td>
                        <span class="line-clamp-2">
                            <?=$event['speaker']?>
                        </span>
                    </td>
                    <td>
                        <?= date('d M Y, ', strtotime($event['start_date'])) ?>
                        <?= date('H:i', strtotime($event['start_time'])) ?>
                    </td>
                    <td><?=$event['location_name']?></td>
                    <td><?=$event['quota']?></td>
                    <td><?=$event['category_name']?></td>
                    <td>
                        <?php if ($event['is_published']): ?>
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">Published</span>
                        <?php else: ?>
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">Unpublished</span>
                        <?php endif; ?>
                    </td>
                    <td>
                        <button data-dropdown-offset-skidding="-68" id="dropdownMenuIconHorizontalButton-<?=$index?>" data-dropdown-toggle="dropdownDotsHorizontal-<?=$index?>" class="inline-flex cursor-pointer items-center p-2 text-sm font-medium text-center text-gray-900 rounded-lg hover:bg-white focus:outline-none" type="button">
                            <svg class="w-5 h-5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 16 3">
                                <path d="M2 0a1.5 1.5 0 1 1 0 3 1.5 1.5 0 0 1 0-3Zm6.041 0a1.5 1.5 0 1 1 0 3 1.5 1.5 0 0 1 0-3ZM14 0a1.5 1.5 0 1 1 0 3 1.5 1.5 0 0 1 0-3Z"/>
                            </svg>
                        </button>
                    </td>
                    <div id="dropdownDotsHorizontal-<?=$index?>" class="z-10 hidden bg-white divide-y divide-gray-100 rounded-lg shadow-sm w-44 dark:bg-gray-700 dark:divide-gray-600">
                        <ul class="py-1" aria-labelledby="dropdownMenuIconHorizontalButton-<?=$index?>">
                            <li>
                                <a href="<?=BASE_URL?>/event/show/<?=$event['id']?>"
                                   class="flex items-center px-4 py-2 text-sm font-semibold text-gray-700 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-900">
                                    <svg class="mr-4 h-4 w-4 text-gray-900 dark:text-gray-300"  xmlns="http://www.w3.org/2000/svg" viewBox="0 0 576 512">
                                        <path d="M288 80c-65.2 0-118.8 29.6-159.9 67.7C89.6 183.5 63 226 49.4 256c13.6 30 40.2 72.5 78.6 108.3C169.2 402.4 222.8 432 288 432s118.8-29.6 159.9-67.7C486.4 328.5 513 286 526.6 256c-13.6-30-40.2-72.5-78.6-108.3C406.8 109.6 353.2 80 288 80zM95.4 112.6C142.5 68.8 207.2 32 288 32s145.5 36.8 192.6 80.6c46.8 43.5 78.1 95.4 93 131.1c3.3 7.9 3.3 16.7 0 24.6c-14.9 35.7-46.2 87.7-93 131.1C433.5 443.2 368.8 480 288 480s-145.5-36.8-192.6-80.6C48.6 356 17.3 304 2.5 268.3c-3.3-7.9-3.3-16.7 0-24.6C17.3 208 48.6 156 95.4 112.6zM288 336c44.2 0 80-35.8 80-80s-35.8-80-80-80c-.7 0-1.3 0-2 0c1.3 5.1 2 10.5 2 16c0 35.3-28.7 64-64 64c-5.5 0-10.9-.7-16-2c0 .7 0 1.3 0 2c0 44.2 35.8 80 80 80zm0-208a128 128 0 1 1 0 256 128 128 0 1 1 0-256z"/>
                                    </svg>
                                    View Details
                                </a>
                            </li>
                            <li>
                                <a href="<?=BASE_URL?>/event/edit/<?=$event['id']?>"
                                   class="flex items-center px-4 py-2 text-sm font-semibold text-gray-700 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-900">
                                    <svg class="mr-4 h-4 w-4 text-gray-900 dark:text-gray-300" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512">
                                        <path d="M441 58.9L453.1 71c9.4 9.4 9.4 24.6 0 33.9L424 134.1 377.9 88 407 58.9c9.4-9.4 24.6-9.4 33.9 0zM209.8 256.2L344 121.9 390.1 168 255.8 302.2c-2.9 2.9-6.5 5-10.4 6.1l-58.5 16.7 16.7-58.5c1.1-3.9 3.2-7.5 6.1-10.4zM373.1 25L175.8 222.2c-8.7 8.7-15 19.4-18.3 31.1l-28.6 100c-2.4 8.4-.1 17.4 6.1 23.6s15.2 8.5 23.6 6.1l100-28.6c11.8-3.4 22.5-9.7 31.1-18.3L487 138.9c28.1-28.1 28.1-73.7 0-101.8L474.9 25C446.8-3.1 401.2-3.1 373.1 25zM88 64C39.4 64 0 103.4 0 152L0 424c0 48.6 39.4 88 88 88l272 0c48.6 0 88-39.4 88-88l0-112c0-13.3-10.7-24-24-24s-24 10.7-24 24l0 112c0 22.1-17.9 40-40 40L88 464c-22.1 0-40-17.9-40-40l0-272c0-22.1 17.9-40 40-40l112 0c13.3 0 24-10.7 24-24s-10.7-24-24-24L88 64z"/>
                                    </svg>
                                    Edit Event
                                </a>
                            </li>
                        </ul>
                        <div class="py-1">
                            <form action="<?= BASE_URL ?>/event/destroy/<?= $event['id'] ?>" method="post" onsubmit="return confirm('Are you sure you want to delete this event?');">
                                <button type="submit"
                                        class="flex items-center justify-center w-full cursor-pointer px-4 py-2 text-sm font-semibold text-red-500 hover:bg-gray-100 dark:hover:bg-gray-900">
                                    Delete
                                </button>
                            </form>
                        </div>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </section>
</section>
