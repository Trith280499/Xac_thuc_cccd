<!DOCTYPE html>
<html lang="vi">

<head>
  <meta charset="UTF-8">
  <title>Reset Tài Khoản Sinh Viên</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  <style>
    body {
      background-color: #f1f3f5;
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    }

    .card {
      max-width: 800px;
      margin: 50px auto;
      border-radius: 15px;
    }

    .form-section {
      background-color: #f8f9fa;
      padding: 15px;
      border-radius: 10px;
      margin-bottom: 20px;
    }

    .form-section h5 {
      margin-bottom: 15px;
      color: #495057;
    }

    .btn-reset {
      font-size: 1.1rem;
      font-weight: 500;
    }

    table th,
    table td {
      vertical-align: middle;
    }
  </style>
</head>

<body>

  <div class="container">
    <div class="card shadow-lg p-4">
      <h3 class="text-center mb-4 text-primary">Xác Nhận & Reset Tài Khoản Sinh Viên</h3>

      <form id="form2" action="/reset/confirm" method="POST">
        @csrf

        <!-- Thông tin cá nhân -->
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

        <!-- Bảng chọn tài khoản -->
        <div class="form-section">
          <h5>Chọn tài khoản cần reset</h5>
          <table class="table table-bordered">
            <thead class="table-light">
              <tr>
                <th>Loại tài khoản</th>
                <th>Tài khoản</th>
                <th>Thao tác</th>
              </tr>
            </thead>
            <tbody>
              <tr>
  <td>📧 Email sinh viên (@student.hcmue.edu.vn)</td>
  <td>
    <input type="text" class="form-control" value="{{ $edu->tai_khoan ?? '' }}" readonly>
    <small>Mật khẩu: <b>{{ $edu->mat_khau ?? '' }}</b></small><br>
    <small>Lần reset: {{ $edu->ngay_reset ?? 'Chưa có' }}</small>
  </td>
  <td class="text-center"><input type="checkbox" name="reset_email"></td>
</tr>

<tr>
  <td>📝 Tài khoản VLE (học trực tuyến)</td>
  <td>
    <input type="text" class="form-control" value="{{ $vle->tai_khoan ?? '' }}" readonly>
    <small>Mật khẩu: <b>{{ $vle->mat_khau ?? '' }}</b></small><br>
    <small>Lần reset: {{ $vle->ngay_reset ?? 'Chưa có' }}</small>
  </td>
  <td class="text-center"><input type="checkbox" name="reset_moodle"></td>
</tr>

<tr>
  <td>🖥️ Tài khoản Microsoft Team</td>
  <td>
    <input type="text" class="form-control" value="{{ $msteam->tai_khoan ?? '' }}" readonly>
    <small>Mật khẩu: <b>{{ $msteam->mat_khau ?? '' }}</b></small><br>
    <small>Lần reset: {{ $msteam->ngay_reset ?? 'Chưa có' }}</small>
  </td>
  <td class="text-center"><input type="checkbox" name="reset_portal"></td>
</tr>

            </tbody>
          </table>
        </div>

        <!-- Nút Reset -->
        <div class="text-center">
          <button type="submit" class="btn btn-danger w-100 btn-reset">
            🔄 Reset các tài khoản đã chọn
          </button>
        </div>
      </form>
    </div>
  </div>

</body>

</html>