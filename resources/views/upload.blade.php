<!DOCTYPE html>
<html lang="vi">
<head>
  <meta charset="utf-8">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>Upload ảnh CCCD</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  <style>
    body { background-color: #f8f9fa; }
    .card { max-width: 500px; margin: 40px auto; border-radius: 12px; }
    .upload-box { border: 2px dashed #cfd8dc; border-radius: 10px; padding: 30px 20px; text-align: center; cursor: pointer; background-color: #fafafa; transition: 0.3s; }
    .upload-box.dragover { border-color: #0d6efd; background-color: #e8f0fe; }
    .preview-image { display: none; width: 100%; height: auto; border-radius: 10px; object-fit: cover; box-shadow: 0 2px 8px rgba(0,0,0,0.1); }
    .progress { height: 10px; margin-top: 15px; display: none; background-color: #eee; border-radius: 5px; overflow: hidden; }
    .progress-bar { background: linear-gradient(90deg, #1e88e5, #29b6f6); transition: width 0.4s ease-in-out; height: 100%; width: 0%; }
  </style>
</head>
<body>
<div class="container">
  <div class="card p-4 shadow-sm">
    <h3 class="text-center mb-4">Upload ảnh CCCD của bạn</h3>
    <div id="alertBox"></div>

    <form id="uploadForm" enctype="multipart/form-data">
      @csrf
      <input type="file" id="fileInput" name="cccd" accept="image/*" style="opacity:0;width:0;height:0;position:absolute;" required>

      <div class="upload-box" id="dropArea">
        <div id="uploadInfo">
          <img id="icon" src="https://cdn-icons-png.flaticon.com/512/1829/1829589.png" alt="upload" width="60" style="opacity:0.7;margin-bottom:10px;">
          <p class="fw-bold mb-1 upload-text">Thả ảnh vào hoặc <span class="text-primary">browse</span></p>
          <small class="text-muted upload-text">Supports: JPG, PNG, HEIC</small>
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
const alertBox = document.getElementById('alertBox');

dropArea.addEventListener('click', () => fileInput.click());
dropArea.addEventListener('dragover', e => { e.preventDefault(); dropArea.classList.add('dragover'); });
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
    }, 500);
  };
  reader.readAsDataURL(file);
}

//-----------------------------------------------------------------------------
//js kiểm tra ảnh có được gửi từ điện thoại hay trong ngày

// document.getElementById('uploadForm').addEventListener('submit', async (e) => {
//   e.preventDefault();
//   const file = fileInput.files[0];
//   if (!file) return alert('Vui lòng chọn ảnh.');

//   const formData = new FormData();
//   formData.append('cccd', file);
//   formData.append('_token', '{{ csrf_token() }}');

//   try {
//    const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

// const res = await fetch('{ route("cccd.process") }', {
//   method: 'POST',
//   body: formData,
//   headers: {
//     'X-CSRF-TOKEN': token
//   },
//   credentials: 'same-origin'
// });
//     const data = await res.json();
//     showAlert(data.message, data.status);

//     if (data.status === 'success') {
//     const authRes = await fetch('/cccd-auth', {
//   method: 'POST',
//   headers: {
//     'Content-Type': 'application/json',
//     'X-CSRF-TOKEN': token
//   },
//   credentials: 'same-origin',
//   body: JSON.stringify({ image_url: data.image_url })
// });
//       const authData = await authRes.text();

//       if (authData.trim() === '000001') {
//         showAlert('✅ Xác thực CCCD thành công! Mã: 000001', 'success');
//       } else {
//         showAlert('❌ Không thể xác thực CCCD (Cant author cccd)', 'danger');
//       }
//     }

//   } catch (err) {
//     console.error(err);
//     showAlert('Lỗi khi gửi yêu cầu: ' + err.message, 'danger');
//   }
// });

// Temporarily disable CCCD upload, test only cccd-auth

//-----------------------------------------------------------------------------
//js giả lập gọi api trích xuất cccd, kiểm tra tồn tại và nếu có sinh viên thì lấy data gửi qua form2

document.getElementById('uploadForm').addEventListener('submit', async (e) => {
  e.preventDefault();

  const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
  const fileInput = document.getElementById('fileInput');
  const file = fileInput.files[0];

  if (!file) {
    showAlert('Vui lòng chọn ảnh CCCD trước khi xác thực.', 'warning');
    return;
  }

  try {
    // const base64Image = await toBase64(file);
    const uploadForm = new FormData();
    uploadForm.append('cccd', file);

    const authRes = await fetch('/cccd-auth', {
      method: 'POST',
      headers: {
        // 'Content-Type': 'application/json',
        'X-CSRF-TOKEN': token
      },
      // credentials: 'same-origin',
      // body: JSON.stringify({ image_base64: base64Image })
      body: uploadForm
    });

    const data = await authRes.json();

    // 3️⃣ Handle response
    if (data.status === 'success') {
      console.log('✅ CCCD xác thực thành công, chuyển sang trang kiểm tra...');
      window.location.href = `/check-info?cccd_text=${data.cccd_text}`;
    } else {
      showAlert(data.message || 'Không thể xác thực CCCD.', 'danger');
    }

  } catch (err) {
    console.error(err);
    showAlert('Lỗi khi gửi yêu cầu: ' + err.message, 'danger');
  }
});

// helper: convert file to Base64
// function toBase64(file) {
//   return new Promise((resolve, reject) => {
//     const reader = new FileReader();
//     reader.readAsDataURL(file);
//     reader.onload = () => resolve(reader.result);
//     reader.onerror = error => reject(error);
//   });
// }

function showAlert(message, type) {
  const alertBox = document.getElementById('alertBox');
  alertBox.innerHTML = `
    <div class="alert alert-${type} alert-dismissible fade show mt-3" role="alert">
      ${message}
      <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>`;
}
</script>
</body>
</html>
