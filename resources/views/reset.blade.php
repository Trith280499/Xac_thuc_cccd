<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Reset Password Sinh viên</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <style>
        img#preview {
            display: none;
            max-width: 450px;
            margin-top: 15px;
        }
    </style>
</head>
<body class="bg-light">
<div class="container mt-5">
    <div class="card p-4 shadow-sm">
        <h3 class="text-center mb-4">Reset mật khẩu sinh viên</h3>
        <form id="resetForm" action="/reset" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="mb-3">
                <label class="form-label">Ảnh CCCD (mặt trước):</label>
                <input type="file" name="cccd" class="form-control" id="cccdInput" accept="image/*" required>
                <img id="preview" alt="Preview CCCD">
            </div>
            <button type="button" id="submitBtn" class="btn btn-primary w-100">Xác thực và Reset</button>
        </form>
    </div>
</div>

<!-- Modal -->
<div class="modal fade" id="confirmModal" tabindex="-1" aria-labelledby="confirmModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-scrollable">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="confirmModalLabel">Xác nhận reset mật khẩu</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Đóng"></button>
      </div>
      <div class="modal-body" style="max-height: 300px; overflow-y: auto;">
        <p>
          Vui lòng đọc kỹ các điều khoản và xác nhận bạn đồng ý trước khi reset mật khẩu.
        </p>
        <p>
          - Bạn cam kết rằng ảnh CCCD là thật và thuộc về bạn.<br>
          - Thông tin được cung cấp sẽ chỉ được sử dụng cho mục đích xác thực tài khoản.<br>
          - Việc gửi thông tin giả mạo có thể dẫn đến việc bị khóa tài khoản.
        </p>
        <p>
          (Kéo xuống để xác nhận)
        </p>
        <div class="form-check">
          <input class="form-check-input" type="checkbox" value="" id="confirmCheck">
          <label class="form-check-label" for="confirmCheck">
            Tôi đã đọc và đồng ý với các điều khoản trên.
          </label>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
        <button type="button" id="confirmSubmit" class="btn btn-primary" disabled>Xác nhận & Gửi</button>
      </div>
    </div>
  </div>
</div>

<script>
// Show image preview
document.getElementById('cccdInput').addEventListener('change', function(event) {
    const img = document.getElementById('preview');
    const file = event.target.files[0];
    if (file) {
        const reader = new FileReader();
        reader.onload = e => {
            img.src = e.target.result;
            img.style.display = 'block';
        };
        reader.readAsDataURL(file);
    }
});

// Show modal when pressing submit
document.getElementById('submitBtn').addEventListener('click', function() {
    const modal = new bootstrap.Modal(document.getElementById('confirmModal'));
    modal.show();
});

// Enable confirm button only when checkbox checked
document.getElementById('confirmCheck').addEventListener('change', function() {
    document.getElementById('confirmSubmit').disabled = !this.checked;
});

// Submit form when confirm in modal
document.getElementById('confirmSubmit').addEventListener('click', function() {
    document.getElementById('resetForm').submit();
});
</script>
</body>
</html>
