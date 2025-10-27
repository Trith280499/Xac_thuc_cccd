<!DOCTYPE html>
<html lang="vi">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>Quản lý xét duyệt - HCMUE</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

  <style>
    /* Giữ nguyên CSS như trước */
    :root {
      --primary-color: #124874;
      --accent-color: #CF373D;
      --success-color: #28a745;
      --warning-color: #ffc107;
      --danger-color: #dc3545;
    }

    body {
      background-color: #f6f8fa;
      font-family: 'Segoe UI', Tahoma, sans-serif;
    }

    .navbar {
      background: linear-gradient(135deg, var(--primary-color), #0d3a5c);
      box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
    }

    .dashboard-card {
      background: #fff;
      border-radius: 12px;
      box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
      transition: transform 0.3s, box-shadow 0.3s;
      border-left: 4px solid var(--primary-color);
      margin-bottom: 15px;
    }

    .dashboard-card:hover {
      transform: translateY(-2px);
      box-shadow: 0 6px 14px rgba(0, 0, 0, 0.12);
    }

    .status-badge {
      padding: 6px 12px;
      border-radius: 20px;
      font-size: 0.85rem;
      font-weight: 500;
      white-space: nowrap;
    }

    .status-pending {
      background-color: rgba(255, 193, 7, 0.15);
      color: #856404;
    }

    .status-approved {
      background-color: rgba(40, 167, 69, 0.15);
      color: #155724;
    }

    .status-rejected {
      background-color: rgba(220, 53, 69, 0.15);
      color: #721c24;
    }

    .filter-btn.active {
      background-color: var(--primary-color);
      color: white;
    }

    .user-avatar {
      width: 40px;
      height: 40px;
      border-radius: 50%;
      background: linear-gradient(135deg, var(--primary-color), var(--accent-color));
      display: flex;
      align-items: center;
      justify-content: center;
      color: white;
      font-weight: bold;
      font-size: 1rem;
      flex-shrink: 0;
    }

    .action-btn {
      padding: 6px 12px;
      border-radius: 6px;
      font-size: 0.85rem;
      transition: all 0.2s;
      white-space: nowrap;
    }

    .btn-approve {
      background-color: rgba(40, 167, 69, 0.1);
      color: var(--success-color);
      border: 1px solid var(--success-color);
    }

    .btn-approve:hover {
      background-color: var(--success-color);
      color: white;
    }

    .btn-reject {
      background-color: rgba(220, 53, 69, 0.1);
      color: var(--danger-color);
      border: 1px solid var(--danger-color);
    }

    .btn-reject:hover {
      background-color: var(--danger-color);
      color: white;
    }

    .detail-modal .modal-header {
      background: linear-gradient(135deg, var(--primary-color), #0d3a5c);
      color: white;
    }

    .info-card {
      background-color: #f8f9fa;
      border-radius: 8px;
      padding: 15px;
      margin-bottom: 15px;
    }

    .stats-card {
      text-align: center;
      padding: 15px;
      border-radius: 10px;
      color: white;
      margin-bottom: 15px;
      min-height: 100px;
      display: flex;
      flex-direction: column;
      justify-content: center;
    }

    .stats-total {
      background: linear-gradient(135deg, #6c757d, #495057);
    }

    .stats-pending {
      background: linear-gradient(135deg, #ffc107, #e0a800);
    }

    .stats-approved {
      background: linear-gradient(135deg, #28a745, #1e7e34);
    }

    .stats-rejected {
      background: linear-gradient(135deg, #dc3545, #bd2130);
    }

    .search-box {
      position: relative;
    }

    .search-box input {
      padding-left: 40px;
    }

    .search-box i {
      position: absolute;
      left: 15px;
      top: 12px;
      color: #6c757d;
    }

    .application-item {
      display: flex;
      align-items: center;
      padding: 12px 15px;
      border-bottom: 1px solid #eee;
    }

    .application-item:last-child {
      border-bottom: none;
    }

    .application-info {
      flex: 1;
      min-width: 0;
    }

    .application-actions {
      flex-shrink: 0;
      margin-left: 15px;
    }

    @media (max-width: 992px) {
      .image-preview {
        max-width: 100%;
        height: auto;
      }

    }

    /* Responsive adjustments */
    @media (max-width: 768px) {
      .stats-card {
        min-height: 80px;
        padding: 12px;
      }

      .stats-card h3 {
        font-size: 1.5rem;
      }

      .application-item {
        flex-direction: column;
        align-items: flex-start;
      }

      .application-actions {
        margin-left: 0;
        margin-top: 10px;
        width: 100%;
        display: flex;
        justify-content: flex-end;
      }

      .filter-buttons {
        overflow-x: auto;
        white-space: nowrap;
        padding-bottom: 10px;
      }

      .filter-buttons .btn-group {
        flex-wrap: nowrap;
      }

      .info-item {
        flex-direction: column;
      }

      .info-label {
        min-width: auto;
        margin-bottom: 4px;
      }

      .info-value {
        text-align: left;
      }
    }

    @media (max-width: 576px) {
      .container {
        padding-left: 10px;
        padding-right: 10px;
      }

      .dashboard-card {
        margin-left: -5px;
        margin-right: -5px;
        border-radius: 8px;
      }

      .modal-dialog {
        margin: 10px;
      }
    }

    .loading-spinner {
      display: flex;
      justify-content: center;
      align-items: center;
      height: 200px;
    }
.tk-grid-row {
  display: grid;
  grid-template-columns: 40px 6fr 6fr; /* 3 cột: checkbox | mô tả | input */
  align-items: center;
  gap: 10px;
  padding: 6px 4px;
}

/* ✅ Header style */
.header-row {
  border-bottom: 2px solid #ddd;
  padding-bottom: 4px;
  margin-bottom: 8px;
}

/* ✅ Dòng dữ liệu */
#checkboxContainer .tk-grid-row {
  border-bottom: 1px solid #eee;
}

/* Checkbox giữa cột */
.col-checkbox {
  text-align: center;
}

/* Cột mô tả */
.col-info label,
.header-row .col-info {
  font-size: 0.9rem;
  color: #333;
  line-height: 1.3;
  white-space: nowrap;
  overflow: hidden;
  text-overflow: ellipsis;
}

/* Input tên tài khoản */
.col-input .tk-name-input {
  width: 100%;
}

/* Hiệu ứng hiện input */
.tk-name-input {
  opacity: 0;
  transform: translateY(-5px);
  transition: all 0.2s ease;
}
.tk-name-input[style*="display: block"] {
  opacity: 1;
  transform: translateY(0);
}

/* Checkbox rõ ràng */
.custom-checkbox {
  width: 18px;
  height: 18px;
  cursor: pointer;
  accent-color: #0d6efd;
  border: 2px solid #0d6efd;
  border-radius: 4px;
  transition: 0.2s ease;
}
.custom-checkbox:checked {
  background-color: #0d6efd;
  border-color: #0d6efd;
}

/* Hover dòng */
#checkboxContainer .tk-grid-row:hover {
  background-color: #f8f9fa;
}

/* ========== CẢI THIỆN UI THÔNG TIN CHUNG ========== */
.info-card {
  background: white;
  border-radius: 10px;
  box-shadow: 0 2px 8px rgba(0,0,0,0.08);
  padding: 20px;
  margin-bottom: 20px;
  border-left: 4px solid var(--primary-color);
  transition: all 0.3s ease;
}

.info-card:hover {
  box-shadow: 0 4px 12px rgba(0,0,0,0.12);
  transform: translateY(-2px);
}

.info-card h6 {
  color: var(--primary-color);
  font-weight: 600;
  margin-bottom: 16px;
  padding-bottom: 8px;
  border-bottom: 1px solid #eaeaea;
  display: flex;
  align-items: center;
}

.info-card h6 i {
  margin-right: 8px;
  font-size: 1.1rem;
}

.info-grid {
  display: grid;
  grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
  gap: 12px;
}

.info-item {
  display: flex;
  align-items: center;
  padding: 8px 0;
  border-bottom: 1px solid #f0f0f0;
}

.info-item:last-child {
  border-bottom: none;
}

.info-label {
  font-weight: 600;
  color: #495057;
  min-width: 160px;
  font-size: 0.9rem;
}

.info-value {
  color: #212529;
  font-weight: 500;
  flex: 1;
}

.info-icon {
  width: 32px;
  height: 32px;
  border-radius: 8px;
  background: rgba(18, 72, 116, 0.1);
  display: flex;
  align-items: center;
  justify-content: center;
  margin-right: 12px;
  color: var(--primary-color);
  font-size: 0.9rem;
}

@media (max-width: 768px) {
  .info-grid {
    grid-template-columns: 1fr;
  }
  
  .info-item {
    flex-direction: column;
    align-items: flex-start;
    padding: 10px 0;
  }
  
  .info-label {
    min-width: auto;
    margin-bottom: 4px;
    font-size: 0.85rem;
  }
  
  .info-value {
    font-size: 0.95rem;
  }
  
  .info-icon {
    display: none;
  }
}

  </style>
</head>

<body>
  <!-- Navbar -->
  <nav class="navbar navbar-expand-lg navbar-dark">
    <div class="container">
      <a class="navbar-brand d-flex align-items-center" href="#">
        <img src="{{ asset('images/Logo HCMUE.png') }}" alt="Logo HCMUE" height="40" class="me-2">
        <span class="fw-bold">HCMUE - QUẢN LÝ XÉT DUYỆT</span>
      </a>

      <!-- Thêm button chuyển hướng tới trang quản lý loại tài khoản -->
      <div class="navbar-nav ms-auto">
        <a href="{{ route('quan-ly-loai-tai-khoan') }}" class="btn btn-outline-light me-2">
          <i class="fas fa-cog me-1"></i> Quản lý loại tài khoản
        </a>
        <!-- <div class="navbar-nav ms-auto">
        <div class="nav-item dropdown">
          <a class="nav-link dropdown-toggle d-flex align-items-center" href="#" role="button" data-bs-toggle="dropdown">
            <div class="user-avatar me-2">AD</div>
            <span>Quản trị viên</span>
          </a>
          <ul class="dropdown-menu">
            <li><a class="dropdown-item" href="#"><i class="fas fa-user me-2"></i>Tài khoản</a></li>
            <li><a class="dropdown-item" href="#"><i class="fas fa-cog me-2"></i>Cài đặt</a></li>
            <li><hr class="dropdown-divider"></li>
            <li><a class="dropdown-item" href="#"><i class="fas fa-sign-out-alt me-2"></i>Đăng xuất</a></li>
          </ul>
        </div> -->
      </div>
    </div>
  </nav>

  <div class="container py-4">
    <!-- Header -->
    <div class="row mb-4">
      <div class="col-md-8"></div>
      <div class="col-md-4">
        <div class="search-box">
          <i class="fas fa-search"></i>
          <input type="text" class="form-control" id="searchInput" placeholder="Tìm kiếm theo MSSV, CCCD...">
        </div>
      </div>
    </div>

    <!-- Statistics -->
    <div class="row mb-4">
      <div class="col-md-3 col-sm-6">
        <div class="stats-card stats-total">
          <h3 id="totalCount">0</h3>
          <p>Tổng yêu cầu</p>
        </div>
      </div>
      <div class="col-md-3 col-sm-6">
        <div class="stats-card stats-pending">
          <h3 id="pendingCount">0</h3>
          <p>Đang chờ</p>
        </div>
      </div>
      <div class="col-md-3 col-sm-6">
        <div class="stats-card stats-approved">
          <h3 id="approvedCount">0</h3>
          <p>Đã duyệt</p>
        </div>
      </div>
      <div class="col-md-3 col-sm-6">
        <div class="stats-card stats-rejected">
          <h3 id="rejectedCount">0</h3>
          <p>Đã từ chối</p>
        </div>
      </div>
    </div>

    <!-- Filters -->
    <div class="d-flex mb-4 flex-wrap">
      <div class="btn-group me-3 filter-buttons">
        <button class="btn btn-outline-primary filter-btn active" data-filter="all">Tất cả</button>
        <button class="btn btn-outline-primary filter-btn" data-filter="pending">Chờ duyệt</button>
        <button class="btn btn-outline-primary filter-btn" data-filter="approved">Đã duyệt</button>
        <button class="btn btn-outline-primary filter-btn" data-filter="rejected">Từ chối</button>
      </div>
      <div class="ms-auto mt-2 mt-md-0">
        <button class="btn btn-light me-2" id="refreshBtn"><i class="fas fa-sync-alt me-1"></i> Làm mới</button>
      </div>
    </div>

    <!-- Applications List -->
    <div id="applicationsList" class="dashboard-card">
      <div class="loading-spinner">
        <div class="spinner-border text-primary" role="status">
          <span class="visually-hidden">Đang tải...</span>
        </div>
        <span class="ms-2">Đang tải dữ liệu...</span>
      </div>
    </div>

    <!-- Pagination -->
    <div class="d-flex justify-content-between align-items-center mt-4 flex-wrap">
      <div class="text-muted mb-2 mb-md-0">Hiển thị <span id="showingCount">0</span> trên <span id="totalItems">0</span> yêu cầu</div>
      <nav>
        <ul class="pagination mb-0">
          <li class="page-item disabled" id="prevPage"><a class="page-link" href="#">Trước</a></li>
          <li class="page-item active"><a class="page-link" href="#">1</a></li>
          <li class="page-item"><a class="page-link" href="#">2</a></li>
          <li class="page-item"><a class="page-link" href="#">3</a></li>
          <li class="page-item" id="nextPage"><a class="page-link" href="#">Tiếp</a></li>
        </ul>
      </nav>
    </div>
  </div>

  <!-- Detail Modal -->
  <!-- ✅ Modal chi tiết xét duyệt -->
<div class="modal fade detail-modal" id="detailModal" tabindex="-1">
  <div class="modal-dialog modal-lg">
    <div class="modal-content p-3">
      <div class="modal-header">
        <h5 class="modal-title">Chi tiết yêu cầu xét duyệt</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
      </div>

      <div class="modal-body row gap-2 justify-content-center">
        <!-- Thông tin chung - ĐÃ ĐƯỢC CẢI THIỆN -->
        <div class="col-md-12 info-card">
          <h6><i class="fas fa-info-circle me-2"></i>Thông tin chung</h6>
          <div class="info-grid">
            <div class="info-item">
              <div class="info-icon">
                <i class="fas fa-id-card"></i>
              </div>
              <div class="info-label">MSSV:</div>
              <div class="info-value" id="detailMssv">-</div>
            </div>
            <div class="info-item">
              <div class="info-icon">
                <i class="fas fa-address-card"></i>
              </div>
              <div class="info-label">Số CCCD:</div>
              <div class="info-value" id="detailCccd">-</div>
            </div>
            <div class="info-item">
              <div class="info-icon">
                <i class="fas fa-tag"></i>
              </div>
              <div class="info-label">Trạng thái:</div>
              <div class="info-value">
                <span class="status-badge status-pending" id="detailStatusBadge">Đang chờ</span>
              </div>
            </div>
            <div class="info-item">
              <div class="info-icon">
                <i class="fas fa-paper-plane"></i>
              </div>
              <div class="info-label">Thời gian gửi:</div>
              <div class="info-value" id="detailSubmitTime">-</div>
            </div>
            <div class="info-item">
              <div class="info-icon">
                <i class="fas fa-sync-alt"></i>
              </div>
              <div class="info-label">Thời gian cập nhật:</div>
              <div class="info-value" id="detailUpdateTime">-</div>
            </div>
          </div>
        </div>

        <!-- Hình ảnh CCCD -->
        <div class="info-card">
          <h6><i class="fas fa-images me-2"></i>Hình ảnh CCCD</h6>
          <div class="row">
            <div class="col-md-12 text-center">
              <p class="mb-2">Ảnh CCCD đã tải lên</p>
              <img id="detailFrontImage" class="image-preview" style="max-height: 300px; width: auto;">
            </div>
          </div>
        </div>

        <!-- Lịch sử và ghi chú -->
        <div class="info-card">
          <h6><i class="fas fa-history me-2"></i>Lịch sử xét duyệt</h6>
          <div class="info-item">
            <div class="info-icon">
              <i class="fas fa-sticky-note"></i>
            </div>
            <div class="info-label">Ghi chú hiện tại:</div>
            <div class="info-value" id="detailCurrentNote">-</div>
          </div>
          <div class="mt-3">
            <label for="rejectReason" class="form-label">Ghi chú/Lý do (nếu từ chối)</label>
            <textarea class="form-control" id="rejectReason" rows="3" placeholder="Nhập lý do từ chối hoặc ghi chú xét duyệt..."></textarea>
            <div class="form-text">Ghi chú này sẽ được lưu lại trong lịch sử xét duyệt</div>
          </div>
        </div>

        <!-- Các loại tài khoản được xét duyệt -->
        <div class="mt-4" id="loaiTaiKhoanSection" style="display:none;">
          <h6 class="text-primary"><i class="fas fa-user-cog me-2"></i>Các loại tài khoản được xét duyệt</h6>
          <div id="loaiTaiKhoanList" class="p-2 border rounded bg-light">
            <p class="text-muted mb-0">Chưa có loại tài khoản nào được chọn.</p>
          </div>
          <div class="mt-3 text-end">
            <button class="btn btn-outline-primary btn-sm" id="suaTaiKhoanBtn">
              <i class="fas fa-edit me-1"></i> Sửa danh sách
            </button>
          </div>
        </div>
      </div>

      <div class="modal-footer">
        <button type="button" class="btn btn-light" id="cancelBtn" data-bs-dismiss="modal">Đóng</button>
        <button type="button" class="btn btn-danger btn-reject" id="rejectBtn"><i class="fas fa-times me-1"></i> Từ chối</button>
        <button type="button" class="btn btn-primary btn-add" id="addTaiKhoanBtn"><i class="fas fa-plus me-1"></i> Thêm tài khoản</button>
        <button type="button" class="btn btn-success btn-approve" id="approveBtn" style="display:none;"><i class="fas fa-check me-1"></i> Chấp nhận</button>
      </div>
    </div>
  </div>
</div>

<!-- ✅ Modal nhỏ (đặt RA NGOÀI modal lớn) -->
<div class="modal fade" id="loaiTkModal" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered modal-lg">
    <div class="modal-content p-3 shadow-lg">
      <div class="modal-header">
        <h5 class="modal-title">Xét duyệt các tài khoản cho sinh viên</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>

      <div class="modal-body">
        <div class="form-check mb-2">
          <input class="form-check-input" type="checkbox" id="selectAllCheckbox">
          <label class="form-check-label fw-semibold" for="selectAllCheckbox">Chọn tất cả</label>
        </div>
       <!-- ✅ Header 3 cột đồng bộ layout -->
<div id="checkboxHeader" class="tk-grid-row header-row">
  <div class="col-checkbox"></div>
  <div class="col-info fw-semibold text-secondary">Loại tài khoản</div>
  <div class="col-input fw-semibold text-secondary">Tên tài khoản</div>
</div>

        <div id="checkboxContainer" class="mb-2" style="max-height: 300px; overflow-y: auto;"></div>
      </div>

      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
        <button type="button" class="btn btn-success" id="confirmLoaiTkBtn"><i class="fas fa-check me-1"></i> Xác nhận</button>
      </div>
    </div>
  </div>
</div>

</div>

  <script>
    document.addEventListener('DOMContentLoaded', function() {
      const applicationsList = document.getElementById('applicationsList');
      const filterBtns = document.querySelectorAll('.filter-btn');
      const searchInput = document.getElementById('searchInput');
      const refreshBtn = document.getElementById('refreshBtn');
      const detailModal = new bootstrap.Modal(document.getElementById('detailModal'));

      let currentFilter = 'all';
      let currentApplications = [];
      let filteredApplications = [];
      const selectedAccountsMap = {}; // mỗi xét duyệt có danh sách loại TK riêng

  // === Elements trong modal ===
  const addTaiKhoanBtn = document.getElementById('addTaiKhoanBtn');
  const approveBtn = document.getElementById('approveBtn');
  const rejectBtn = document.getElementById('rejectBtn');
  const loaiTkModal = new bootstrap.Modal(document.getElementById('loaiTkModal'));

  const confirmLoaiTkBtn = document.getElementById('confirmLoaiTkBtn');
  const checkboxContainer = document.getElementById('checkboxContainer');
  const selectAllCheckbox = document.getElementById('selectAllCheckbox');
  const loaiTaiKhoanSection = document.getElementById('loaiTaiKhoanSection');
  const loaiTaiKhoanList = document.getElementById('loaiTaiKhoanList');

      // === INIT ===
      loadApplications();

      // === Bộ lọc và tìm kiếm ===
      filterBtns.forEach(btn => {
        btn.addEventListener('click', function() {
          filterBtns.forEach(b => b.classList.remove('active'));
          this.classList.add('active');
          currentFilter = this.getAttribute('data-filter');
          filterApplications();
        });
      });
      searchInput.addEventListener('input', filterApplications);
      refreshBtn.addEventListener('click', loadApplications);

      // === Khi ấn "Thêm tài khoản" ===
      addTaiKhoanBtn.addEventListener('click', async function() {
        const currentAppId = this.getAttribute('data-app-id');
        if (!currentAppId) return alert('Không xác định được bản ghi đang xét duyệt.');

        try {
          const res = await fetch('/quan-ly-xet-duyet/getAllLoaiTK');
          const result = await res.json();
          if (!result.success) {
            alert(result.message || 'Không thể tải danh sách loại tài khoản.');
            return;
          }

          renderLoaiTaiKhoanCheckbox(result.data, currentAppId);
          loaiTkModal.show();
        } catch (err) {
          console.error('Lỗi khi gọi API getAllLoaiTK:', err);
          alert('Đã xảy ra lỗi khi tải danh sách loại tài khoản.');
        }
      });

  // === Hiển thị checkbox trong modal nhỏ ===
function renderLoaiTaiKhoanCheckbox(data, appId) {
  checkboxContainer.innerHTML = '';

  data.forEach(item => {
    const div = document.createElement('div');
    div.className = 'tk-grid-row';
    div.innerHTML = `
      <div class="col-checkbox">
        <input class="form-check-input loai-tk-item custom-checkbox" type="checkbox" value="${item.id}" id="tk_${item.id}"
              data-name="${item.ten_loai}" data-desc="${item.mo_ta || ''}">
      </div>
      <div class="col-info">
        <label class="form-check-label" for="tk_${item.id}">
          <strong>${item.ten_loai}</strong> - ${item.mo_ta || ''}
        </label>
      </div>
      <div class="col-input">
        <input type="text" class="form-control form-control-sm tk-name-input" 
               placeholder="Nhập tên tài khoản..." data-id="${item.id}" style="display:none;">
      </div>
    `;
          checkboxContainer.appendChild(div);
        });

  // === Khi tick checkbox, hiện/ẩn input tương ứng ===
  const allChecks = checkboxContainer.querySelectorAll('.loai-tk-item');
  allChecks.forEach(chk => {
    const input = checkboxContainer.querySelector(`.tk-name-input[data-id="${chk.value}"]`);

    // Gán sẵn giá trị cũ nếu có
    const prev = selectedAccountsMap[appId]?.find(a => a.id == chk.value);
    if (prev) {
      chk.checked = true;
      input.value = prev.ten_tai_khoan || '';
      input.style.display = 'block';
    }

  chk.addEventListener('change', () => {
  if (chk.checked) {
    // Khi tick: hiện input và giữ placeholder "Nhập tên tài khoản"
    input.style.display = 'block';
    if (!input.value.trim()) input.placeholder = 'Nhập tên tài khoản';
    input.focus();
  } else {
    // Khi bỏ tick: ẩn input, reset lỗi + placeholder nhưng giữ lại trạng thái ban đầu
    input.style.display = 'none';
    input.classList.remove('is-invalid');
    input.value = '';
    input.placeholder = 'Nhập tên tài khoản';
  }
});

  });


  // === Logic chọn tất cả ===
  selectAllCheckbox.checked = allChecks.length > 0 && [...allChecks].every(chk => chk.checked);
  selectAllCheckbox.onchange = function () {
    allChecks.forEach(chk => {
      chk.checked = this.checked;
      const input = checkboxContainer.querySelector(`.tk-name-input[data-id="${chk.value}"]`);
      input.style.display = chk.checked ? 'block' : 'none';
    });
  };

  allChecks.forEach(chk => {
    chk.addEventListener('change', () => {
      const total = allChecks.length;
      const checkedCount = [...allChecks].filter(c => c.checked).length;
      selectAllCheckbox.checked = (checkedCount === total);
    });
  });
}


// === Khi xác nhận trong modal nhỏ ===
confirmLoaiTkBtn.addEventListener('click', function() {
  const currentAppId = addTaiKhoanBtn.getAttribute('data-app-id');
  const checkedBoxes = checkboxContainer.querySelectorAll('input.loai-tk-item:checked');
  let valid = true;
  const chosen = [];

  // Duyệt từng checkbox được tick
  checkedBoxes.forEach(cb => {
    const input = checkboxContainer.querySelector(`.tk-name-input[data-id="${cb.value}"]`);
    if (!input.value.trim()) {
      valid = false;
      input.classList.add('is-invalid');
      input.placeholder = 'Bạn cần nhập tên tài khoản';
    } else {
      input.classList.remove('is-invalid');
      chosen.push({
        id: cb.value,
        ten_loai: cb.dataset.name,
        mo_ta: cb.dataset.desc,
        ten_tai_khoan: input.value.trim()
      });
    }
  });

  if (!valid) {
    alert('Vui lòng nhập tên tài khoản cho các loại đã chọn.');
    return;
  }

  //  Nếu bỏ hết tick thì hiển thị lại nút "Thêm tài khoản", ẩn "Chấp nhận"
  if (chosen.length === 0) {
    loaiTkModal.hide();
    selectedAccountsMap[currentAppId] = [];
    renderSelectedLoaiTaiKhoan([], currentAppId);
    addTaiKhoanBtn.style.display = 'inline-block';
    approveBtn.style.display = 'none';
    return;
  }

  // Lưu dữ liệu đã chọn
  selectedAccountsMap[currentAppId] = chosen;
  loaiTkModal.hide();
  renderSelectedLoaiTaiKhoan(chosen, currentAppId);

  //  Hiển thị nút "Chấp nhận", ẩn "Thêm tài khoản"
  addTaiKhoanBtn.style.display = 'none';
  approveBtn.style.display = 'inline-block';
});



  // === Hiển thị danh sách loại tài khoản đã chọn ===
  function renderSelectedLoaiTaiKhoan(list, appId) {
  if (!list || list.length === 0) {
    loaiTaiKhoanSection.style.display = 'none';
    loaiTaiKhoanList.innerHTML = `<p class="text-muted mb-0">Chưa có loại tài khoản nào được chọn.</p>`;
    return;
  }

  loaiTaiKhoanSection.style.display = 'block';
  loaiTaiKhoanList.innerHTML = `
    <table class="table table-sm table-bordered align-middle mb-0">
      <thead class="table-light">
        <tr>
          <th>Loại tài khoản</th>
          <th>Mô tả</th>
          <th>Tên tài khoản</th>
        </tr>
      </thead>
      <tbody>
        ${list.map(item => `
          <tr>
            <td><strong>${item.ten_loai}</strong></td>
            <td>${item.mo_ta || 'Không có mô tả'}</td>
            <td>${item.ten_tai_khoan || '-'}</td>
          </tr>
        `).join('')}
      </tbody>
    </table>
  `;

  // Nút sửa
document.getElementById('suaTaiKhoanBtn').onclick = () => {
  loaiTkModal.show();

  setTimeout(() => {
    // Lấy dữ liệu hiện tại từ bên ngoài
    const currentAppId = addTaiKhoanBtn.getAttribute('data-app-id');
    const currentSelected = selectedAccountsMap[currentAppId] || [];

    // Reset tất cả trước khi áp dụng lại
    const allChecks = checkboxContainer.querySelectorAll('.loai-tk-item');
    allChecks.forEach(chk => {
      const input = checkboxContainer.querySelector(`.tk-name-input[data-id="${chk.value}"]`);
      const found = currentSelected.find(acc => acc.id == chk.value);

      if (found) {
        chk.checked = true;
        input.value = found.ten_tai_khoan || '';
        input.style.display = 'block';
      } else {
        chk.checked = false;
        input.value = '';
        input.style.display = 'none';
      }
      input.classList.remove('is-invalid');
    });

    // Cập nhật checkbox "Chọn tất cả"
    const total = allChecks.length;
    const checkedCount = [...allChecks].filter(c => c.checked).length;
    selectAllCheckbox.checked = checkedCount === total;
  }, 100);
};

}

      // === Khi nhấn "Chấp nhận" ===
   approveBtn.addEventListener('click', function() {
  const currentAppId = this.getAttribute('data-app-id');
  if (!currentAppId) return alert('Không xác định được bản ghi đang xét duyệt.');

  if (confirm('Bạn có chắc chắn muốn chấp nhận yêu cầu này?')) {
    // Lấy danh sách loại tài khoản đã chọn từ map
    const selectedAccounts = selectedAccountsMap[currentAppId] || [];

    // Gửi cả danh sách đó lên server
    updateApplicationStatus(currentAppId, 'approved', '', selectedAccounts);

    bootstrap.Modal.getInstance(document.getElementById('detailModal')).hide();
  }
});

      // === Khi nhấn "Từ chối" ===
      rejectBtn.addEventListener('click', function() {
        const currentAppId = this.getAttribute('data-app-id');
        const reason = document.getElementById('rejectReason').value.trim();
        if (!reason) return alert('Vui lòng nhập lý do từ chối!');
        if (confirm('Xác nhận từ chối yêu cầu này?')) {
          updateApplicationStatus(currentAppId, 'rejected', reason);
          bootstrap.Modal.getInstance(document.getElementById('detailModal')).hide();
        }
      });

  // === Khi đóng modal chi tiết ===
document.getElementById('cancelBtn').addEventListener('click', function() {
  const currentStatus = document.getElementById('detailStatusBadge').textContent.trim();

  // Luôn reset ID
  approveBtn.removeAttribute('data-app-id');
  addTaiKhoanBtn.removeAttribute('data-app-id');
  rejectBtn.removeAttribute('data-app-id');

  // Ẩn tất cả nút
  approveBtn.style.display = 'none';
  rejectBtn.style.display = 'none';
  addTaiKhoanBtn.style.display = 'none';

  // Chỉ hiện lại "Thêm tài khoản" nếu đang ở trạng thái "Đang chờ"
  if (currentStatus === 'Đang chờ' || currentStatus === 'Chờ duyệt') {
    addTaiKhoanBtn.style.display = 'inline-block';
  }
});


      // === Khi xem chi tiết 1 hàng ===
      function showApplicationDetail(appId) {
        const app = currentApplications.find(a => a.id == appId);
        if (!app) return alert('Không tìm thấy dữ liệu chi tiết!');

        // Gán ID cho các nút
        addTaiKhoanBtn.setAttribute('data-app-id', app.id);
        approveBtn.setAttribute('data-app-id', app.id);
        rejectBtn.setAttribute('data-app-id', app.id);

<<<<<<< HEAD
    // Gán thông tin
    document.getElementById('detailMssv').textContent = app.mssv_input || '-';
    document.getElementById('detailCccd').textContent = app.cccd_input || '-';
    document.getElementById('detailFrontImage').src = app.anh_cccd_moi || app.anh_cccd || '/storage/cccd_images/default.jpg';
    document.getElementById('detailCurrentNote').textContent = app.ghi_chu || '-';
    document.getElementById('detailSubmitTime').textContent = app.created_at ? new Date(app.created_at).toLocaleString('vi-VN') : '-';
    document.getElementById('detailUpdateTime').textContent = app.updated_at ? new Date(app.updated_at).toLocaleString('vi-VN') : '-';
=======
        // Gán thông tin
        document.getElementById('detailMssv').textContent = app.mssv_input || '-';
        document.getElementById('detailCccd').textContent = app.cccd_input || '-';
        document.getElementById('detailName').textContent = app.ho_ten || '-';
        document.getElementById('detailDob').textContent = app.ngay_sinh || '-';
        document.getElementById('detailGender').textContent = app.gioi_tinh || '-';
        document.getElementById('detailHometown').textContent = app.que_quan || '-';
        document.getElementById('detailAddress').textContent = app.noi_thuong_tru || '-';
        document.getElementById('detailIssueDate').textContent = app.ngay_cap || '-';
        document.getElementById('detailIssuePlace').textContent = app.noi_cap || '-';
        document.getElementById('detailFrontImage').src = app.anh_cccd_moi || app.anh_cccd || '/storage/cccd_images/default.jpg';
        document.getElementById('detailCurrentNote').textContent = app.ghi_chu || '-';
        document.getElementById('detailSubmitTime').textContent = app.created_at ? new Date(app.created_at).toLocaleString('vi-VN') : '-';
        document.getElementById('detailUpdateTime').textContent = app.updated_at ? new Date(app.updated_at).toLocaleString('vi-VN') : '-';
>>>>>>> 2ec2e9a62424ff39463d1338482fcd1800d93323

        // Cập nhật trạng thái badge
        const statusBadge = document.getElementById('detailStatusBadge');
        statusBadge.className = `status-badge status-${app.trang_thai}`;
        statusBadge.textContent =
          app.trang_thai === 'approved' ? 'Đã duyệt' :
          app.trang_thai === 'rejected' ? 'Đã từ chối' : 'Đang chờ';

        // Kiểm tra nếu app đã có loại tài khoản
        const existing = selectedAccountsMap[appId] || [];
        renderSelectedLoaiTaiKhoan(existing, appId);

   if (app.trang_thai === 'pending') {
  // Đang chờ duyệt → cho phép thêm tài khoản & từ chối
  rejectBtn.style.display = 'inline-block';
  
  const existing = selectedAccountsMap[appId] || [];
  if (existing.length > 0) {
    addTaiKhoanBtn.style.display = 'none';
    approveBtn.style.display = 'inline-block';
  } else {
    addTaiKhoanBtn.style.display = 'inline-block';
    approveBtn.style.display = 'none';
  }

} else {
  // ✅ Nếu đã duyệt hoặc bị từ chối → chỉ hiển thị nút "Đóng"
  approveBtn.style.display = 'none';
  rejectBtn.style.display = 'none';
  addTaiKhoanBtn.style.display = 'none';
}

        detailModal.show();
      }

      // === Load danh sách ===
      async function loadApplications() {
        try {
          const response = await fetch('/quan-ly-xet-duyet');
          if (!response.ok) throw new Error(`HTTP error! status: ${response.status}`);
          const data = await response.json();
          currentApplications = data.applications || data || [];
          filterApplications();
        } catch (error) {
          console.error('Error loading applications:', error);
        }
      }

      // === Lọc danh sách ===
      function filterApplications() {
        const searchTerm = searchInput.value.toLowerCase();
        filteredApplications = currentApplications.filter(app => {
          const matchesFilter = currentFilter === 'all' || app.trang_thai === currentFilter;
          const matchesSearch = !searchTerm ||
            (app.mssv_input && app.mssv_input.toLowerCase().includes(searchTerm)) ||
            (app.cccd_input && app.cccd_input.toLowerCase().includes(searchTerm)) ||
            (app.ho_ten && app.ho_ten.toLowerCase().includes(searchTerm));
          return matchesFilter && matchesSearch;
        });
        renderApplications();
        updateStatistics();
      }

      // === Render danh sách ===
      function renderApplications() {
        if (filteredApplications.length === 0) {
          applicationsList.innerHTML = `<div class="text-center py-5 text-muted">Không có yêu cầu nào</div>`;
          return;
        }

        let html = '';
        filteredApplications.forEach(app => {
          const statusClass = `status-${app.trang_thai}`;
          const statusText =
            app.trang_thai === 'approved' ? 'Đã duyệt' :
            app.trang_thai === 'rejected' ? 'Từ chối' : 'Chờ duyệt';
          const submitTime = app.created_at ? new Date(app.created_at).toLocaleString('vi-VN') : '-';
          html += `
        <div class="application-item">
          <div class="application-info">
            <div class="d-flex justify-content-between mb-1">
              <h6 class="fw-bold mb-0">${app.ho_ten || app.mssv_input}</h6>
              <span class="status-badge ${statusClass}">${statusText}</span>
            </div>
            <div class="text-muted small">
              MSSV: ${app.mssv_input || '-'} | CCCD: ${app.cccd_input || '-'} | ${submitTime}
            </div>
          </div>
          <div class="application-actions">
            <button class="btn btn-sm btn-outline-primary view-detail" data-id="${app.id}">
              <i class="fas fa-eye me-1"></i> Chi tiết
            </button>
          </div>
        </div>`;
        });

        applicationsList.innerHTML = html;
        document.querySelectorAll('.view-detail').forEach(btn => {
          btn.addEventListener('click', () => showApplicationDetail(btn.getAttribute('data-id')));
        });
      }

      // === Cập nhật thống kê ===
      function updateStatistics() {
        document.getElementById('totalCount').textContent = currentApplications.length;
        document.getElementById('pendingCount').textContent = currentApplications.filter(a => a.trang_thai === 'pending').length;
        document.getElementById('approvedCount').textContent = currentApplications.filter(a => a.trang_thai === 'approved').length;
        document.getElementById('rejectedCount').textContent = currentApplications.filter(a => a.trang_thai === 'rejected').length;
      }

  // === Cập nhật trạng thái server ===
  async function updateApplicationStatus(appId, status, reason = '', accounts = []) {
  try {
    const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    const res = await fetch('/quan-ly-xet-duyet', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': token,
        'Accept': 'application/json'
      },
      body: JSON.stringify({ id: appId, status, reason, accounts }) //  gửi thêm danh sách tài khoản
    });
    const result = await res.json();
    if (result.success) {
      alert('Cập nhật thành công!');
      loadApplications();
    } else {
      alert(result.message || 'Có lỗi khi cập nhật.');
    }
  } catch (err) {
    alert('Lỗi khi cập nhật: ' + err.message);
  }
}

});
</script>
</body>

</html>