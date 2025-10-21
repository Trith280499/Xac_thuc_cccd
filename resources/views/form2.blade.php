<!DOCTYPE html>
<html lang="vi">
<head>
  <meta charset="UTF-8">
  <title>Xác nhận và khôi phục tài khoản sinh viên</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  <style>
    body {
      background-color: #f1f3f5;
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    }

    .card {
      max-width: 850px;
      margin: 50px auto;
      border-radius: 15px;
    }

    .form-section {
      background-color: #f8f9fa;
      padding: 15px;
      border-radius: 10px;
      margin-bottom: 20px;
    }

    table th {
      background-color: #e9ecef;
      text-align: center;
      vertical-align: middle;
      font-weight: 600;
    }

    table td {
      vertical-align: middle;
    }

    .status-text {
      font-weight: 500;
      color: #0d6efd;
      cursor: pointer;
      transition: all 0.25s ease;
    }

    .status-text:hover {
      color: #084298;
      text-decoration: underline;
    }

    .status-success {
      color: #198754 !important;
      cursor: default;
      text-decoration: none;
    }
  </style>
</head>

<body>
  <div class="container">
    <div class="card shadow-lg p-4">
      <h3 class="text-center mb-4 text-primary">XÁC NHẬN VÀ KHÔI PHỤC TÀI KHOẢN SINH VIÊN</h3>

      <form id="form2" action="/reset/confirm" method="POST">
        @csrf

        <!-- Thông tin sinh viên -->
        <div class="form-section">
          <h5>Thông tin sinh viên</h5>
          <div class="mb-3">
            <label class="form-label fw-bold">Họ và tên</label>
            <input type="text" name="hoten" class="form-control" value="{{ $sv->ho_ten ?? '' }}" placeholder="Nhập họ và tên" required>
          </div>
          <div class="mb-3">
            <label class="form-label fw-bold">Căn cước công dân</label>
            <input type="text" name="cccd" class="form-control" value="{{ $cccd->cccd ?? '' }}" placeholder="Nhập số CCCD" required>
          </div>
          <div class="mb-3">
            <label class="form-label fw-bold">Mã số sinh viên</label>
            <input type="text" name="mssv" class="form-control" value="{{ $sv->mssv ?? '' }}" placeholder="Nhập MSSV" required>
          </div>
        </div>

        <!-- Trạng thái khôi phục -->
        <div class="form-section">
          <h5>Trạng thái khôi phục tài khoản</h5>
  <div class="table-responsive">
    <table class="table table-bordered align-middle mb-0">
      <thead>
        <tr>
          <th style="width: 50%;">Loại tài khoản</th>
          <th style="width: 40%;">Tài khoản</th>
          <th style="width: 10%;">Thao tác</th>
        </tr>
      </thead>
      <tbody>
        <tr>
          <td>
            <img src="{{ asset('images/teams.png') }}" alt="Microsoft Teams" width="26" class="me-2">
            Microsoft Teams<br>
            <small class="text-muted">(MSSV@student.hcmue.edu.vn)</small>
          </td>
          <td>
            <input type="text" name="email_account" class="form-control"
                   value="{{ $edu->email ?? '' }}" placeholder="Email">
          </td>
          <td class="text-center">
            <span class="status-text text-primary" style="cursor:pointer;"
                  onclick="recoverAccount(this, 'Microsoft Teams')">Khôi phục</span>
          </td>
        </tr>

        <tr>
          <td>📝 VLE (học trực tuyến)</td>
          <td>
            <input type="text" name="moodle_account" class="form-control"
                   value="{{ $vle->username ?? '' }}" placeholder="Tên đăng nhập">
          </td>
          <td class="text-center">
            <span class="status-text text-primary" style="cursor:pointer;"
                  onclick="recoverAccount(this, 'VLE')">Khôi phục</span>
          </td>
        </tr>

        <tr>
          <td>👨‍🎓 Portal (MSSV)</td>
          <td>
            <input type="text" name="portal_account" class="form-control"
                   value="{{ $msteam->username ?? '' }}" placeholder="Tài khoản Portal">
          </td>
          <td class="text-center">
            <span class="status-text text-primary" style="cursor:pointer;"
                  onclick="recoverAccount(this, 'Portal')">Khôi phục</span>
          </td>
        </tr>
      </tbody>
    </table>
  </div>
        </div>

        <!-- Lịch sử khôi phục -->
        <div class="form-section">
          <h5>Lịch sử khôi phục</h5>
          <div class="table-responsive">
            <table class="table table-bordered align-middle mb-0">
              <thead>
                <tr>
                  <th>Loại tài khoản</th>
                  <th>Tên tài khoản</th>
                  <th>Mật khẩu</th>
                  <th>Ngày</th>
                  <th>Giờ</th>
                  <th>Tháng</th>
                  <th>Năm</th>
                </tr>
              </thead>
              <tbody id="historyTable">
                <tr class="text-center text-muted">
                  <td colspan="7">Chưa có lịch sử khôi phục nào</td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>

      </form>
    </div>
  </div>

  <script>
    function recoverAccount(el, type) {
      if (el.classList.contains('status-success')) return;

      alert(`🔄 Đang khôi phục tài khoản ${type}...`);

      el.textContent = 'Đang khôi phục...';
      el.style.pointerEvents = 'none';

      setTimeout(() => {
        el.textContent = `✅ ${type} đã khôi phục xong`;
        el.classList.add('status-success');

        // Thêm vào bảng lịch sử
        addHistoryRow(type);
      }, 1500);
    }

    function addHistoryRow(type) {
      const table = document.getElementById('historyTable');
      const now = new Date();

      const row = `
        <tr>
          <td>${type}</td>
          <td>${type.toLowerCase()}_user</td>
          <td>${Math.random().toString(36).slice(-8)}</td>
          <td>${now.getDate()}</td>
          <td>${now.getHours()}:${now.getMinutes().toString().padStart(2, '0')}</td>
          <td>${now.getMonth() + 1}</td>
          <td>${now.getFullYear()}</td>
        </tr>
      `;

      if (table.querySelector('.text-muted')) table.innerHTML = '';
      table.insertAdjacentHTML('afterbegin', row);
    }
  </script>
</body>
</html>
