<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AI Background Remover</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <style>
        :root {
            --bg-color: #f5f5f5;
            --card-bg: #ffffff;
            --primary-color: #212529;
            --secondary-color: #6c757d;
            --shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
            --success-color: #198754;
            --processing-color: #0d6efd;
        }

        body {
            background-color: var(--bg-color);
            font-family: 'Segoe UI', system-ui, sans-serif;
            min-height: 100vh;
            padding: 20px 0;
        }

        .app-container {
            max-width: 900px;
            margin: 0 auto;
        }

        .header {
            text-align: center;
            margin-bottom: 2.5rem;
        }

        .header h1 {
            font-weight: 700;
            letter-spacing: -0.5px;
            margin-bottom: 1rem;
            color: var(--primary-color);
        }

        .header p {
            color: var(--secondary-color);
            max-width: 600px;
            margin: 0 auto;
            font-size: 1.1rem;
        }

        .preview-card {
            background: var(--card-bg);
            border-radius: 16px;
            box-shadow: var(--shadow);
            padding: 2.5rem;
            margin-bottom: 2.5rem;
            position: relative;
            min-height: 400px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            border: none;
            transition: all 0.3s ease;
        }

        .preview-area {
            width: 100%;
            height: 350px;
            background-color: #f8f9fa;
            border-radius: 12px;
            border: 2px dashed #e9ecef;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            cursor: pointer;
            transition: all 0.3s ease;
            overflow: hidden;
            position: relative;
        }

        .preview-area:hover {
            border-color: #adb5bd;
            background-color: #f1f3f5;
        }

        .preview-img {
            max-width: 100%;
            max-height: 100%;
            object-fit: contain;
            display: none;
        }

        .upload-icon {
            font-size: 3rem;
            margin-bottom: 1rem;
            color: var(--secondary-color);
            transition: all 0.3s ease;
        }

        .upload-btn {
            background-color: var(--primary-color);
            color: white;
            border: none;
            padding: 12px 28px;
            font-size: 1.1rem;
            font-weight: 600;
            border-radius: 50px;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            gap: 10px;
            position: relative;
            z-index: 2;
        }

        .upload-btn:hover {
            background-color: #343a40;
            transform: translateY(-2px);
        }

        .upload-btn:active {
            transform: translateY(0);
        }

        .samples-section {
            margin-top: 1.5rem;
            text-align: center;
        }

        .samples-section h4 {
            font-weight: 600;
            margin-bottom: 1.5rem;
            color: var(--primary-color);
        }

        .sample-img {
            width: 80px;
            height: 80px;
            object-fit: cover;
            margin: 0 5px;
            border-radius: 8px;
            cursor: pointer;
            transition: all 0.3s ease;
            /* background-color: #e9ecef; */
        }

        .sample-img:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
        }

        .thumbnail-container {
            position: relative;
            margin-bottom: 20px;
        }

        .thumbnail-container::after {
            content: "Try this";
            position: absolute;
            bottom: -20px;
            left: 0;
            right: 0;
            text-align: center;
            font-size: 0.8rem;
            color: var(--secondary-color);
            opacity: 0;
            transition: opacity 0.3s ease;
        }

        .thumbnail-container:hover::after {
            opacity: 1;
        }

        .footer {
            text-align: center;
            margin-top: 3rem;
            color: var(--secondary-color);
            font-size: 0.9rem;
        }

        .processing-overlay {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(255, 255, 255, 0.9);
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            opacity: 0;
            pointer-events: none;
            transition: all 0.3s ease;
            z-index: 10;
            border-radius: 12px;
        }

        .processing-spinner {
            width: 60px;
            height: 60px;
            border: 5px solid rgba(13, 110, 253, 0.2);
            border-top: 5px solid var(--processing-color);
            border-radius: 50%;
            animation: spin 1s linear infinite;
            margin-bottom: 20px;
        }

        @keyframes spin {
            0% {
                transform: rotate(0deg);
            }

            100% {
                transform: rotate(360deg);
            }
        }

        .processing-text {
            font-size: 1.2rem;
            font-weight: 600;
            color: var(--primary-color);
        }

        .result-container {
            display: none;
            width: 100%;
            text-align: center;
            margin-top: 20px;
        }

        .result-image {
            max-width: 100%;
            max-height: 300px;
            object-fit: contain;
            border-radius: 8px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            background:
                linear-gradient(45deg, #f0f0f0 25%, transparent 25%, transparent 75%, #f0f0f0 75%),
                linear-gradient(45deg, #f0f0f0 25%, transparent 25%, transparent 75%, #f0f0f0 75%);
            background-size: 20px 20px;
            background-position: 0 0, 10px 10px;
        }

        .download-btn {
            background-color: var(--success-color);
            color: white;
            border: none;
            padding: 10px 24px;
            font-size: 1rem;
            font-weight: 600;
            border-radius: 50px;
            transition: all 0.3s ease;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            margin: 10px 5px;
        }

        .download-btn:hover {
            background-color: #157347;
            transform: translateY(-2px);
        }

        .upload-instructions {
            color: var(--secondary-color);
            margin-top: 1rem;
            font-size: 0.9rem;
        }

        .comparison-container {
            display: flex;
            width: 100%;
            gap: 20px;
            margin-top: 20px;
        }

        .comparison-box {
            flex: 1;
            text-align: center;
            padding: 15px;
            background: #f8f9fa;
            border-radius: 10px;
        }

        .comparison-label {
            font-weight: 600;
            margin-bottom: 10px;
            color: var(--secondary-color);
        }

        .action-buttons {
            display: flex;
            justify-content: center;
            flex-wrap: wrap;
            margin-top: 25px;
        }

        .try-another-btn {
            background-color: #6c757d;
            color: white;
            border: none;
            padding: 10px 24px;
            font-size: 1rem;
            font-weight: 600;
            border-radius: 50px;
            transition: all 0.3s ease;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            margin: 10px 5px;
        }

        .try-another-btn:hover {
            background-color: #5c636a;
            transform: translateY(-2px);
        }

        .success-message {
            background-color: #d1e7dd;
            color: #0f5132;
            padding: 15px;
            border-radius: 8px;
            margin: 20px auto;
            max-width: 500px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .result-card {
            background: var(--card-bg);
            border-radius: 16px;
            box-shadow: var(--shadow);
            padding: 2.5rem;
            margin-bottom: 2.5rem;
            position: relative;
            min-height: 400px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            border: none;
            transition: all 0.3s ease;
        }

        @media (max-width: 768px) {

            .preview-card,
            .result-card {
                padding: 1.5rem;
            }

            .header h1 {
                font-size: 2rem;
            }

            .header p {
                font-size: 1rem;
            }

            .preview-area {
                height: 280px;
            }

            .comparison-container {
                flex-direction: column;
            }

            .action-buttons {
                flex-direction: column;
                align-items: center;
            }
                    .sample-img {
            width: 60px;
            height: 60px;
                    }
        }
    </style>
</head>

<body>
    <div class="app-container">
        <!-- Header Section -->
        <div class="header">
            <h1>Background Remover</h1>
            <p class="lead">Remove backgrounds from portrait photo in just one click with AI Photo Background Remover.
            </p>
        </div>

        <!-- Upload Section (Initially Visible) -->
        <div class="card preview-card" id="uploadSection">
            <div class="preview-area" id="previewArea">
                <i class="bi bi-cloud-arrow-up upload-icon" id="uploadIcon"></i>
                <input type="file" id="fileInput" accept="image/*" style="display: none;">
                <button class="upload-btn" id="uploadBtn">
                    <i class="bi bi-upload"></i> Upload Image
                </button>
                <p class="upload-instructions" id="uploadInstructions">Drag & drop or click to upload</p>

                <img src="" class="preview-img" id="originalPreview" alt="Original image">

                <div class="processing-overlay" id="processingOverlay">
                    <div class="processing-spinner"></div>
                    <p class="processing-text">Removing background...</p>
                </div>
            </div>
        </div>

        <!-- Result Section (Initially Hidden) -->
        <div class="result-card" id="resultSection" style="display: none;">
            <div class="success-message">
                <i class="bi bi-check-circle-fill" style="font-size: 1.5rem;"></i>
                <span>Background removed successfully! Download your image below.</span>
            </div>

            <div class="comparison-container">
                <div class="comparison-box">
                    <div class="comparison-label">Original</div>
                    <img src="" class="result-image" id="originalImage" alt="Original image">
                </div>
                <div class="comparison-box">
                    <div class="comparison-label">Background Removed</div>
                    <img src="" class="result-image" id="processedImage" alt="Processed image">
                </div>
            </div>

            <div class="action-buttons">
                <button class="download-btn" id="downloadBtn">
                    <i class="bi bi-download"></i> Download Result
                </button>
                <button class="try-another-btn" id="tryAnotherBtn">
                    <i class="bi bi-arrow-repeat"></i> Try Another Image
                </button>
            </div>
        </div>

        <!-- Sample Images Section -->
        <div class="samples-section" id="sampleSection">
            <h4>No image? Try one of these:</h4>
            <!-- <div class="row"> -->

                <div class="d-flex justify-content-center mt-2">
                    <div class="thumbnail-container">
                        <img class="sample-img" data-sample="sample1"
                            src="https://randomuser.me/api/portraits/women/1.jpg" alt="">
                    </div>
                    <div class="thumbnail-container">
                        <img class="sample-img" data-sample="sample2"
                            src="https://randomuser.me/api/portraits/men/2.jpg" alt="">
                    </div>
                    <div class="thumbnail-container">
                        <img class="sample-img" data-sample="sample3"
                            src="https://randomuser.me/api/portraits/women/3.jpg" alt="">
                    </div>
                    <div class="thumbnail-container">
                        <img class="sample-img" data-sample="sample4"
                            src="https://randomuser.me/api/portraits/men/4.jpg" alt="">
                    </div>
                </div>

            <!-- </div> -->
        </div>

        <!-- Footer -->
        <div class="footer">
            <p>Powered by AI technology • 100% automatic background removal</p>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        $(document).ready(function () {
            // Elements
            const fileInput = $('#fileInput');
            const uploadBtn = $('#uploadBtn');
            const uploadIcon = $('#uploadIcon');
            const originalPreview = $('#originalPreview');
            const processingOverlay = $('#processingOverlay');
            const uploadSection = $('#uploadSection');
            const resultSection = $('#resultSection');
            const originalImage = $('#originalImage');
            const processedImage = $('#processedImage');
            const downloadBtn = $('#downloadBtn');
            const tryAnotherBtn = $('#tryAnotherBtn');
            const previewArea = $('#previewArea');
            const sampleSection = $('#sampleSection');
            const uploadInstructions = $('#uploadInstructions');

            // Upload button click
            uploadBtn.on('click', function () {
                fileInput.click();
            });

            // File input change
            fileInput.on('change', function (e) {
                if (e.target.files && e.target.files[0]) {
                    const file = e.target.files[0];
                    const reader = new FileReader();

                    reader.onload = function (event) {
                        // Show the image preview
                        uploadIcon.hide();
                        originalPreview.attr('src', event.target.result).show();

                        // Process the image
                        processImage(file);
                    }

                    reader.readAsDataURL(file);
                }
            });

            // Drag and drop functionality
            previewArea.on('dragover', function (e) {
                e.preventDefault();
                $(this).css('border-color', '#0d6efd');
                $(this).css('background-color', '#e7f1ff');
            });

            previewArea.on('dragleave', function () {
                $(this).css('border-color', '#e9ecef');
                $(this).css('background-color', '#f8f9fa');
            });

            previewArea.on('drop', function (e) {
                e.preventDefault();
                $(this).css('border-color', '#e9ecef');
                $(this).css('background-color', '#f8f9fa');

                if (e.originalEvent.dataTransfer.files && e.originalEvent.dataTransfer.files[0]) {
                    const file = e.originalEvent.dataTransfer.files[0];

                    if (file.type.match('image.*')) {
                        const reader = new FileReader();

                        reader.onload = function (event) {
                            uploadIcon.hide();
                            originalPreview.attr('src', event.target.result).show();
                            processImage(file);
                        }

                        reader.readAsDataURL(file);
                    }
                }
            });

            // Sample image click
            $('.sample-img').on('click', function () {
                const sampleUrl = $(this).attr('src');

                // Show loading state
                uploadIcon.hide();
                originalPreview.attr('src', sampleUrl).show();

                // Simulate processing for sample image
                processImage(null, sampleUrl);
            });

            // Process image (simulated)
            function processImage(file, sampleUrl = null) {

                // Show processing overlay
                processingOverlay.css('opacity', 1).css('pointer-events', 'all');

                resultSection.hide();
                uploadBtn.hide();
                uploadInstructions.hide();

                // Simulate processing time (2-3 seconds)
                const processTime = 2000 + Math.random() * 1000;

                setTimeout(function () {
                    // Hide processing overlay
                    processingOverlay.css('opacity', 0).css('pointer-events', 'none');

                    // Set images for result section
                    const originalSrc = sampleUrl || originalPreview.attr('src');
                    const processedSrc = "https://i.imgur.com/7iZ8p0V.png"; // Dummy result with transparent BG

                    originalImage.attr('src', originalSrc);
                    processedImage.attr('src', processedSrc);

                    // Switch to result section
                    uploadSection.hide();
                    resultSection.show();

                    sampleSection.hide();
                    uploadBtn.show();
                    uploadInstructions.show();


                }, processTime);
            }

            // Try another image button
            tryAnotherBtn.on('click', function () {
                // Reset the UI
                uploadSection.show();
                sampleSection.show();
                resultSection.hide();

                // Clear the preview
                uploadIcon.show();
                originalPreview.hide().attr('src', '');

                // Reset file input
                fileInput.val('');
            });

            // Download button
            downloadBtn.on('click', function () {
                // In a real app, this would download the processed image
                alert('Background removed image downloaded successfully!');
            });
        });
    </script>
</body>

</html>