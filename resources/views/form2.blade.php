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
    <!-- test truyền ảnh thẻ -->
@if(!empty($decodedBase64))
  <div class="text-center mb-4">
    <img src="{{ $decodedBase64 }}" 
         alt="Ảnh CCCD" 
         class="img-fluid rounded shadow-sm" 
         style="max-height: 280px; border: 1px solid #dee2e6;">
    <p class="text-muted mt-2">Ảnh CCCD đã tải lên</p>
  </div>
@endif
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
          <td><img src="{{ asset('images/teams.png') }}" alt="Teams" width="26" class="me-2"> Microsoft Teams</td>
          <td>
            <input type="text" class="form-control" 
                   value="{{ $eduAccounts->first()->tai_khoan ?? '' }}" readonly>
          </td>
          <td class="text-center">
            <span class="status-text text-primary" onclick="recoverAccount(this, 'Teams')">Khôi phục</span>
          </td>
        </tr>

        <tr>
          <td>📝 VLE (học trực tuyến)</td>
          <td>
            <input type="text" class="form-control" 
                   value="{{ $vleAccounts->first()->tai_khoan ?? '' }}" readonly>
          </td>
          <td class="text-center">
            <span class="status-text text-primary" onclick="recoverAccount(this, 'VLE')">Khôi phục</span>
          </td>
        </tr>

        <tr>
          <td>👨‍🎓 Portal (MSSV)</td>
          <td>
            <input type="text" class="form-control" 
                   value="{{ $msteamAccounts->first()->tai_khoan ?? '' }}" readonly>
          </td>
          <td class="text-center">
            <span class="status-text text-primary" onclick="recoverAccount(this, 'Portal')">Khôi phục</span>
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
          <th>Tài khoản</th>
          <th>Mật khẩu</th>
          <th>Ngày reset</th>
        </tr>
      </thead>
      <tbody>
        {{-- EDU --}}
        @foreach ($eduAccounts as $acc)
        <tr>
          <td>Microsoft Teams</td>
          <td>{{ $acc->tai_khoan }}</td>
          <td>{{ $acc->mat_khau }}</td>
          <td>{{ $acc->ngay_reset ?? '---' }}</td>
        </tr>
        @endforeach

        {{-- VLE --}}
        @foreach ($vleAccounts as $acc)
        <tr>
          <td>VLE</td>
          <td>{{ $acc->tai_khoan }}</td>
          <td>{{ $acc->mat_khau }}</td>
          <td>{{ $acc->ngay_reset ?? '---' }}</td>
        </tr>
        @endforeach

        {{-- MSTeams --}}
        @foreach ($msteamAccounts as $acc)
        <tr>
          <td>Portal</td>
          <td>{{ $acc->tai_khoan }}</td>
          <td>{{ $acc->mat_khau }}</td>
          <td>{{ $acc->ngay_reset ?? '---' }}</td>
        </tr>
        @endforeach

        @if ($eduAccounts->isEmpty() && $vleAccounts->isEmpty() && $msteamAccounts->isEmpty())
        <tr class="text-center text-muted">
          <td colspan="4">Chưa có lịch sử khôi phục nào</td>
        </tr>
        @endif
      </tbody>
    </table>
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
