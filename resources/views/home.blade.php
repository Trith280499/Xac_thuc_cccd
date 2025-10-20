<!-- resources/views/home.blade.php -->
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Trang chủ</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
</head>
<body class="bg-light">
<div class="container mt-5">
    <div class="card p-4 shadow-sm text-center">
        <h2 class="mb-4">Chào mừng bạn đến hệ thống xác thực</h2>
       <form action="{{ url('/cccd') }}" method="POST">
            @csrf
            <div class="mb-3 text-start">
                <label for="cccd" class="form-label fw-bold">Nhập CCCD của bạn:</label>
               <input type="text" id="text" name="text" class="form-control" placeholder="Nhập số CCCD..." required>
            </div>
            <button type="submit" class="btn btn-primary w-100">Đăng Nhập</button>
        </form>

        <p class="mt-4">
            Bạn chưa xác thực? 
            <a href="{{ url('/reset-form') }}" class="text-decoration-none">Nhấn vào đây để xác thực ngay</a>
        </p>
    </div>
</div>
</body>
</html>
