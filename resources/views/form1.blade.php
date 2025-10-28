<!DOCTYPE html>
<html lang="vi">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>Hỗ trợ khôi phục tài khoản</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

  <style>
    :root {
      --primary-color: #124874;
      --accent-color: #CF373D;
    }
    body { background-color: #f6f8fa; font-family: 'Segoe UI', Tahoma, sans-serif; }
    .upload-card {
      max-width: 520px;
      margin: 60px auto;
      padding: 30px;
      background: #fff;
      border-radius: 16px;
      box-shadow: 0 6px 20px rgba(18,72,116,0.15);
      border-top: 6px solid var(--primary-color);
      text-align: center;
    }
    video, canvas {
      width: 100%;
      border-radius: 12px;
      margin-top: 10px;
      display: none;
    }
    button {
      border: none;
      border-radius: 8px;
      padding: 12px;
      color: white;
      font-weight: 500;
      transition: 0.3s;
    }
    #startBtn { width: 100%; background-color: var(--primary-color); margin-top: 10px; }
    #captureBtn { width: 100%; background-color: var(--primary-color); display: none; margin-top: 10px; }
    #retakeBtn { background-color: #6c757d; display: none; width: 48%; }
    #uploadBtn { background-color: var(--accent-color); display: none; width: 48%; }
    .button-row {
      display: flex;
      justify-content: space-between;
      gap: 4%;
      margin-top: 10px;
    }
    button:hover { opacity: 0.9; }
    .info-box {
      text-align: left;
      margin-top: 20px;
      display: none;
    }
    .info-box strong { color: var(--primary-color); }
  </style>
</head>
<body>

<div class="upload-card">
  <img src="{{ asset('images/Logo HCMUE.png') }}" alt="Logo SP HCM" height="80">
  <h4 class="mt-3 mb-2 text-primary">TRƯỜNG ĐH SƯ PHẠM TP.HCM</h4>
  <p class="text-muted">Hệ thống Hỗ trợ khôi phục tài khoản</p>

  <div id="alertBox"></div>

  <!-- Camera preview -->
  <video id="video" autoplay playsinline></video>
  <canvas id="canvas"></canvas>

  <!-- Buttons -->
  <button id="startBtn">Bắt đầu xác thực bằng Camera</button>
  <button id="captureBtn">Chụp ảnh CCCD</button>
  <div class="button-row">
    <button id="retakeBtn">Chụp lại</button>
    <button id="uploadBtn">Xác thực</button>
  </div>

  <!-- Display extracted info -->
  <div id="infoBox" class="info-box border rounded p-3 bg-light"></div>
</div>

<footer class="text-center text-muted mt-4">© 2025 Ho Chi Minh City University of Education</footer>

<script>
  const video = document.getElementById('video');
  const canvas = document.getElementById('canvas');
  const startBtn = document.getElementById('startBtn');
  const captureBtn = document.getElementById('captureBtn');
  const retakeBtn = document.getElementById('retakeBtn');
  const uploadBtn = document.getElementById('uploadBtn');
  const alertBox = document.getElementById('alertBox');
  const infoBox = document.getElementById('infoBox');
  let stream;

  // Start camera
  startBtn.addEventListener('click', async () => {
    try {
      stream = await navigator.mediaDevices.getUserMedia({ 
        video: { 
          facingMode: 'environment',
          width: { ideal: 1280 },
          height: { ideal: 720 }
        } 
      });
      video.srcObject = stream;
      video.style.display = 'block';
      startBtn.style.display = 'none';
      captureBtn.style.display = 'block';
    } catch (err) {
      showAlert("Không thể truy cập camera: " + err.message, "danger");
    }
  });

  // Take a photo
  captureBtn.addEventListener('click', () => {
    const ctx = canvas.getContext('2d');
    canvas.width = video.videoWidth;
    canvas.height = video.videoHeight;
    ctx.drawImage(video, 0, 0, canvas.width, canvas.height);
    video.style.display = 'none';
    canvas.style.display = 'block';
    captureBtn.style.display = 'none';
    retakeBtn.style.display = 'inline-block';
    uploadBtn.style.display = 'inline-block';
    
    // Stop camera stream to save resources
    if (stream) {
      stream.getTracks().forEach(track => track.stop());
    }
  });

  // Retake
  retakeBtn.addEventListener('click', () => {
    infoBox.style.display = 'none';
    canvas.style.display = 'none';
    video.style.display = 'block';
    captureBtn.style.display = 'block';
    retakeBtn.style.display = 'none';
    uploadBtn.style.display = 'none';
    
    // Restart camera
    startCamera();
  });

  // Restart camera function
  async function startCamera() {
    try {
      stream = await navigator.mediaDevices.getUserMedia({ 
        video: { 
          facingMode: 'environment',
          width: { ideal: 1280 },
          height: { ideal: 720 }
        } 
      });
      video.srcObject = stream;
    } catch (err) {
      showAlert("Không thể khởi động lại camera: " + err.message, "danger");
    }
  }

  // Send image to Laravel /cccd-auth
  uploadBtn.addEventListener('click', async () => {
    const base64Image = canvas.toDataURL('image/jpeg', 0.8);
    const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    const formData = new FormData();
    formData.append('image_base64', base64Image);

    try {
      showAlert("Đang xử lý ảnh, vui lòng chờ...", "info");
      uploadBtn.disabled = true;
      uploadBtn.textContent = "Đang xử lý...";

      const response = await fetch("/cccd-auth", {
        method: "POST",
        headers: { 
          "X-CSRF-TOKEN": token,
          "Accept": "application/json"
        },
        body: formData
      });

      // Handle redirect (success case - tìm thấy sinh viên)
      if (response.redirected) {
        window.location.href = response.url;  
        return;
      }

      const result = await response.json();

      if (result.status === "success") {
        showAlert(" " + result.message, "success");
        // Redirect sẽ được xử lý từ server
      } else if (result.status === "warning") {
        // Không tìm thấy sinh viên -> chuyển đến form xét duyệt
        showAlert(" " + result.message, "warning");
        
        // Chuyển đến form xét duyệt sau 2 giây
        setTimeout(() => {
          window.location.href = "/xet-duyet/view?cccd=" + encodeURIComponent(result.ocr_data?.id || '') + "&image_url=" + encodeURIComponent(result.image_url || '');
        }, 2000);
      } else {
        showAlert(" " + result.message, "danger");
      }

    } catch (err) {
      showAlert(" Lỗi khi gửi ảnh: " + err.message, "danger");
    } finally {
      uploadBtn.disabled = false;
      uploadBtn.textContent = "Xác thực";
    }
  });

  // Show OCR info
  function displayInfo(ocr, imageUrl = null) {
    infoBox.style.display = 'block';
    infoBox.innerHTML = `
      ${imageUrl ? `<p><strong>Ảnh CCCD:</strong> <a href="${imageUrl}" target="_blank">Xem ảnh</a></p>` : ''}
      <p><strong>Số CCCD:</strong> ${ocr?.id || 'Không xác định'}</p>
      <p><strong>Họ và tên:</strong> ${ocr?.full_name || ''}</p>
      <p><strong>Ngày sinh:</strong> ${ocr?.date_of_birth || ''}</p>
      <p><strong>Giới tính:</strong> ${ocr?.sex || ''}</p>
    `;
  }

  // Helper: show alert
  function showAlert(message, type) {
    alertBox.innerHTML = `
      <div class="alert alert-${type} alert-dismissible fade show mt-3" role="alert">
        ${message}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
      </div>`;
  }
</script>

</body>
</html>