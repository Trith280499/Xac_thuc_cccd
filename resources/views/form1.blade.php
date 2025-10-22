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
    .upload-card { max-width: 480px; margin: 80px auto; padding: 30px; background: #fff; border-radius: 16px; box-shadow: 0 6px 20px rgba(18,72,116,0.15); border-top: 6px solid var(--primary-color); }
    .upload-box { border: 2px dashed var(--primary-color); border-radius: 12px; background: #f4f8fb; text-align: center; padding: 40px 20px; cursor: pointer; transition: 0.3s; }
    .upload-box:hover { background: #eaf2f8; border-color: var(--accent-color); }
    .preview-image { display: none; width: 100%; border-radius: 10px; margin-top: 10px; }
    button { background-color: var(--accent-color); border: none; color: #fff; font-weight: 500; border-radius: 8px; padding: 10px 0; width: 100%; }
    button:hover { background-color: #b82f35; }
  </style>
</head>
<body>

<div class="upload-card text-center">
  <img src="{{ asset('images/Logo HCMUE.png') }}" alt="Logo SP HCM" height="80">
  <h3 class="mb-4 text-primary">TRƯỜNG ĐH SƯ PHẠM TP.HCM</h3>
  <p class="text-muted">Hệ thống Hỗ trợ khôi phục tài khoản</p>

  <div id="alertBox"></div>

  <form id="uploadForm" action="{{ route('cccd.auth') }}" method="POST" enctype="multipart/form-data">
    @csrf

    <input type="file" id="fileInput" name="cccd" accept="image/*" style="opacity:0; width:0; height:0; position:absolute;" required>

    <div class="upload-box mb-3" id="dropArea">
      <div id="uploadInfo">
        <img id="icon" src="https://cdn-icons-png.flaticon.com/512/1829/1829589.png" width="60" alt="upload">
        <p class="fw-semibold mb-1">Thả ảnh vào hoặc <span class="text-primary">browse</span></p>
        <small class="text-muted">Hỗ trợ: JPG, PNG, JPEG2000</small>
      </div>
      <img id="previewImage" class="preview-image" alt="Preview CCCD">
    </div>

    <button type="submit">Xác thực</button>
  </form>
</div>

<footer class="text-center text-muted mt-4">© 2025 Ho Chi Minh City University of Education</footer>

<script>
  const dropArea = document.getElementById('dropArea');
  const fileInput = document.getElementById('fileInput');
  const previewImage = document.getElementById('previewImage');
  const uploadInfo = document.getElementById('uploadInfo');

  dropArea.addEventListener('click', () => fileInput.click());
  dropArea.addEventListener('dragover', e => { e.preventDefault(); dropArea.classList.add('dragover'); });
  dropArea.addEventListener('dragleave', () => dropArea.classList.remove('dragover'));
  dropArea.addEventListener('drop', e => {
    e.preventDefault(); dropArea.classList.remove('dragover');
    fileInput.files = e.dataTransfer.files;
    handleFile(fileInput.files[0]);
  });
  fileInput.addEventListener('change', () => handleFile(fileInput.files[0]));

  function handleFile(file) {
    if (!file) return;
    const reader = new FileReader();
    reader.onload = e => {
      previewImage.src = e.target.result;
      uploadInfo.style.display = 'none';
      previewImage.style.display = 'block';
    };
    reader.readAsDataURL(file);
  }


  function toBase64(file) {
    return new Promise((resolve, reject) => {
      const reader = new FileReader();
      reader.readAsDataURL(file);
      reader.onload = () => resolve(reader.result);
      reader.onerror = error => reject(error);
    });
  }

  function showAlert(message, type) {
    const alertBox = document.getElementById('alertBox');
    alertBox.innerHTML = `
      <div class="alert alert-${type} alert-dismissible fade show mt-3" role="alert">
        ${message}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
      </div>`;
  }

  document.getElementById('uploadForm').addEventListener('submit', async (e) => {
    e.preventDefault();
    
    const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    const file = fileInput.files[0];

    if (!file) {
        showAlert('Vui lòng chọn ảnh CCCD trước khi xác thực.', 'warning');
        return;
    }

    try {
        const base64Image = await toBase64(file);
        const formData = new FormData();
        formData.append('image_base64', base64Image);

        const response = await fetch('/cccd-auth', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': token,
                'Accept': 'text/html'
            },
            body: formData
        });

        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }

        // Chuyển hướng đến form2 sau khi xác thực thành công
        window.location.href = '/form2/view';

    } catch (err) {
        console.error(err);
        showAlert('Lỗi khi gửi yêu cầu: ' + err.message, 'danger');
    }
});
</script>
</body>
</html>
