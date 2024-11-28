<x-layout>
    <!-- Main Section -->
    <section class="bg-white dark:bg-gray-900">
        <div class="py-8 lg:py-16 px-4 mx-auto max-w-screen-md flex flex-col items-center space-y-8">

            <!-- Centered Campaign Form -->
            <canvas id="canvas" class="border-2 border-gray-400 shadow-lg rounded-lg"></canvas>

            <!-- Buttons -->
            <div class="flex space-x-4 w-full max-w-lg">
                <button type="button" onclick="downloadCanvasAsImage('{{$name . "_poster.png"}}')"
                        class="py-3 px-5 w-full font-medium text-center text-white rounded-lg bg-primary-700 hover:bg-primary-800 focus:ring-4 focus:outline-none focus:ring-primary-300">
                    Download
                </button>
                <button type="button" onclick="shareCanvasAsImage('{{$name . "_poster.png"}}')"
                        class="py-3 px-5 w-full font-medium text-center text-white rounded-lg bg-primary-700 hover:bg-primary-800 focus:ring-4 focus:outline-none focus:ring-primary-300">
                    Share
                </button>
            </div>

        </div>
    </section>


    <!-- Loader -->
    <div id="loader"
         class="hidden absolute top-0 left-0 w-full h-full bg-gray-200 bg-opacity-50 flex items-center justify-center z-50">
        <div class="animate-spin rounded-full border-t-4 border-primary-600 w-16 h-16"></div>
    </div>

    <script>

        const canvas = document.getElementById('canvas');

        initializeCanvas(canvas, 730 / 1020, '{{ $poster }}', '{{ $profile }}', '{{$name}}', '{{$credentials}}');

        /**
         * Resize the canvas dynamically based on device size and pixel ratio.
         * The canvas size will adjust to the screen's width, ensuring it maintains
         * a consistent aspect ratio.
         *
         * @param {HTMLCanvasElement} canvas - The canvas element to resize.
         * @param {CanvasRenderingContext2D} ctx - The 2D context of the canvas.
         * @param {number} aspectRatio - The aspect ratio (width / height) to maintain while resizing.
         * @param {string} backgroundUrl - The URL of the background image to be drawn.
         * @param {string} profileUrl - The URL of the profile image to be drawn.
         * @param {string} name - The name text to be drawn on the canvas.
         * @param {string} credentials - The credentials text to be drawn on the canvas.
         */
        function resizeCanvas(canvas, ctx, aspectRatio, backgroundUrl, profileUrl, name, credentials) {
            const canvasWidth = Math.min(window.innerWidth * 0.9, window.innerHeight * aspectRatio);
            const canvasHeight = canvasWidth / aspectRatio;
            const pixelRatio = window.devicePixelRatio || 1; // Account for device pixel ratio (retina displays)

            // Set canvas dimensions
            canvas.width = canvasWidth * pixelRatio;
            canvas.height = canvasHeight * pixelRatio;

            // Style the canvas to match the calculated width/height
            canvas.style.width = `${canvasWidth}px`;
            canvas.style.height = `${canvasHeight}px`;

            // Apply scaling based on device pixel ratio
            ctx.scale(pixelRatio, pixelRatio);

            // Draw the content after resizing
            drawCanvas(ctx, canvasWidth, canvasHeight, backgroundUrl, profileUrl, name, credentials);
        }

        /**
         * Debounce function to throttle calls to a provided function.
         * This is used to limit the frequency of resize events triggering canvas resize.
         *
         * @param {Function} func - The function to be debounced.
         * @param {number} wait - The debounce delay time in milliseconds.
         * @returns {Function} - A debounced version of the input function.
         */
        function debounce(func, wait) {
            let timeout;
            return function (...args) {
                clearTimeout(timeout); // Clear the previous timeout
                timeout = setTimeout(() => func.apply(this, args), wait); // Set a new timeout
            };
        }

        /**
         * Load an image from a URL and return a Promise that resolves when the image is fully loaded.
         *
         * @param {string} src - The URL of the image to be loaded.
         * @returns {Promise<HTMLImageElement>} - A promise that resolves with the loaded image element.
         */
        function loadImage(src) {
            return new Promise((resolve, reject) => {
                const img = new Image();
                img.src = src;
                img.onload = () => resolve(img);  // Resolve the promise with the image when loaded
                img.onerror = reject;           // Reject the promise if there is an error
            });
        }

        /**
         * Draws the provided text on the canvas, breaking it into lines if necessary,
         * ensuring that the text fits within the specified maximum width.
         *
         * @param {CanvasRenderingContext2D} ctx - The 2D context of the canvas.
         * @param {string} text - The text to be drawn.
         * @param {number} x - The x-coordinate where the text will start.
         * @param {number} y - The y-coordinate where the text will be positioned.
         * @param {number} maxWidth - The maximum width the text can occupy before wrapping.
         * @param {number} fontSize - The font size to be used for the text.
         */
        function drawText(ctx, text, x, y, maxWidth, fontSize) {
            ctx.font = `${fontSize}px Arial`; // Set the font size and type
            ctx.fillStyle = "#8b317d";          // Set the text color
            const words = text.split(' ');    // Split the text into words
            let line = '';
            let lineHeight = fontSize * 1.2; // Line height is 1.2 times the font size for readability

            words.forEach((word) => {
                const testLine = `${line}${word} `;
                const testWidth = ctx.measureText(testLine).width;

                // Check if the text exceeds the maximum width and needs to wrap
                if (testWidth > maxWidth && line) {
                    ctx.fillText(line, x, y);  // Draw the current line
                    line = `${word} `;          // Start a new line with the current word
                    y += lineHeight;           // Move the y-coordinate down by the line height
                } else {
                    line = testLine;           // Add the word to the current line
                }
            });

            ctx.fillText(line, x, y);           // Draw the last line of text
        }

        /**
         * Draw the background, profile image, and text on the canvas.
         * This function is called after the canvas is resized to render the final content.
         *
         * @param {CanvasRenderingContext2D} ctx - The 2D context of the canvas.
         * @param {number} width - The width of the canvas.
         * @param {number} height - The height of the canvas.
         * @param {string} backgroundUrl - The URL of the background image to be drawn.
         * @param {string} profileUrl - The URL of the profile image to be drawn.
         * @param {string} name - The name text to be drawn on the canvas.
         * @param {string} credentials - The credentials text to be drawn on the canvas.
         */
        async function drawCanvas(ctx, width, height, backgroundUrl, profileUrl, name, credentials) {
            try {
                // Load the background image and draw it on the canvas
                const background = await loadImage(backgroundUrl);
                ctx.drawImage(background, 0, 0, width, height);

                // Set dynamic text properties and draw the name and credentials
                const fontSize = width * 0.035;      // Set the font size dynamically (3.5% of the canvas width)
                const textX = width * 0.38;          // X-coordinate for the text
                const textY1 = height * 0.13;        // Y-coordinate for the name
                const textY2 = height * 0.16;        // Y-coordinate for the credentials
                const textMaxWidth = width * 0.5;    // Max width of the text area

                drawText(ctx, name, textX, textY1, textMaxWidth, fontSize);        // Draw name
                drawText(ctx, credentials, textX, textY2, textMaxWidth, fontSize); // Draw credentials

                // Load and draw the profile image in a circular shape
                const profile = await loadImage(profileUrl);
                const profileRadius = width * 0.125; // Radius of the circular profile image
                const x = width * 0.09;               // X-coordinate for the profile image
                const y = height * 0.035;             // Y-coordinate for the profile image

                // Draw the circular profile image
                ctx.save();
                ctx.beginPath();
                ctx.arc(x + profileRadius, y + profileRadius, profileRadius, 0, 2 * Math.PI); // Circle path
                ctx.closePath();
                ctx.clip(); // Clip the canvas to the circular path
                ctx.drawImage(profile, x, y, 2 * profileRadius, 2 * profileRadius); // Draw the image within the clipped area
                ctx.restore(); // Restore the canvas state
            } catch (error) {
                alert(error); // Log errors if image loading fails
            }
        }

        /**
         * Set up a debounced resize event listener to adjust the canvas size when the window is resized.
         *
         * @param {HTMLCanvasElement} canvas - The canvas element to be resized.
         * @param {CanvasRenderingContext2D} ctx - The 2D context of the canvas.
         * @param {number} aspectRatio - The aspect ratio to maintain when resizing the canvas.
         * @param {string} backgroundUrl - The URL of the background image.
         * @param {string} profileUrl - The URL of the profile image.
         * @param {string} name - The name text to be drawn.
         * @param {string} credentials - The credentials text to be drawn.
         */
        function setupResizeListener(canvas, ctx, aspectRatio, backgroundUrl, profileUrl, name, credentials) {
            window.addEventListener('resize', debounce(() => resizeCanvas(canvas, ctx, aspectRatio, backgroundUrl, profileUrl, name, credentials), 200));
        }

        /**
         * Initialize the canvas by setting up the resize listener and triggering the initial drawing.
         * This function will also handle the window load event to render the canvas content.
         *
         * @param {HTMLCanvasElement} canvas - Pass the canvas .
         * @param {number} aspectRatio - The aspect ratio (width / height) for resizing the canvas.
         * @param {string} backgroundUrl - The URL of the background image.
         * @param {string} profileUrl - The URL of the profile image.
         * @param {string} name - The name text to be drawn.
         * @param {string} credentials - The credentials text to be drawn.
         */
        function initializeCanvas(canvas, aspectRatio, backgroundUrl, profileUrl, name, credentials) {
            const ctx = canvas.getContext('2d'); // Get the 2D context of the canvas
            window.onload = () => resizeCanvas(canvas, ctx, aspectRatio, backgroundUrl, profileUrl, name, credentials);
            setupResizeListener(canvas, ctx, aspectRatio, backgroundUrl, profileUrl, name, credentials);
        }

        /**
         * Downloads the current canvas content as a PNG image.
         * The image is generated from the canvas data and automatically downloaded when called.
         *
         * @param {string} fileName - The name of the file to be downloaded.
         */
        function downloadCanvasAsImage(fileName = 'canvas_image.png') {
            try {
                const dataUrl = canvas.toDataURL('image/png');
                const link = document.createElement('a');
                link.href = dataUrl;
                link.download = fileName;
                link.click();
            } catch (e) {
                alert(e)
            }
        }

        /**
         * Shares the current canvas content as an image using the Web Share API.
         * The image is converted into a Blob and shared with the available sharing apps.
         *
         * @param {string} title - The title of the shared content.
         * @param {string} text - The text accompanying the shared content.
         */
        async function shareCanvasAsImage(title = 'Canvas Image', text = '') {
            try {
                const dataUrl = canvas.toDataURL('image/png');
                const response = await fetch(dataUrl);
                const blob = await response.blob();
                const shareData = {
                    title: title,
                    text: text,
                    files: [new File([blob], 'canvas_image.png', { type: 'image/png' })], // File object for sharing
                };
                if (navigator.share) {
                    await navigator.share(shareData);
                    console.log('Canvas shared successfully');
                } else {
                    alert('Web Share API is not supported on this device');
                }
            } catch (error) {
                alert(error);
            }
        }


    </script>
</x-layout>