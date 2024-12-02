<x-layout>
    <!-- Main Section -->
    <section class="bg-white dark:bg-gray-900 min-h-screen">
        <div class="py-8 lg:py-16 px-4 mx-auto max-w-screen-md">

            <!-- Logo at the top -->
            <div class="text-center mb-8">
                <img src="https://integracehealth.com/wp-content/uploads/2023/06/New-Project-12-1.png" alt="Logo" class="w-72 mx-auto">
            </div>

            <!-- Campaign Form -->
            <form action="{{route("home.submit")}}" method="POST" class="space-y-8" enctype="multipart/form-data" id="campaign-form">
                @csrf

                @error('common')
                <div class="mb-4 p-4 bg-red-100 text-red-800 rounded-lg">
                    <span class="text-xs font-semibold text-red-700 dark:text-red-500">{{ $message }}</span>
                </div>
                @enderror

                <!-- Full Name and Email Address -->
                <div class="grid gap-4 md:grid-cols-2">
                    <div>
                        <label for="name" class="block mb-2 text-sm font-medium text-gray-900 dark:text-gray-300">Full Name</label>
                        <input type="text" id="name" name="name" class="shadow-sm bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white" placeholder="Dr. ABC" required>
                        @error('name')<span class="text-xs font-semibold text-red-700 dark:text-red-500">{{ $message }}</span>@enderror
                    </div>

                    <div>
                        <label for="email" class="block mb-2 text-sm font-medium text-gray-900 dark:text-gray-300">Email Address</label>
                        <input type="email" id="email" name="email" class="shadow-sm bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white" placeholder="abc@example.com" required>
                        @error('email')<span class="text-xs font-semibold text-red-700 dark:text-red-500">{{ $message }}</span>@enderror
                    </div>
                </div>

                <!-- Phone Number and Credentials -->
                <div class="grid gap-4 md:grid-cols-2">
                    <div>
                        <label for="phone" class="block mb-2 text-sm font-medium text-gray-900 dark:text-gray-300">Phone Number</label>
                        <input type="tel" id="phone" name="phone" class="shadow-sm bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white" placeholder="1234567890" required>
                        @error('phone')<span class="text-xs font-semibold text-red-700 dark:text-red-500">{{ $message }}</span>@enderror
                    </div>

                    <div>
                        <label for="credentials" class="block mb-2 text-sm font-medium text-gray-900 dark:text-gray-300">Credentials</label>
                        <input type="text" id="credentials" name="credentials" class="shadow-sm bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white" placeholder="MBBS / MD" required>
                        @error('credentials')<span class="text-xs font-semibold text-red-700 dark:text-red-500">{{ $message }}</span>@enderror
                    </div>
                </div>

                <!-- Language Selection Dropdown -->
                <div>
                    <label for="language" class="block mb-2 text-sm font-medium text-gray-900 dark:text-gray-300">Languages</label>
                    <select id="language" name="language" class="block w-full bg-gray-50 dark:bg-gray-700 text-gray-900 dark:text-white shadow-sm border border-gray-300 dark:border-gray-600 focus:ring-primary-500 focus:border-primary-500 rounded-lg p-2.5">
                        @foreach ($languages as $lang)
                            <option value="{{$lang}}">{{$lang}}</option>
                        @endforeach
                    </select>
                    @error('language')<span class="text-xs font-semibold text-red-700 dark:text-red-500">{{ $message }}</span>@enderror
                </div>

                <!-- Image Upload Section -->
                <div class="flex flex-col items-center justify-center w-full" id="upload-section">
                    <label for="profile" class="flex flex-col items-center justify-center w-full h-44 border-2 border-gray-300 border-dashed rounded-lg cursor-pointer bg-gray-50 dark:hover:bg-gray-800 dark:bg-gray-700 hover:bg-gray-100 dark:border-gray-600">
                        <div class="flex flex-col items-center justify-center pt-5 pb-6">
                            <svg class="w-8 h-8 mb-4 text-gray-500 dark:text-gray-400" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 16">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 13h3a3 3 0 0 0 0-6h-.025A5.56 5.56 0 0 0 16 6.5 5.5 5.5 0 0 0 5.207 5.021C5.137 5.017 5.071 5 5 5a4 4 0 0 0 0 8h2.167M10 15V6m0 0L8 8m2-2 2 2"/>
                            </svg>
                            <p class="mb-2 text-sm text-gray-500 dark:text-gray-400"><span class="font-semibold">Click to upload</span> or drag and drop</p>
                            <p class="text-xs text-gray-500 dark:text-gray-400">Profile Picture (PNG, JPG only.)</p>
                        </div>
                        <input type="file" name="profile" id="profile" class="hidden" accept="image/*" />
                    </label>
                </div>
                @error('profile')<span class="text-xs font-semibold text-red-700 dark:text-red-500">{{ $message }}</span>@enderror

                <!-- Preview Section -->
                <div class="mt-4 hidden flex flex-col items-center" id="preview-section">
                    <div class="relative">
                        <img id="preview-image" class="w-32 h-32 rounded-full object-cover transition-all duration-300" alt="Profile Preview" src="" />
                        <button type="button" id="remove-image" class="absolute -top-2 -right-2 bg-primary-700 text-white rounded-full p-1 hover:bg-primary-800">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>
                </div>

                <!-- Terms and Conditions -->
                <div class="mt-6 text-center text-sm text-gray-500 dark:text-gray-400">
                    <p>By submitting this form, you agree to our <strong>Terms and Conditions</strong>.</p>
                </div>

                <!-- Full Name and Email Address -->
                <div class="grid gap-4 md:grid-cols-2">
                    <div>
                        <label for="fso_name" class="block mb-2 text-sm font-medium text-gray-900 dark:text-gray-300">FSO Name</label>
                        <input type="text" id="fso_name" name="fso_name" class="shadow-sm bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white" placeholder="FSO Name" required>
                        @error('fso_name')<span class="text-xs font-semibold text-red-700 dark:text-red-500">{{ $message }}</span>@enderror
                    </div>

                    <div>
                        <label for="fso_emp_id" class="block mb-2 text-sm font-medium text-gray-900 dark:text-gray-300">Employee ID</label>
                        <input type="text" id="fso_emp_id" name="fso_emp_id" class="shadow-sm bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white" placeholder="FSO EPM ID" required>
                        @error('fso_emp_id')<span class="text-xs font-semibold text-red-700 dark:text-red-500">{{ $message }}</span>@enderror
                    </div>
                </div>

                <!-- Buttons -->
                <div class="flex space-x-4">
                    <button type="submit" class="py-3 px-5 w-full font-medium text-center text-white rounded-lg bg-primary-700 hover:bg-primary-800 focus:ring-4 focus:outline-none focus:ring-primary-300">Submit</button>
                    <button type="reset" id="clear-button" class="py-3 px-5 w-full font-medium text-center text-gray-700 bg-gray-200 hover:bg-gray-300 focus:ring-4 focus:outline-none focus:ring-gray-300 rounded-lg">Clear</button>
                </div>
            </form>
        </div>
    </section>

    <!-- Loader -->
    <div id="loader" class="hidden absolute top-0 left-0 w-full h-full bg-gray-200 bg-opacity-50 flex items-center justify-center z-50">
        <div class="animate-spin rounded-full border-t-4 border-primary-600 w-16 h-16"></div>
    </div>

    <!-- JavaScript -->
    <script>

        const removeImageButton = document.getElementById("remove-image");
        const previewSection = document.getElementById("preview-section");
        const uploadSection = document.getElementById("upload-section");
        const imageInput = document.getElementById("profile");
        const previewImage = document.getElementById("preview-image");
        const loader = document.getElementById("loader");
        const form = document.getElementById("campaign-form")

        // HANDLE IMAGE UPLOAD
        imageInput.addEventListener('change', function () {
            const file = this.files[0];
            if (file && file.type.startsWith('image/')) {
                const reader = new FileReader();
                reader.onload = function (e) {
                    previewImage.src = e.target.result;
                    previewSection.classList.remove('hidden');
                    uploadSection.classList.add('hidden');
                };
                reader.readAsDataURL(file);
            } else {
                previewImage.src = "";
                previewSection.classList.add('hidden');
                uploadSection.classList.remove('hidden');
            }
        });

        // REMOVE IMAGE
        removeImageButton.addEventListener('click', function () {
            previewImage.src = "";
            previewSection.classList.add('hidden');
            uploadSection.classList.remove('hidden');
            imageInput.value = '';
        });

        // RESET OR CLEAR THE FORM
        document.getElementById("clear-button").addEventListener('click', function () {
            previewImage.src = "";
            previewSection.classList.add('hidden');
            uploadSection.classList.remove('hidden');
            document.getElementById("campaign-form").reset();
        });

    </script>
</x-layout>
