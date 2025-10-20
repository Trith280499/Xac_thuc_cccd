<!DOCTYPE html>
<html lang="vi">
<head>
  <meta charset="utf-8">
  <title>Upload ảnh CCCD</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  <style>
    body { background-color: #f8f9fa; }
    .card { max-width: 500px; margin: 40px auto; border-radius: 12px; }

    /* --- Khung upload --- */
    .upload-box {
      border: 2px dashed #cfd8dc;
      border-radius: 10px;
      padding: 30px 20px;
      text-align: center;
      cursor: pointer;
      transition: 0.3s;
      background-color: #fafafa;
      position: relative;
      overflow: hidden;
    }
    .upload-box.dragover {
      border-color: #0d6efd;
      background-color: #e8f0fe;
    }
    .upload-box img#icon {
      width: 60px;
      opacity: 0.7;
      margin-bottom: 10px;
    }
    .upload-text { transition: 0.3s; }

    /* --- Ảnh xem trước --- */
    .preview-image {
      display: none;
      width: 100%;
      height: auto;
      border-radius: 10px;
      object-fit: cover;
      box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    }

    /* --- Thanh tiến trình --- */
    .progress {
      height: 10px;
      margin-top: 15px;
      display: none;
      background-color: #eee;
      border-radius: 5px;
      overflow: hidden;
    }

    /* --- Chính thanh màu --- */
    .progress-bar {
      background: linear-gradient(90deg, #1e88e5, #29b6f6);
      transition: width 0.4s ease-in-out;
      height: 100%;
      width: 0%;
    }
  </style>
</head>
<body>
<div class="container">
  <div class="card p-4 shadow-sm">
    <h3 class="text-center mb-4">Upload ảnh CCCD của bạn</h3>

    <form id="uploadForm" action="/cccd" method="POST" enctype="multipart/form-data">
      @csrf
      <!-- <input type="file" id="fileInput" name="cccd" accept="image/*" hidden required> -->
<input type="file" id="fileInput" name="cccd"
       accept="image/*" style="opacity:0; width:0; height:0; position:absolute;" required>

      <div class="upload-box" id="dropArea">
        <div id="uploadInfo">
          <img id="icon" src="https://cdn-icons-png.flaticon.com/512/1829/1829589.png" alt="upload">
          <p class="fw-bold mb-1 upload-text">Thả ảnh vào hoặc <span class="text-primary">browse</span></p>
          <small class="text-muted upload-text">Supports: JPG, JPEG2000, PNG</small>
        </div>
        <img id="previewImage" class="preview-image" alt="Preview CCCD">
      </div>

      <div class="progress mt-3">
        <div class="progress-bar" id="progressBar"></div>
      </div>

      <button type="submit" class="btn btn-primary w-100 mt-3">Xác thực</button>
    </form>
  </div>
</div>

<script>
const dropArea = document.getElementById('dropArea');
const fileInput = document.getElementById('fileInput');
const previewImage = document.getElementById('previewImage');
const progressBar = document.getElementById('progressBar');
const progressContainer = document.querySelector('.progress');
const uploadInfo = document.getElementById('uploadInfo');

// Click chọn file
dropArea.addEventListener('click', () => fileInput.click());

// Drag effect
dropArea.addEventListener('dragover', (e) => { e.preventDefault(); dropArea.classList.add('dragover'); });
dropArea.addEventListener('dragleave', () => dropArea.classList.remove('dragover'));
dropArea.addEventListener('drop', (e) => {
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
