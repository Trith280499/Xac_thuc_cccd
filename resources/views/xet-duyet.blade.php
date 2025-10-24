<!DOCTYPE html>
<html lang="vi">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>Xét duyệt thông tin - HCMUE</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

  <style>
    :root {
      --primary-color: #124874;
      --accent-color: #CF373D;
    }
    body { background-color: #f6f8fa; font-family: 'Segoe UI', Tahoma, sans-serif; }
    .approval-card {
      max-width: 500px;
      margin: 60px auto;
      padding: 30px;
      background: #fff;
      border-radius: 16px;
      box-shadow: 0 6px 20px rgba(18,72,116,0.15);
      border-top: 6px solid var(--accent-color);
    }
    .btn-primary { background-color: var(--primary-color); border: none; }
    .btn-primary:hover { background-color: #0d3a5c; }
  </style>
</head>
<body>

<div class="approval-card">
  <div class="text-center mb-4">
    <img src="{{ asset('images/Logo HCMUE.png') }}" alt="Logo HCMUE" height="70">
    <h4 class="mt-3 mb-2 text-primary">TRƯỜNG ĐH SƯ PHẠM TP.HCM</h4>
    <p class="text-muted">Xét duyệt thông tin sinh viên</p>
  </div>

  <div id="alertBox"></div>

  <div class="info-section mb-4 p-3 border rounded bg-light">
    <h6 class="text-primary">📷 Thông tin từ CCCD</h6>
    <p class="mb-1"><strong>Số CCCD:</strong> <span id="cccdNumber">{{ request()->get('cccd', '') }}</span></p>
    <p class="mb-1"><strong>Họ và tên:</strong> <span id="cccdName">Đang tải...</span></p>
    <p class="mb-0"><strong>Ảnh CCCD:</strong> 
      @if(request()->get('image_url'))
        <a href="{{ request()->get('image_url') }}" target="_blank">Xem ảnh</a>
      @else
        <span class="text-muted">Không có</span>
      @endif
    </p>
  </div>

  <form id="approvalForm">
    <div class="mb-3">
      <label for="mssv" class="form-label">Mã số sinh viên (MSSV)</label>
      <input type="text" class="form-control" id="mssv" name="mssv" 
             placeholder="Nhập mã số sinh viên" required>
    </div>
    
    <div class="mb-3">
      <label for="cccd" class="form-label">Số căn cước công dân</label>
      <input type="text" class="form-control" id="cccd" name="cccd" 
             value="{{ request()->get('cccd', '') }}" readonly>
      <div class="form-text">Số CCCD đã được xác nhận từ ảnh</div>
    </div>

    <button type="submit" class="btn btn-primary w-100 py-2" id="submitBtn">
      Gửi yêu cầu xét duyệt
    </button>
  </form>

  <div class="text-center mt-4">
    <a href="/" class="text-decoration-none">← Quay lại xác thực bằng camera</a>
  </div>
</div>

<footer class="text-center text-muted mt-4">© 2025 Ho Chi Minh City University of Education</footer>

<script>
  document.addEventListener('DOMContentLoaded', function() {
    const approvalForm = document.getElementById('approvalForm');
    const submitBtn = document.getElementById('submitBtn');
    const alertBox = document.getElementById('alertBox');
    const cccdInput = document.getElementById('cccd');
    const mssvInput = document.getElementById('mssv');

    // Try to extract name from URL parameters or OCR data
    const urlParams = new URLSearchParams(window.location.search);
    const cccdNumber = urlParams.get('cccd');
    
    // In a real scenario, you might want to get OCR data from session storage
    // or make an API call to get the extracted data

    // Form submission
    approvalForm.addEventListener('submit', async function(e) {
      e.preventDefault();
      
      const mssv = mssvInput.value.trim();
      const cccd = cccdInput.value.trim();

      if (!mssv) {
        showAlert('Vui lòng nhập mã số sinh viên', 'warning');
        return;
      }

      if (!cccd) {
        showAlert('Số CCCD không hợp lệ', 'danger');
        return;
      }

      try {
        submitBtn.disabled = true;
        submitBtn.textContent = 'Đang gửi yêu cầu...';

        const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        
        const response = await fetch('/submit-approval', {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': token,
            'Accept': 'application/json'
          },
          body: JSON.stringify({
            mssv: mssv,
            cccd: cccd
          })
        });

        const result = await response.json();

        if (result.success) {
          showAlert('✅ ' + result.message, 'success');
          approvalForm.style.display = 'none';
          
          // Show success message
          const successHtml = `
            <div class="alert alert-success text-center">
              <h5>✅ Đã gửi yêu cầu xét duyệt thành công!</h5>
              <p class="mb-2">Thông tin của bạn đã được ghi nhận và đang chờ xét duyệt.</p>
              <p class="mb-0"><strong>MSSV:</strong> ${mssv} | <strong>CCCD:</strong> ${cccd}</p>
            </div>
            <div class="text-center mt-3">
              <a href="/" class="btn btn-outline-primary">Quay lại trang chủ</a>
            </div>
          `;
          document.querySelector('.approval-card').innerHTML = successHtml;
        } else {
          showAlert('❌ ' + result.message, 'danger');
        }

      } catch (error) {
        showAlert('❌ Lỗi khi gửi yêu cầu: ' + error.message, 'danger');
      } finally {
        submitBtn.disabled = false;
        submitBtn.textContent = 'Gửi yêu cầu xét duyệt';
      }
    });

    // Helper: show alert
    function showAlert(message, type) {
      alertBox.innerHTML = `
        <div class="alert alert-${type} alert-dismissible fade show" role="alert">
          ${message}
          <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>`;
    }

    // Focus on MSSV input
    mssvInput.focus();
  });
</script>

</body>
</html>