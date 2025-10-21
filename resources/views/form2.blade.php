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

    .form-section h5 {
      margin-bottom: 15px;
      color: #495057;
    }

    .btn-reset {
      font-size: 1.1rem;
      font-weight: 500;
    }

    table {
      border-radius: 10px;
      overflow: hidden;
    }

    table th {
      background-color: #e9ecef;
      text-align: center;
      vertical-align: middle;
      font-weight: 600;
    }

    table td {
      vertical-align: middle;
      word-wrap: break-word;
      word-break: break-word;
    }

    @media (max-width: 768px) {
      .table-responsive {
        border-radius: 10px;
        overflow-x: auto;
      }
      table td img {
        width: 22px;
      }
      table th, table td {
        font-size: 0.9rem;
        white-space: nowrap;
      }
      .btn-reset {
        font-size: 1rem;
      }
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

        <!-- Bảng chọn tài khoản -->
        <div class="form-section">
          <h5>Chọn tài khoản cần reset</h5>
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
                    Tài khoản Microsoft Team<br>
                    <small class="text-muted">(MSSV@student.hcmue.edu.vn)</small>
                  </td>
                  <td><input type="text" name="email_account" class="form-control" value="{{ $edu->email ?? '' }}" placeholder="Nhập email"></td>
                  <td class="text-center"><input type="checkbox" name="reset_email"></td>
                </tr>
                <tr>
                  <td>
                    <!-- <img src="{{ asset('images/vle.png') }}" alt="VLE" width="26" class="me-2"> -->
                     📝
                    Tài khoản VLE (học trực tuyến)
                  </td>
                  <td><input type="text" name="moodle_account" class="form-control" value="{{ $vle->username ?? '' }}" placeholder="Nhập tên đăng nhập"></td>
                  <td class="text-center"><input type="checkbox" name="reset_moodle"></td>
                </tr>
                <tr>
                  <td>
                    <!-- <img src="{{ asset('images/portal.png') }}" alt="Portal" width="26" class="me-2"> -->
                     👨‍🎓
                    Tài khoản Online (MSSV)
                  </td>
                  <td><input type="text" name="portal_account" class="form-control" value="{{ $msteam->username ?? '' }}" placeholder="Nhập tài khoản Portal"></td>
                  <td class="text-center"><input type="checkbox" name="reset_portal"></td>
                </tr>
              </tbody>
            </table>
          </div>
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
