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
      box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    }
    
    .dashboard-card {
      background: #fff;
      border-radius: 12px;
      box-shadow: 0 4px 12px rgba(0,0,0,0.08);
      transition: transform 0.3s, box-shadow 0.3s;
      border-left: 4px solid var(--primary-color);
      margin-bottom: 15px;
    }
    
    .dashboard-card:hover {
      transform: translateY(-2px);
      box-shadow: 0 6px 14px rgba(0,0,0,0.12);
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
      <div class="navbar-nav ms-auto">
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
        </div>
      </div>
    </div>
  </nav>

  <div class="container py-4">
    <!-- Header -->
    <div class="row mb-4">
      <div class="col-md-8">
        <h2 class="fw-bold text-primary">Quản lý xét duyệt sinh viên</h2>
        <p class="text-muted">Xem và xét duyệt thông tin sinh viên từ CCCD</p>
      </div>
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
        <button class="btn btn-light"><i class="fas fa-download me-1"></i> Xuất file</button>
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
  <div class="modal fade detail-modal" id="detailModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Chi tiết yêu cầu xét duyệt</h5>
          <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
          <div class="row">
            <div class="col-md-6">
              <div class="info-card">
                <h6><i class="fas fa-id-card me-2 text-primary"></i>Thông tin từ CCCD</h6>
                <p class="mb-1"><strong>Số CCCD:</strong> <span id="detailCccd">012345678901</span></p>
                <p class="mb-1"><strong>Họ và tên:</strong> <span id="detailName">Nguyễn Văn A</span></p>
                <p class="mb-0"><strong>Ngày sinh:</strong> <span id="detailDob">01/01/2000</span></p>
              </div>
            </div>
            <div class="col-md-6">
              <div class="info-card">
                <h6><i class="fas fa-user-graduate me-2 text-primary"></i>Thông tin sinh viên</h6>
                <p class="mb-1"><strong>MSSV:</strong> <span id="detailMssv">72100001</span></p>
              </div>
            </div>
          </div>
          
          <div class="info-card">
            <h6><i class="fas fa-images me-2 text-primary"></i>Hình ảnh CCCD</h6>
            <div class="row">
              <div class="col-md-12 text-center">
                <p class="mb-1">Ảnh xác thực</p>
                <img id="detailFrontImage" src="" class="img-thumbnail" style="max-height: 300px; width: auto;">
              </div>
            </div>
          </div>
          
          <div class="info-card">
            <h6><i class="fas fa-history me-2 text-primary"></i>Lịch sử xét duyệt</h6>
            <div class="mb-2">
              <span class="status-badge status-pending me-2" id="detailStatusBadge">Đang chờ</span>
              <small class="text-muted">Gửi lúc: <span id="detailSubmitTime">10:30 15/04/2025</span></small>
            </div>
            <div class="mb-0">
              <label for="rejectReason" class="form-label">Ghi chú/Lý do (nếu từ chối)</label>
              <textarea class="form-control" id="rejectReason" rows="3" placeholder="Nhập lý do từ chối (nếu có)"></textarea>
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-light" data-bs-dismiss="modal">Đóng</button>
          <button type="button" class="btn btn-danger btn-reject" id="rejectBtn">
            <i class="fas fa-times me-1"></i> Từ chối
          </button>
          <button type="button" class="btn btn-success btn-approve" id="approveBtn">
            <i class="fas fa-check me-1"></i> Chấp nhận
          </button>
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
      
      // Initialize the UI
      loadApplications();
      
      // Filter buttons event listeners
      filterBtns.forEach(btn => {
        btn.addEventListener('click', function() {
          filterBtns.forEach(b => b.classList.remove('active'));
          this.classList.add('active');
          currentFilter = this.getAttribute('data-filter');
          filterApplications();
        });
      });
      
      // Search input event listener
      searchInput.addEventListener('input', function() {
        filterApplications();
      });
      
      // Refresh button event listener
      refreshBtn.addEventListener('click', function() {
        loadApplications();
      });
      
      // Approve button event listener
      document.getElementById('approveBtn').addEventListener('click', function() {
        const currentAppId = this.getAttribute('data-app-id');
        if (currentAppId && confirm('Bạn có chắc chắn muốn chấp nhận yêu cầu này?')) {
          updateApplicationStatus(currentAppId, 'approved');
          detailModal.hide();
        }
      });
      
      // Reject button event listener
      document.getElementById('rejectBtn').addEventListener('click', function() {
        const currentAppId = this.getAttribute('data-app-id');
        const rejectReason = document.getElementById('rejectReason').value.trim();
        
        if (!currentAppId) return;
        
        if (!rejectReason) {
          alert('Vui lòng nhập lý do từ chối');
          return;
        }
        
        if (confirm('Bạn có chắc chắn muốn từ chối yêu cầu này?')) {
          updateApplicationStatus(currentAppId, 'rejected', rejectReason);
          detailModal.hide();
        }
      });
      
      // Load applications from API
      async function loadApplications() {
        try {
          applicationsList.innerHTML = `
            <div class="loading-spinner">
              <div class="spinner-border text-primary" role="status">
                <span class="visually-hidden">Đang tải...</span>
              </div>
              <span class="ms-2">Đang tải dữ liệu...</span>
            </div>
          `;
          
          // Gọi API GET để lấy danh sách yêu cầu xét duyệt
          const response = await fetch('/quan-ly-xet-duyet');
          
          if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
          }
          
          const data = await response.json();
          
          currentApplications = data.applications || data || [];
          filterApplications();
          
        } catch (error) {
          console.error('Error loading applications:', error); 
          applicationsList.innerHTML = `
            <div class="text-center py-4">
              <i class="fas fa-exclamation-triangle fa-2x text-danger mb-3"></i>
              <h5 class="text-danger">Lỗi khi tải dữ liệu</h5>
              <p class="text-muted">Vui lòng thử lại sau</p>
              <button class="btn btn-primary" onclick="loadApplications()">Thử lại</button>
            </div>
          `;
        }
      }
      
      // Filter applications based on current filter and search
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
      
      // Render applications list
      function renderApplications() {
        if (filteredApplications.length === 0) {
          applicationsList.innerHTML = `
            <div class="text-center py-5">
              <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
              <h5 class="text-muted">Không có yêu cầu nào</h5>
              <p class="text-muted">Không tìm thấy yêu cầu phù hợp với bộ lọc</p>
            </div>
          `;
          return;
        }
        
        let applicationsHTML = '';
        
        filteredApplications.forEach(app => {
          const statusClass = `status-${app.trang_thai}`;
          let statusText = '';
          
          switch(app.trang_thai) {
            case 'pending': statusText = 'Chờ duyệt'; break;
            case 'approved': statusText = 'Đã duyệt'; break;
            case 'rejected': statusText = 'Từ chối'; break;
            default: statusText = 'Chờ duyệt';
          }
          
          // Xử lý hiển thị tên - sử dụng họ tên nếu có, nếu không dùng MSSV
          const displayName = app.ho_ten || `Sinh viên ${app.mssv_input}`;
          const firstLetter = displayName.charAt(0).toUpperCase();
          const submitTime = app.created_at ? new Date(app.created_at).toLocaleString('vi-VN') : 'Chưa có thông tin';
          
          applicationsHTML += `
            <div class="application-item">
              <div class="application-info">
                <div class="d-flex flex-wrap align-items-center justify-content-between mb-1">
                  <h6 class="fw-bold mb-0 me-2">${displayName}</h6>
                  <span class="status-badge ${statusClass}">${statusText}</span>
                </div>
                <div class="d-flex flex-wrap text-muted">
                  <span class="me-3"><small>MSSV: ${app.mssv_input || 'Chưa có'}</small></span>
                  <span class="me-3"><small>CCCD: ${app.cccd_input || 'Chưa có'}</small></span>
                  <span><small>${submitTime}</small></span>
                </div>
              </div>
              <div class="application-actions">
                <button class="btn btn-sm btn-outline-primary view-detail" data-id="${app.id}">
                  <i class="fas fa-eye me-1"></i> Chi tiết
                </button>
              </div>
            </div>
          `;
        });
        
        applicationsList.innerHTML = applicationsHTML;
        
        // Add event listeners to detail buttons
        document.querySelectorAll('.view-detail').forEach(btn => {
          btn.addEventListener('click', function() {
            const appId = this.getAttribute('data-id');
            showApplicationDetail(appId);
          });
        });
        
        // Update counts
        document.getElementById('showingCount').textContent = filteredApplications.length;
        document.getElementById('totalItems').textContent = currentApplications.length;
      }
      
      // Show application details in modal
      function showApplicationDetail(appId) {
        const app = currentApplications.find(a => a.id == appId);
        if (!app) return;
        
        document.getElementById('detailCccd').textContent = app.cccd_input || 'Chưa có thông tin';
        document.getElementById('detailName').textContent = app.ho_ten || `Sinh viên ${app.mssv_input}`;
        document.getElementById('detailDob').textContent = app.ngay_sinh || 'Chưa có thông tin';
        document.getElementById('detailMssv').textContent = app.mssv_input || 'Chưa có thông tin';
        document.getElementById('detailFrontImage').src = app.anh_cccd || '/storage/cccd_images/default.jpg';
        document.getElementById('detailSubmitTime').textContent = app.created_at ? new Date(app.created_at).toLocaleString('vi-VN') : 'Chưa có thông tin';
        document.getElementById('rejectReason').value = app.ghi_chu || '';
        
        // Update status badge
        const statusBadge = document.getElementById('detailStatusBadge');
        statusBadge.className = `status-badge status-${app.trang_thai} me-2`;
        switch(app.trang_thai) {
          case 'pending': statusBadge.textContent = 'Đang chờ'; break;
          case 'approved': statusBadge.textContent = 'Đã duyệt'; break;
          case 'rejected': statusBadge.textContent = 'Đã từ chối'; break;
          default: statusBadge.textContent = 'Đang chờ';
        }
        
        // Set current app id for action buttons
        document.getElementById('approveBtn').setAttribute('data-app-id', app.id);
        document.getElementById('rejectBtn').setAttribute('data-app-id', app.id);
        
        // Show/hide action buttons based on status
        if (app.trang_thai === 'pending') {
          document.getElementById('approveBtn').style.display = 'inline-block';
          document.getElementById('rejectBtn').style.display = 'inline-block';
          document.getElementById('rejectReason').disabled = false;
        } else {
          document.getElementById('approveBtn').style.display = 'none';
          document.getElementById('rejectBtn').style.display = 'none';
          document.getElementById('rejectReason').disabled = true;
        }
        
        detailModal.show();
      }
      
      // Update application status
      async function updateApplicationStatus(appId, status, reason = '') {
        try {
          const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
          
          const response = await fetch('/quan-ly-xet-duyet', {
            method: 'POST',
            headers: {
              'Content-Type': 'application/json',
              'X-CSRF-TOKEN': token,
              'Accept': 'application/json'
            },
            body: JSON.stringify({
              id: appId,
              status: status,
              reason: reason
            })
          });
          
          if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
          }
          
          const result = await response.json();
          
          if (result.success) {
            // Reload applications to get updated data
            loadApplications();
            alert('Cập nhật trạng thái thành công!');
          } else {
            alert('Có lỗi xảy ra: ' + (result.message || 'Không xác định'));
          }
        } catch (error) {
          console.error('Error updating application status:', error);
          alert('Lỗi khi cập nhật trạng thái: ' + error.message);
        }
      }
      
      // Update statistics
      function updateStatistics() {
        const total = currentApplications.length;
        const pending = currentApplications.filter(app => app.trang_thai === 'pending').length;
        const approved = currentApplications.filter(app => app.trang_thai === 'approved').length;
        const rejected = currentApplications.filter(app => app.trang_thai === 'rejected').length;
        
        document.getElementById('totalCount').textContent = total;
        document.getElementById('pendingCount').textContent = pending;
        document.getElementById('approvedCount').textContent = approved;
        document.getElementById('rejectedCount').textContent = rejected;
      }
    });
  </script>
</body>
</html>