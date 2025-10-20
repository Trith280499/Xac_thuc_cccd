<!DOCTYPE html>
<html lang="vi">
<head>
  <meta charset="UTF-8">
  <title>Reset Thành Công</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body {
      background-color: #f1f3f5;
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    }
    .btn-home {
      background-color: #124874; /* Xanh Cerulean */
      color: #fff;
      padding: 10px 20px;
      border-radius: 8px;
      text-decoration: none;
      font-weight: 500;
    }
    .btn-home:hover {
      background-color: #0f3a60; /* Xanh đậm hơn khi hover */
      color: #fff;
    }
    .logo {
      width: 120px;
      margin-bottom: 20px;
    }
    .text-user {
      color: #124874; /* Xanh Cerulean cho user */
      font-weight: 600;
    }
    .text-pass {
      color: #CF373D; /* Đỏ Jasper cho mật khẩu */
      font-weight: 600;
    }
  </style>
</head>
<body>

<div class="container d-flex justify-content-center align-items-center vh-100">
    <div class="card p-5 text-center shadow-sm">
        <!-- Logo trường -->
        <img src="/mnt/data/9d7845e9-2069-4742-94fc-3980c5b83351.png" alt="Logo SPHCM" class="logo">

        <h2 class="mb-3" style="color:#124874;">🎉 Reset Thành Công!</h2>
        <p class="mb-4">Tài khoản của bạn đã được reset thành công. Dưới đây là thông tin đăng nhập mới:</p>
        <div class="mb-3">
            <strong>User:</strong> <span class="text-user">student001</span>
        </div>
        <div class="mb-4">
            <strong>Mật khẩu:</strong> <span class="text-pass">12345678</span>
        </div>
        <a href="/" class="btn btn-home">Quay về trang chủ</a>
    </div>
</div>

</body>
</html>