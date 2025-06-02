<?php
use Core\Validator;
?>
<section>
    <!-- Header -->
    <div class="mb-4">
        <div>
            <h1 class="text-2xl font-semibold text-blue-900 mb-1">Create Event</h1>
            <p class="text-blue-600">Plan and set up a new event.</p>
        </div>
        <div class="mt-4 max-w-6xl mx-auto flex justify-end ">
            <a href="<?= BASE_URL ?>/dashboard/event" class="text-gray-900 bg-white/70 border border-gray-300 focus:outline-none hover:bg-gray-100 focus:ring-4 focus:ring-gray-100 font-medium rounded-xl text-sm px-5 py-2.5">
                Back
            </a>
        </div>
    </div>

    <!-- Form -->
        <section class="bg-white/70 backdrop-blur-sm rounded-2xl p-6 border border-blue-100 mx-auto max-w-6xl">
            <form action="<?= BASE_URL ?>/event/store" method="post" enctype="multipart/form-data" class="space-y-6">
                <div class="grid gap-4 grid-cols-2 sm:grid-cols-3 md:grid-cols-4 sm:gap-6">
                    <div class="w-full">
                        <label for="title" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Title</label>
                        <input type="text" name="title" id="title"
                               value="<?= old('title') ?>"
                               class="bg-gray-50 border <?= Validator::hasError('title') ? 'border-red-500' : 'border-gray-300' ?> text-gray-900 text-sm rounded-lg focus:ring-blue-600 focus:border-blue-600 block w-full p-2.5"
                               placeholder="Event title" required autofocus>
                        <?= error_field('title') ?>
                    </div>
                    <div class="w-full">
                        <label for="speaker" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Speaker</label>
                        <input type="text" name="speaker" id="speaker"
                               value="<?= old('speaker') ?>"
                               class="bg-gray-50 border <?= Validator::hasError('speaker') ? 'border-red-500' : 'border-gray-300' ?> text-gray-900 text-sm rounded-lg focus:ring-blue-600 focus:border-blue-600 block w-full p-2.5"
                               placeholder="Speaker name" required>
                        <?= error_field('speaker') ?>
                    </div>
                    <div class="w-full">
                        <label for="quota" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Quota</label>
                        <input type="number" name="quota" id="quota" min="1"
                               value="<?= old('quota') ?>"
                               class="bg-gray-50 border <?= Validator::hasError('quota') ? 'border-red-500' : 'border-gray-300' ?> text-gray-900 text-sm rounded-lg focus:ring-blue-600 focus:border-blue-600 block w-full p-2.5"
                               placeholder="Quota" required>
                        <?= error_field('quota') ?>
                    </div>
                    <div>
                        <label for="category" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Category</label>
                        <select id="category" name="category_id"
                                class="bg-gray-50 border <?= Validator::hasError('category_id') ? 'border-red-500' : 'border-gray-300' ?> text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" required>
                            <option value="">Select category</option>
                            <?php foreach ($data['categories'] as $category): ?>
                                <option value="<?= $category['id'] ?>" <?= old(('category_id') == $category['id'])? 'selected' : '' ?>><?=$category['name']?></option>
                            <?php endforeach;?>
                        </select>
                        <?= error_field('category_id') ?>
                    </div>
                    <div class="w-full">
                        <label for="start_date" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Start Date</label>
                        <div class="relative max-w-sm">
                            <div class="absolute inset-y-0 start-0 flex items-center ps-3.5 pointer-events-none">
                                <svg class="w-4 h-4 text-gray-500 dark:text-gray-400" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M20 4a2 2 0 0 0-2-2h-2V1a1 1 0 0 0-2 0v1h-3V1a1 1 0 0 0-2 0v1H6V1a1 1 0 0 0-2 0v1H2a2 2 0 0 0-2 2v2h20V4ZM0 18a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2V8H0v10Zm5-8h10a1 1 0 0 1 0 2H5a1 1 0 0 1 0-2Z"/>
                                </svg>
                            </div>
                            <input datepicker datepicker-buttons datepicker-autoselect-today id="start_date" name="start_date"
                                   value="<?= old('start_date') ?>"
                                   type="text" class="bg-gray-50 border <?= Validator::hasError('start_date') ? 'border-red-500' : 'border-gray-300' ?> text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full ps-10 p-2.5" placeholder="Select date" required>
                        </div>
                        <?= error_field('start_date') ?>
                    </div>
                    <div class="w-full">
                        <label for="start_time" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Start Time</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 end-0 top-0 flex items-center pe-3.5 pointer-events-none">
                                <svg class="w-4 h-4 text-gray-500 dark:text-gray-400" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 24 24">
                                    <path fill-rule="evenodd" d="M2 12C2 6.477 6.477 2 12 2s10 4.477 10 10-4.477 10-10 10S2 17.523 2 12Zm11-4a1 1 0 1 0-2 0v4a1 1 0 0 0 .293.707l3 3a1 1 0 0 0 1.414-1.414L13 11.586V8Z" clip-rule="evenodd"/>
                                </svg>
                            </div>
                            <input type="time" id="start_time" name="start_time"
                                   value="<?= old('start_time') ?: '08:00' ?>"
                                   class="bg-gray-50 border leading-none <?= Validator::hasError('start_time') ? 'border-red-500' : 'border-gray-300' ?> text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" min="07:00" max="22:00" required />
                        </div>
                        <?= error_field('start_time') ?>
                    </div>
                    <div class="w-full">
                        <label for="end_date" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">End Date</label>
                        <div class="relative max-w-sm">
                            <div class="absolute inset-y-0 start-0 flex items-center ps-3.5 pointer-events-none">
                                <svg class="w-4 h-4 text-gray-500 dark:text-gray-400" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M20 4a2 2 0 0 0-2-2h-2V1a1 1 0 0 0-2 0v1h-3V1a1 1 0 0 0-2 0v1H6V1a1 1 0 0 0-2 0v1H2a2 2 0 0 0-2 2v2h20V4ZM0 18a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2V8H0v10Zm5-8h10a1 1 0 0 1 0 2H5a1 1 0 0 1 0-2Z"/>
                                </svg>
                            </div>
                            <input datepicker datepicker-buttons datepicker-autoselect-today id="end_date" name="end_date"
                                   value="<?= old('end_date') ?>"
                                   type="text" class="bg-gray-50 border <?= Validator::hasError('end_date') ? 'border-red-500' : 'border-gray-300' ?> text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full ps-10 p-2.5" placeholder="Select date" required>
                        </div>
                        <?= error_field('end_date') ?>
                    </div>
                    <div class="w-full">
                        <label for="end_time" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">End Time</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 end-0 top-0 flex items-center pe-3.5 pointer-events-none">
                                <svg class="w-4 h-4 text-gray-500 dark:text-gray-400" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 24 24">
                                    <path fill-rule="evenodd" d="M2 12C2 6.477 6.477 2 12 2s10 4.477 10 10-4.477 10-10 10S2 17.523 2 12Zm11-4a1 1 0 1 0-2 0v4a1 1 0 0 0 .293.707l3 3a1 1 0 0 0 1.414-1.414L13 11.586V8Z" clip-rule="evenodd"/>
                                </svg>
                            </div>
                            <input type="time" id="end_time" name="end_time"
                                   value="<?= old('end_time') ?: '16:00' ?>"
                                   class="bg-gray-50 border leading-none <?= Validator::hasError('end_time') ? 'border-red-500' : 'border-gray-300' ?> text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" min="07:00" max="22:00" required />
                        </div>
                        <?= error_field('end_time') ?>
                    </div>
                    <div class="w-full">
                        <label for="registration_deadline" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Registration Deadline</label>
                        <div class="relative max-w-sm">
                            <div class="absolute inset-y-0 start-0 flex items-center ps-3.5 pointer-events-none">
                                <svg class="w-4 h-4 text-gray-500 dark:text-gray-400" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M20 4a2 2 0 0 0-2-2h-2V1a1 1 0 0 0-2 0v1h-3V1a1 1 0 0 0-2 0v1H6V1a1 1 0 0 0-2 0v1H2a2 2 0 0 0-2 2v2h20V4ZM0 18a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2V8H0v10Zm5-8h10a1 1 0 0 1 0 2H5a1 1 0 0 1 0-2Z"/>
                                </svg>
                            </div>
                            <input datepicker datepicker-buttons datepicker-autoselect-today id="registration_deadline" name="registration_deadline"
                                   value="<?= old('registration_deadline') ?>"
                                   type="text" class="bg-gray-50 border <?= Validator::hasError('registration_deadline') ? 'border-red-500' : 'border-gray-300' ?> text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full ps-10 p-2.5" placeholder="Select date" required>
                        </div>
                        <?= error_field('registration_deadline') ?>
                    </div>
                    <div class="w-full">
                        <label for="location_name" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Location Name</label>
                        <input type="text" name="location_name" id="location_name"
                               value="<?= old('location_name') ?>"
                               class="bg-gray-50 border <?= Validator::hasError('location_name') ? 'border-red-500' : 'border-gray-300' ?> text-gray-900 text-sm rounded-lg focus:ring-blue-600 focus:border-blue-600 block w-full p-2.5"
                               placeholder="Location name" required>
                        <?= error_field('location_name') ?>
                    </div>
                    <div class="w-full">
                        <label for="location_address" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Location Address</label>
                        <input type="text" name="location_address" id="location_address"
                               value="<?= old('location_address') ?>"
                               class="bg-gray-50 border <?= Validator::hasError('location_address') ? 'border-red-500' : 'border-gray-300' ?> text-gray-900 text-sm rounded-lg focus:ring-blue-600 focus:border-blue-600 block w-full p-2.5"
                               placeholder="Location address">
                        <?= error_field('location_address') ?>
                    </div>
                    <div class="w-full">
                        <label for="location_link" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Location Link</label>
                        <input type="text" name="location_link" id="location_link"
                               value="<?= old('location_link') ?>"
                               class="bg-gray-50 border <?= Validator::hasError('location_link') ? 'border-red-500' : 'border-gray-300' ?> text-gray-900 text-sm rounded-lg focus:ring-blue-600 focus:border-blue-600 block w-full p-2.5"
                               placeholder="Location link">
                        <?= error_field('location_link') ?>
                    </div>
                    <div class="w-full col-span-full sm:col-span-2">
                        <label for="short_description" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Description</label>
                        <textarea id="short_description" name="short_description" rows="12" class="block p-2.5 w-full text-sm text-gray-900 bg-gray-50 rounded-lg border <?= Validator::hasError('short_description') ? 'border-red-500' : 'border-gray-300' ?> focus:ring-blue-500 focus:border-blue-500" placeholder="..."><?= old('short_description') ?></textarea>
                        <?= error_field('short_description') ?>
                    </div>

                    <div class="w-full col-span-full sm:col-span-1">
                        <label for="thumbnail" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Thumbnail</label>
                        <div class="flex items-center justify-center w-full">
                            <label for="thumbnail" class="flex flex-col items-center justify-center w-full h-64 border-2  <?= Validator::hasError('thumbnail') ? 'border-red-500' : 'border-gray-300' ?> border-dashed rounded-lg cursor-pointer bg-gray-50">
                                <div class="flex flex-col items-center justify-center pt-5 pb-6" id="thumbnail-preview-container">
                                    <img id="thumbnail-preview" src="" alt="Preview" class="hidden mb-4 w-auto h-40 object-contain rounded-lg" />
                                    <svg id="thumbnail-placeholder" class="w-8 h-8 mb-4 text-gray-500 dark:text-gray-400" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 16">
                                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 13h3a3 3 0 0 0 0-6h-.025A5.56 5.56 0 0 0 16 6.5 5.5 5.5 0 0 0 5.207 5.021C5.137 5.017 5.071 5 5 5a4 4 0 0 0 0 8h2.167M10 15V6m0 0L8 8m2-2 2 2"/>
                                    </svg>
                                    <p class="mb-2 text-sm text-gray-500 dark:text-gray-400"><span class="font-semibold">Click to upload</span> or drag and drop</p>
                                    <p class="text-xs text-gray-500 dark:text-gray-400">SVG, PNG, JPG or JPEG</p>
                                </div>
                                <input id="thumbnail" name="thumbnail" type="file" accept="image/*" class="hidden" onchange="previewThumbnail(event)" />
                            </label>
                        </div>
                        <?= error_field('thumbnail') ?>
                    </div>
                    <div class="w-full">
                        <label for="is_published" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Publish</label>
                        <select id="is_published" name="is_published"
                                class="bg-gray-50 border <?= Validator::hasError('is_published') ? 'border-red-500' : 'border-gray-300' ?> text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5"
                                required>
                            <option value="">Select publish status</option>
                            <option value="n" <?= old('is_published') === 'n' ? 'selected' : '' ?>>Draft</option>
                            <option value="y" <?= old('is_published') === 'y' ? 'selected' : '' ?>>Publish</option>
                        </select>
                        <?= error_field('is_published') ?>
                    </div>
                    <div class="col-span-full">
                        <label for="full_description" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Content</label>
                        <script>
                            tinymce.init({
                                selector: '#full_description',
                                plugins: [
                                    'anchor', 'autolink', 'charmap', 'codesample', 'emoticons', 'image', 'link', 'lists', 'media', 'searchreplace', 'table', 'visualblocks', 'wordcount',
                                ],
                                toolbar: 'undo redo | blocks fontfamily fontsize | bold italic underline strikethrough | link image media table mergetags | addcomment showcomments | spellcheckdialog a11ycheck typography | align lineheight | checklist numlist bullist indent outdent | emoticons charmap | removeformat',
                                tinycomments_mode: 'embedded',
                                tinycomments_author: 'Author name',
                                mergetags_list: [
                                    { value: 'First.Name', title: 'First Name' },
                                    { value: 'Email', title: 'Email' },
                                ]
                            });
                        </script>
                        <textarea id="full_description" name="full_description" class="<?= Validator::hasError('full_description') ? 'border-red-500' : '' ?>"><?= old('full_description') ?></textarea>
                        <?= error_field('full_description') ?>
                    </div>
                </div>
                <button type="submit" class="inline-flex cursor-pointer items-center px-5 py-2.5 mt-4 sm:mt-6 text-sm font-medium text-center text-white bg-blue-700 rounded-lg focus:ring-4 focus:ring-blue-200 dark:focus:ring-blue-900 hover:bg-blue-800">
                    Create Event
                </button>
            </form>
        </section>
</section>
<script>
    function previewThumbnail(event) {
        const input = event.target;
        const preview = document.getElementById('thumbnail-preview');
        const placeholder = document.getElementById('thumbnail-placeholder');
        if (input.files && input.files[0]) {
            const file = input.files[0];
            if (file.type.startsWith('image/')) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    preview.src = e.target.result;
                    preview.classList.remove('hidden');
                    placeholder.classList.add('hidden');
                };
                reader.readAsDataURL(file);
            } else {
                preview.src = '';
                preview.classList.add('hidden');
                placeholder.classList.remove('hidden');
            }
        } else {
            preview.src = '';
            preview.classList.add('hidden');
            placeholder.classList.remove('hidden');
        }
    }

</script>
