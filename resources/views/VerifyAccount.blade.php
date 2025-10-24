<!DOCTYPE html>
<html lang="vi">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>Xác thực sinh viên</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

  <style>
    :root {
      --primary-color: #124874;
      --accent-color: #CF373D;
    }
    body { background-color: #f6f8fa; font-family: 'Segoe UI', Tahoma, sans-serif; }
    .verify-card {
      max-width: 520px;
      margin: 60px auto;
      padding: 30px;
      background: #fff;
      border-radius: 16px;
      box-shadow: 0 6px 20px rgba(18,72,116,0.15);
      border-top: 6px solid var(--primary-color);
    }
    button {
      border: none;
      border-radius: 8px;
      padding: 12px 20px;
      background-color: var(--primary-color);
      color: white;
      font-weight: 500;
      transition: 0.3s;
    }
    button:hover { opacity: 0.9; }
  </style>
</head>
<body>

<div class="verify-card">
  <h4 class="text-center text-primary mb-3">Xác thực thông tin sinh viên</h4>
  <p class="text-center text-muted">Nhập CCCD và MSSV để kiểm tra</p>

  <div id="alertBox"></div>

  <form id="verifyForm">
    <div class="mb-3">
      <label class="form-label fw-semibold">Số CCCD</label>
      <input type="text" id="cccd" class="form-control" required>
    </div>
    <div class="mb-3">
      <label class="form-label fw-semibold">Mã số sinh viên (MSSV)</label>
      <input type="text" id="mssv" class="form-control" required>
    </div>
    <button type="submit" class="w-100">Xác thực</button>
  </form>

  <div id="resultBox" class="mt-4" style="display:none;">
    <h6 class="text-primary">Kết quả xác thực:</h6>
    <div id="resultContent"></div>
  </div>
</div>

<footer class="text-center text-muted mt-4">© 2025 Ho Chi Minh City University of Education</footer>

<script>
  const verifyForm = document.getElementById('verifyForm');
  const alertBox = document.getElementById('alertBox');
  const resultBox = document.getElementById('resultBox');
  const resultContent = document.getElementById('resultContent');

  verifyForm.addEventListener('submit', async (e) => {
    e.preventDefault();
    const cccd = document.getElementById('cccd').value.trim();
    const mssv = document.getElementById('mssv').value.trim();
    const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

    if (!cccd || !mssv) {
      showAlert('Vui lòng nhập đầy đủ CCCD và MSSV.', 'warning');
      return;
    }

    showAlert('Đang xác thực, vui lòng chờ...', 'info');

    try {
      const response = await fetch('/verify-account', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': token },
        body: JSON.stringify({ cccd, mssv })
      });

      const result = await response.json();
      alertBox.innerHTML = '';

      if (result.status === 'success') {
        resultBox.style.display = 'block';
        resultContent.innerHTML = `
          <table class="table table-bordered mt-3">
            <thead class="table-light">
              <tr>
                <th>Chọn</th>
                <th>Loại tài khoản</th>
                <th>Tài khoản</th>
              </tr>
            </thead>
            <tbody>
              ${result.accounts.map(acc => `
                <tr>
                  <td><input type="checkbox" class="form-check-input"></td>
                  <td>${acc.type}</td>
                  <td>${acc.username}</td>
                </tr>
              `).join('')}
            </tbody>
          </table>
        `;
      } else {
        window.location.href = '/not-student';
      }

    } catch (err) {
      showAlert('❌ Lỗi kết nối: ' + err.message, 'danger');
    }
  });

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
