<?php
include_once("config.php");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Upload Image</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f2f2f2;
            padding: 2rem;
            display: flex;
            justify-content: center;
        }

        .upload-container {
            background: #fff;
            padding: 2rem;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            width: 400px;
            text-align: center;
        }

        .drop-zone {
            border: 2px dashed #bbb;
            border-radius: 6px;
            padding: 2rem;
            background: #fafafa;
            cursor: pointer;
            transition: background 0.3s;
        }

        .drop-zone.dragover {
            background: #e8f0fe;
            border-color: #4285f4;
        }

        input[type="file"] {
            display: none;
        }

        #preview {
            margin-top: 1rem;
            max-width: 100%;
            display: none;
            border-radius: 6px;
            border: 1px solid #ccc;
        }

        .submit-btn {
            margin-top: 1rem;
            background-color: #4CAF50;
            color: white;
            padding: 0.6rem 1.2rem;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        .submit-btn:hover {
            background-color: #45a049;
        }

        .form-group {
            margin-top: 1rem;
            text-align: left;
        }

        .form-group label {
            display: block;
            margin-bottom: 0.2rem;
            font-weight: bold;
        }

        .form-group input {
            width: 100%;
            padding: 0.4rem;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        /* Style the select */
        #licenseSelect {
            width: 100%;
            padding: 0.4rem;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 1rem;
            font-family: Arial, sans-serif;
            box-sizing: border-box;
            appearance: none; /* Remove default arrow for some browsers */
            background: white url('data:image/svg+xml;utf8,<svg fill="gray" height="16" viewBox="0 0 24 24" width="16" xmlns="http://www.w3.org/2000/svg"><path d="M7 10l5 5 5-5z"/></svg>') no-repeat right 0.75rem center;
            background-size: 1em;
            cursor: pointer;
            transition: border-color 0.2s ease;
        }
        #licenseSelect:focus {
            outline: none;
            border-color: #4285f4;
            box-shadow: 0 0 3px #4285f4;
        }

        /* Style the custom license input */
        #customLicense {
            width: 100%;
            padding: 0.4rem;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 1rem;
            font-family: Arial, sans-serif;
            box-sizing: border-box;
        }
            #customLicense:focus {
            outline: none;
            border-color: #4285f4;
            box-shadow: 0 0 3px #4285f4;
        }
    </style>
</head>
<body>

<div class="upload-container">
    <h2>Upload Image</h2>
    <form id="uploadForm" enctype="multipart/form-data">
        <div class="drop-zone" id="dropZone">
            <p>Drag & drop image here or click to select</p>
            <input type="file" name="file_input" id="file_input" accept="image/*" />
        </div>
        <img id="preview" alt="Image Preview" />

        <div class="form-group">
          <label for="licenseSelect">License Type</label>
          <select name="license" id="licenseSelect" required>
            <option value="">Lizenz auswählen ...</option>
            <option value="CC BY">Creative Commons Attribution (CC BY)</option>
            <option value="CC BY-SA">Creative Commons ShareAlike (CC BY-SA)</option>
            <option value="CC BY-ND">Creative Commons NoDerivs (CC BY-ND)</option>
            <option value="CC BY-NC">Creative Commons NonCommercial (CC BY-NC)</option>
            <option value="MIT">MIT License</option>
            <option value="GPL">GNU GPL</option>
            <option value="Apache">Apache License 2.0</option>
            <option value="Other">Andere Lizenz (bitte spezifizieren)</option>
          </select>

          <input type="text" name="custom_license" id="custom_license" placeholder="Enter custom license" style="display:none; margin-top: 0.5rem;" />
        </div>

        <div class="form-group">
            <label for="created_by">Created By</label>
            <input type="text" name="created_by" id="created_by" />
        </div>
        <div class="form-group">
            <label for="copyright">Copyright</label>
            <input type="text" name="copyright" id="copyright" />
        </div>
        <div class="form-group">
            <label for="alt_text">Alternate Text</label>
            <input type="text" name="alt_text" id="alt_text" />
        </div>
        <div class="form-group">
            <label for="focus_x">Focus X (0–10000)</label>
            <input type="number" step="0.01" min="0" max="1" name="focus_x" id="focus_x" />
        </div>
        <div class="form-group">
            <label for="focus_y">Focus Y (0–10000)</label>
            <input type="number" step="0.01" min="0" max="1" name="focus_y" id="focus_y" />
        </div>
        <div class="form-group">
            <label for="user_id">User ID</label>
            <input type="text" name="user_id" id="user_id" />
        </div>
        <input type="submit" value="Upload" class="submit-btn" />
    </form>
</div>

<script>
    const dropZone = document.getElementById('dropZone');
    const fileInput = document.getElementById('file_input');
    const preview = document.getElementById('preview');

    dropZone.addEventListener('click', () => fileInput.click());
    dropZone.addEventListener('dragover', (e) => {
        e.preventDefault();
        dropZone.classList.add('dragover');
    });
    dropZone.addEventListener('dragleave', () => dropZone.classList.remove('dragover'));
    dropZone.addEventListener('drop', (e) => {
        e.preventDefault();
        dropZone.classList.remove('dragover');
        const file = e.dataTransfer.files[0];
        if (file) {
            fileInput.files = e.dataTransfer.files;
            previewImage(file);
        }
    });
    fileInput.addEventListener('change', () => {
        const file = fileInput.files[0];
        if (file) previewImage(file);
    });

    function previewImage(file) {
        if (!file.type.startsWith('image/')) return;
        const reader = new FileReader();
        reader.onload = (e) => {
            preview.src = e.target.result;
            preview.style.display = 'block';
        };
        reader.readAsDataURL(file);
    }

    const licenseSelect = document.getElementById('licenseSelect');
    const customLicenseInput = document.getElementById('customLicense');

    licenseSelect.addEventListener('change', () => {
        if (licenseSelect.value === 'Other') {
            customLicenseInput.style.display = 'block';
            customLicenseInput.required = true;
        }
        else {
            customLicenseInput.style.display = 'none';
            customLicenseInput.required = false;
            customLicenseInput.value = '';
        }
    });

    const form = document.getElementById("uploadForm");
    form.addEventListener("submit", async (e) => {
        e.preventDefault();

        const fileInput = document.getElementById("file_input");
        if (fileInput.files.length === 0) {
            alert("Please select a file.");
            return;
        }

        const formData = new FormData(form); // Includes all form fields

        try {
            const response = await fetch("<?= $apiBaseUrl ?>/image/upload", {
                method: "POST",
                body: formData,
                credentials: "include" // Send cookies if needed
            });

            if (response.ok) {
                alert("Upload successful!");
            } else {
                const error = await response.json();
                alert("Upload failed: " + (error.error || response.statusText));
            }
        } catch (err) {
            alert("Upload error: " + err.message);
        }
    });
</script>

</body>
</html>