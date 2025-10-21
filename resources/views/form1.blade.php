<!DOCTYPE html>
<html lang="vi">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Hỗ trợ khôi phục tài khoản</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  <style>
    :root {
      --primary-color: #124874; /* Xanh Cerulean */
      --accent-color: #CF373D;  /* Đỏ Jasper */
    }

    body {
      background-color: #f6f8fa;
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    }

    .upload-card {
      max-width: 480px;
      margin: 80px auto;
      padding: 30px;
      background: #fff;
      border-radius: 16px;
      box-shadow: 0 6px 20px rgba(18, 72, 116, 0.15);
      border-top: 6px solid var(--primary-color);
    }

    h3 {
      color: var(--primary-color);
      font-weight: 600;
    }

    .upload-box {
      border: 2px dashed var(--primary-color);
      border-radius: 12px;
      background: #f4f8fb;
      text-align: center;
      padding: 40px 20px;
      transition: 0.3s;
      cursor: pointer;
      position: relative;
      overflow: hidden;
    }

    .upload-box:hover {
      background: #eaf2f8;
      border-color: var(--accent-color);
    }

    .upload-box.dragover {
      border-color: var(--accent-color);
      background-color: #f9ebec;
    }

    .upload-box img#icon {
      width: 70px;
      opacity: 0.8;
      margin-bottom: 10px;
    }

    .preview-image {
      display: none;
      width: 100%;
      height: auto;
      border-radius: 10px;
      object-fit: cover;
      box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    }

    .progress {
      height: 10px;
      margin-top: 15px;
      display: none;
      background-color: #e9ecef;
      border-radius: 5px;
      overflow: hidden;
    }

    .progress-bar {
      background: linear-gradient(90deg, var(--primary-color), var(--accent-color));
      transition: width 0.4s ease-in-out;
      height: 100%;
      width: 0%;
    }

    button {
      background-color: var(--accent-color);
      border: none;
      color: #fff;
      font-weight: 500;
      border-radius: 8px;
      padding: 10px 0;
      transition: 0.3s;
    }

    button:hover {
      background-color: #b82f35;
    }

    footer {
      text-align: center;
      margin-top: 40px;
      color: #777;
      font-size: 14px;
    }


    /* --- Responsive cho điện thoại --- */
  @media (max-width: 576px) {
    .upload-card {
      margin: 30px 15px;
      padding: 20px;
      border-radius: 12px;
      box-shadow: 0 4px 12px rgba(18, 72, 116, 0.1);
    }

    img[alt="Logo SP HCM"] {
      height: 60px !important;
    }

    h3 {
      font-size: 18px;
      line-height: 1.4;
    }

    .upload-box {
      padding: 25px 10px;
    }

    button {
      padding: 8px 0;
      font-size: 15px;
    }

    footer {
      font-size: 12px;
      margin-top: 25px;
    }
  }
  </style>
</head>
<body>

  <div class="upload-card text-center">
    <img src="{{ asset('images/Logo HCMUE.png') }}" alt="Logo SP HCM" height="80">
    <!-- <img src="https://upload.wikimedia.org/wikipedia/commons/a/ae/Logo_Trường_Đại_học_Sư_phạm_TP_HCM.png" 
         alt="Logo SP HCM" height="80" class="mb-3"> -->
    <h3>TRƯỜNG ĐẠI HỌC SƯ PHẠM THÀNH PHỐ HỒ CHÍ MINH</h3>
    <p class="text-muted mb-4">Hệ thống Hỗ trợ khôi phục tài khoản</p>

    <form id="uploadForm" action="/cccd" method="POST" enctype="multipart/form-data">
      @csrf
      <input type="file" id="fileInput" name="cccd"
             accept="image/*" style="opacity:0; width:0; height:0; position:absolute;" required>

      <div class="upload-box mb-3" id="dropArea">
        <div id="uploadInfo">
          <img id="icon" src="https://cdn-icons-png.flaticon.com/512/1829/1829589.png" alt="upload">
          <p class="fw-semibold mb-1">Thả ảnh vào hoặc 
            <span class="text-primary" style="color: var(--primary-color)">browse</span>
          </p>
          <small class="text-secondary">Hỗ trợ: JPG, JPEG2000, PNG</small>
        </div>
        <img id="previewImage" class="preview-image" alt="Preview CCCD">
      </div>

      <div class="progress mt-3">
        <div class="progress-bar" id="progressBar"></div>
      </div>

      <button type="submit" class="w-100 mt-3">Xác thực</button>
    </form>
  </div>

  <footer>
    Copyright © 2025 Ho Chi Minh City University of Education
  </footer>

  <script>
    const dropArea = document.getElementById('dropArea');
    const fileInput = document.getElementById('fileInput');
    const previewImage = document.getElementById('previewImage');
    const progressBar = document.getElementById('progressBar');
    const progressContainer = document.querySelector('.progress');
    const uploadInfo = document.getElementById('uploadInfo');

    dropArea.addEventListener('click', () => fileInput.click());
    dropArea.addEventListener('dragover', e => {
      e.preventDefault();
      dropArea.classList.add('dragover');
    });
    dropArea.addEventListener('dragleave', () => dropArea.classList.remove('dragover'));
    dropArea.addEventListener('drop', e => {
      e.preventDefault();
      dropArea.classList.remove('dragover');
      fileInput.files = e.dataTransfer.files;
      handleFile(fileInput.files[0]);
    });
    fileInput.addEventListener('change', () => handleFile(fileInput.files[0]));

    function handleFile(file) {
      if (!file) return;
      uploadInfo.style.opacity = '0';
      progressContainer.style.display = 'block';
      progressBar.style.width = '0%';
      previewImage.style.display = 'none';

      let progress = 0;
      const interval = setInterval(() => {
        progress += Math.random() * 10;
        if (progress >= 95) progress = 95;
        progressBar.style.width = progress + '%';
      }, 200);

      const reader = new FileReader();
      reader.onload = e => previewImage.src = e.target.result;
      reader.onloadend = () => {
        clearInterval(interval);
        progressBar.style.width = '100%';
        setTimeout(() => {
          progressContainer.style.display = 'none';
          uploadInfo.style.display = 'none';
          previewImage.style.display = 'block';
          dropArea.style.backgroundColor = '#fff';
          dropArea.style.borderColor = '#cfd8dc';
        }, 500);
      };
      reader.readAsDataURL(file);
    }
  </script>

</body>
</html>
