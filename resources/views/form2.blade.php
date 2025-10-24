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

    .status-disabled {
      color: #6c757d !important;
      cursor: not-allowed;
      text-decoration: none;
    }

    .status-disabled:hover {
      color: #6c757d !important;
      text-decoration: none;
    }

    .reset-info {
      font-size: 0.875rem;
      color: #dc3545;
      margin-top: 5px;
    }

    .account-badge {
      background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
      color: white;
      padding: 8px 16px;
      border-radius: 20px;
      font-weight: 600;
      display: inline-block;
      margin: 5px 0;
    }

    .password-display {
      font-family: 'Courier New', monospace;
      font-size: 1.2rem;
      font-weight: bold;
      background: #f8f9fa;
      padding: 12px;
      border-radius: 8px;
      border: 2px dashed #0d6efd;
      text-align: center;
      margin: 10px 0;
    }

    .modal-success {
      border-top: 4px solid #198754;
    }

    .modal-warning {
      border-top: 4px solid #ffc107;
    }

    .modal-danger {
      border-top: 4px solid #dc3545;
    }

    .countdown-timer {
      font-size: 2rem;
      font-weight: bold;
      color: #0d6efd;
      text-align: center;
      margin: 15px 0;
    }
  </style>
  <meta name="csrf-token" content="{{ csrf_token() }}">
</head>

<body>
  <!-- Modal Thông báo thành công -->
  <div class="modal fade" id="successModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content modal-success">
        <div class="modal-header bg-success text-white">
          <h5 class="modal-title">✅ Thành công</h5>
          <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body text-center">
          <div class="mb-3">
            <i class="fas fa-check-circle fa-3x text-success"></i>
          </div>
          <h5 class="mb-3">Khôi phục tài khoản thành công!</h5>
          <div class="account-badge" id="successAccount"></div>
          <div class="password-display mt-3">
            Mật khẩu mới: <span id="successPassword"></span>
          </div>
          <p class="text-muted mt-3">Bạn sẽ được chuyển hướng sau <span id="countdown">3</span> giây...</p>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-success" onclick="redirectToForm3()">Chuyển ngay</button>
        </div>
      </div>
    </div>
  </div>

  <!-- Modal Xác nhận reset -->
  <div class="modal fade" id="confirmModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-header bg-warning">
          <h5 class="modal-title">⚠️ Xác nhận khôi phục</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body text-center">
          <div class="mb-3">
            <i class="fas fa-exclamation-triangle fa-2x text-warning"></i>
          </div>
          <h5 class="mb-3">Bạn có chắc muốn khôi phục tài khoản?</h5>
          <div class="account-badge" id="confirmAccount"></div>
          <p class="text-muted mt-3">Mật khẩu mới sẽ được tạo tự động và hiển thị ở bước tiếp theo.</p>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy bỏ</button>
          <button type="button" class="btn btn-warning" id="confirmResetBtn">Xác nhận khôi phục</button>
        </div>
      </div>
    </div>
  </div>

  <!-- Modal Thông báo lỗi -->
  <div class="modal fade" id="errorModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content modal-danger">
        <div class="modal-header bg-danger text-white">
          <h5 class="modal-title">❌ Không thể khôi phục</h5>
          <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body text-center">
          <div class="mb-3">
            <i class="fas fa-times-circle fa-3x text-danger"></i>
          </div>
          <h5 class="mb-3" id="errorTitle">Tài khoản đã được reset gần đây</h5>
          <div class="account-badge" id="errorAccount"></div>
          <div class="alert alert-warning mt-3">
            <i class="fas fa-clock me-2"></i>
            <span id="errorMessage"></span>
          </div>
          <div class="countdown-timer">
            <i class="fas fa-hourglass-half me-2"></i>
            <span id="nextResetTime"></span>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
        </div>
      </div>
    </div>
  </div>

  <!-- Modal Loading -->
  <div class="modal fade" id="loadingModal" tabindex="-1" data-bs-backdrop="static">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-body text-center">
          <div class="spinner-border text-primary mb-3" style="width: 3rem; height: 3rem;" role="status">
            <span class="visually-hidden">Đang xử lý...</span>
          </div>
          <h5>Đang khôi phục tài khoản...</h5>
          <p class="text-muted" id="loadingMessage">Vui lòng chờ trong giây lát</p>
        </div>
      </div>
    </div>
  </div>

  <div class="container">
    <div class="card shadow-lg p-4">
      <h3 class="text-center mb-4 text-primary">XÁC NHẬN VÀ KHÔI PHỤC TÀI KHOẢN SINH VIÊN</h3>
      
      <!-- @if(!empty($decodedBase64))
      <div class="text-center mb-4">
        <img src="{{ $decodedBase64 }}"
          alt="Ảnh CCCD"
          class="img-fluid rounded shadow-sm"
          style="max-height: 280px; border: 1px solid #dee2e6;">
        <p class="text-muted mt-2">Ảnh CCCD đã tải lên</p>
      </div>
      @endif -->

      @if(!empty($imageUrl))
      <div class="text-center mb-4">
        <img src="{{ $imageUrl }}"
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
            <input type="text" name="hoten" class="form-control" value="{{ $cccdData->ho_ten ?? '' }}" placeholder="Nhập họ và tên" required disabled>
          </div>
          <div class="mb-3">
            <label class="form-label fw-bold">Căn cước công dân</label>
            <input type="text" name="cccd" class="form-control" value="{{ $cccdData->so_cccd ?? '' }}" placeholder="Nhập số CCCD" required disabled>
          </div>
          <div class="mb-3">
            <label class="form-label fw-bold">Mã số sinh viên</label>
            <input type="text" name="mssv" class="form-control" value="{{ $sv->mssv ?? '' }}" placeholder="Nhập MSSV" required disabled>
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
                  <td><img src="{{ asset('images/teams.png') }}" alt="Teams" width="16" class="me-2"> Microsoft Teams</td>
                  <td>
                    <input type="text" class="form-control"
                      value="{{ $eduAccounts->first()->tai_khoan ?? '' }}" readonly disabled>
                    <div class="reset-info" id="teams-info"></div>
                  </td>
                  <td class="text-center">
                    <span class="status-text text-primary" 
                           data-username="{{ $eduAccounts->first()->tai_khoan ?? '' }}" 
                           data-type="Teams"
                           onclick="showConfirmModal(this)">🔄</span>
                  </td>
                </tr>

                <tr>
                  <td>📝 VLE (học trực tuyến)</td>
                  <td>
                    <input type="text" class="form-control"
                      value="{{ $vleAccounts->first()->tai_khoan ?? '' }}" readonly disabled>
                    <div class="reset-info" id="vle-info"></div>
                  </td>
                  <td class="text-center">
                    <span class="status-text text-primary" 
                           data-username="{{ $vleAccounts->first()->tai_khoan ?? '' }}" 
                           data-type="VLE"
                           onclick="showConfirmModal(this)">🔄</span>
                  </td>
                </tr>

                <tr>
                  <td>👨‍🎓 Portal (MSSV)</td>
                  <td>
                    <input type="text" class="form-control"
                      value="{{ $msteamAccounts->first()->tai_khoan ?? '' }}" readonly disabled>
                    <div class="reset-info" id="portal-info"></div>
                  </td>
                  <td class="text-center">
                    <span class="status-text text-primary" 
                           data-username="{{ $msteamAccounts->first()->tai_khoan ?? '' }}" 
                           data-type="Portal"
                           onclick="showConfirmModal(this)">🔄</span>
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
                  <td>{{ $edu_new_password ?? $acc->mat_khau }}</td>
                  <td>{{ $acc->ngay_reset ?? '---' }}</td>
                </tr>
                @endforeach

                {{-- VLE --}}
                @foreach ($vleAccounts as $acc)
                <tr>
                  <td>VLE</td>
                  <td>{{ $acc->tai_khoan }}</td>
                  <td>{{ $vle_new_password ?? $acc->mat_khau }}</td>
                  <td>{{ $acc->ngay_reset ?? '---' }}</td>
                </tr>
                @endforeach

                {{-- MSTeams --}}
                @foreach ($msteamAccounts as $acc)
                <tr>
                  <td>Portal</td>
                  <td>{{ $acc->tai_khoan }}</td>
                  <td>{{ $portal_new_password ?? $acc->mat_khau }}</td>
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
      </form>
    </div>
  </div>

  <!-- Add Font Awesome for icons -->
  <script src="https://kit.fontawesome.com/your-fontawesome-kit.js" crossorigin="anonymous"></script>

  <script>
    let currentAccount = null;
    let redirectData = null;
    let countdownInterval = null;

    // Hiển thị modal xác nhận
    function showConfirmModal(element) {
      if (element.classList.contains('status-disabled')) {
        return;
      }

      const username = element.getAttribute('data-username');
      const type = element.getAttribute('data-type');
      
      currentAccount = { element, username, type };
      
      // Hiển thị thông tin tài khoản trong modal
      document.getElementById('confirmAccount').textContent = `${username} (${type})`;
      
      const confirmModal = new bootstrap.Modal(document.getElementById('confirmModal'));
      confirmModal.show();
    }

    // Xác nhận reset
    document.getElementById('confirmResetBtn').addEventListener('click', function() {
      const confirmModal = bootstrap.Modal.getInstance(document.getElementById('confirmModal'));
      confirmModal.hide();
      
      performReset(currentAccount);
    });

    // Thực hiện reset
    async function performReset(account) {
      const loadingModal = new bootstrap.Modal(document.getElementById('loadingModal'));
      loadingModal.show();

      try {
        const response = await fetch('/form2/reset-password', {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'Accept': 'application/json'
          },
          body: JSON.stringify({
            type: account.type,
            username: account.username
          })
        });

        const data = await response.json();
        loadingModal.hide();

        if (data.success) {
          // Cập nhật trạng thái nút
          account.element.textContent = '✅';
          account.element.classList.add('status-success');
          
          // Hiển thị modal thành công
          showSuccessModal(data);
        } else {
          // Hiển thị modal lỗi
          showErrorModal(data, account);
        }
      } catch (error) {
        console.error('Error:', error);
        loadingModal.hide();
        
        showErrorModal({
          message: 'Có lỗi xảy ra khi kết nối đến server'
        }, account);
      }
    }

    // Hiển thị modal thành công
    function showSuccessModal(data) {
      document.getElementById('successAccount').textContent = `${data.username} (${data.type})`;
      document.getElementById('successPassword').textContent = data.password;
      
      redirectData = data;
      
      // Bắt đầu đếm ngược
      let countdown = 3;
      document.getElementById('countdown').textContent = countdown;
      
      countdownInterval = setInterval(() => {
        countdown--;
        document.getElementById('countdown').textContent = countdown;
        
        if (countdown <= 0) {
          redirectToForm3();
        }
      }, 1000);
      
      const successModal = new bootstrap.Modal(document.getElementById('successModal'));
      successModal.show();
    }

    // Hiển thị modal lỗi
    function showErrorModal(data, account) {
      document.getElementById('errorTitle').textContent = data.message || 'Không thể khôi phục tài khoản';
      document.getElementById('errorAccount').textContent = `${account.username} (${account.type})`;
      document.getElementById('errorMessage').textContent = data.message || 'Vui lòng thử lại sau';
      
      if (data.next_reset_date) {
        document.getElementById('nextResetTime').textContent = data.next_reset_date;
      } else {
        document.getElementById('nextResetTime').textContent = 'Không xác định';
      }
      
      // Vô hiệu hóa nút nếu chưa đủ thời gian
      if (data.remaining_days > 0) {
        account.element.classList.add('status-disabled');
        account.element.textContent = '⏳';
        
        // Cập nhật thông tin trên form
        const infoId = `${account.type.toLowerCase()}-info`;
        const infoElement = document.getElementById(infoId);
        if (infoElement && data.next_reset_date) {
          infoElement.innerHTML = `<small>Có thể reset lại vào: ${data.next_reset_date}</small>`;
        }
      }
      
      const errorModal = new bootstrap.Modal(document.getElementById('errorModal'));
      errorModal.show();
    }

    // Chuyển hướng đến form3
    function redirectToForm3() {
      if (countdownInterval) {
        clearInterval(countdownInterval);
      }
      
      if (redirectData) {
        window.location.href = `/form3/view?username=${redirectData.username}&password=${redirectData.password}&type=${redirectData.type}`;
      }
    }

    // Kiểm tra trạng thái reset khi trang load
    async function checkResetStatusOnLoad() {
      const buttons = [
        { type: 'Teams', infoId: 'teams-info' },
        { type: 'VLE', infoId: 'vle-info' },
        { type: 'Portal', infoId: 'portal-info' }
      ];

      for (const button of buttons) {
        const element = document.querySelector(`[data-type="${button.type}"]`);
        const infoElement = document.getElementById(button.infoId);
        
        if (element && element.getAttribute('data-username')) {
          const username = element.getAttribute('data-username');
          
          try {
            const response = await fetch('/form2/check-reset-status', {
              method: 'POST',
              headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Accept': 'application/json'
              },
              body: JSON.stringify({
                type: button.type,
                username: username
              })
            });
            
            const data = await response.json();
            
            if (!data.can_reset) {
              // Vô hiệu hóa nút và hiển thị thông tin
              element.classList.add('status-disabled');
              element.textContent = '⏳';
              
              // Hiển thị thông tin thời gian
              if (infoElement) {
                infoElement.innerHTML = `<small>${data.message}</small>`;
              }
            }
          } catch (error) {
            console.error('Error checking reset status:', error);
          }
        }
      }
    }

    // Khởi tạo khi trang load
    document.addEventListener('DOMContentLoaded', function() {
      checkResetStatusOnLoad();
    });
  </script>
</body>
</html>