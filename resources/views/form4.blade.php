<!DOCTYPE html>
<html lang="vi">
<head>
  <meta charset="UTF-8">
  <title>Lịch sử reset mật khẩu</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <!-- Google Fonts -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Roboto:wght@300;400;500;700&display=swap" rel="stylesheet">
  <style>
    :root {
      --primary-color: #124874;
      --secondary-color: #64748b;
      --success-color: #059669;
      --danger-color: #dc2626;
      --warning-color: #d97706;
      --info-color: #0891b2;
      --light-bg: #f8fafc;
      --card-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
    }

    body {
      font-family: 'Inter', 'Segoe UI', system-ui, -apple-system, sans-serif;
      background: linear-gradient(135deg, #f1f5f9 0%, #e2e8f0 100%);
      padding: 30px;
      min-height: 100vh;
      line-height: 1.6;
      font-weight: 400;
    }

    .container {
      max-width: 1400px;
      margin: 0 auto;
    }

    .page-title {
      color: var(--primary-color);
      text-align: center;
      margin-bottom: 40px;
      font-weight: 700;
      font-size: 2.5rem;
      letter-spacing: -0.025em;
      text-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    }

    .page-subtitle {
      text-align: center;
      color: var(--secondary-color);
      font-size: 1.1rem;
      margin-bottom: 40px;
      font-weight: 400;
    }

    .card {
      background: white;
      box-shadow: var(--card-shadow);
      border-radius: 16px;
      border: none;
      overflow: hidden;
      margin-bottom: 30px;
      transition: transform 0.2s ease, box-shadow 0.2s ease;
    }

    .card:hover {
      transform: translateY(-2px);
      box-shadow: 0 20px 40px -10px rgba(0, 0, 0, 0.15);
    }

    .card-header {
      background: linear-gradient(135deg, var(--primary-color) 0%, #1e6aa5 100%);
      color: white;
      font-weight: 600;
      border-radius: 0 !important;
      padding: 20px 30px;
      border: none;
    }

    .card-header h3 {
      margin: 0;
      font-weight: 600;
      font-size: 1.4rem;
      letter-spacing: -0.01em;
    }

    .table-responsive {
      border-radius: 0 0 16px 16px;
    }

    table {
      margin-bottom: 0;
      font-size: 0.95rem;
    }

    th {
      background-color: #f8fafc;
      font-weight: 600;
      color: var(--primary-color);
      border-bottom: 2px solid #e2e8f0;
      padding: 16px 20px;
      font-size: 0.9rem;
      text-transform: uppercase;
      letter-spacing: 0.05em;
    }

    td {
      padding: 16px 20px;
      vertical-align: middle;
      border-bottom: 1px solid #f1f5f9;
      color: #334155;
      font-weight: 400;
    }

    tr {
      transition: background-color 0.15s ease;
    }

    tr:hover {
      background-color: #f8fafc;
    }

    .badge {
      font-weight: 500;
      padding: 6px 12px;
      border-radius: 8px;
      font-size: 0.8rem;
      letter-spacing: 0.02em;
    }

    .badge-teams {
      background: linear-gradient(135deg, #4f46e5 0%, #7c73e6 100%);
    }

    .badge-vle {
      background: linear-gradient(135deg, #059669 0%, #10b981 100%);
    }

    .badge-portal {
      background: linear-gradient(135deg, #dc2626 0%, #ef4444 100%);
    }

    .empty-state {
      text-align: center;
      padding: 60px 40px;
      color: #64748b;
    }

    .empty-state i {
      font-size: 64px;
      margin-bottom: 20px;
      opacity: 0.7;
    }

    .empty-state h4 {
      font-weight: 600;
      color: #475569;
      margin-bottom: 10px;
    }

    .empty-state p {
      font-size: 1rem;
      opacity: 0.8;
    }

    .password-cell {
      font-family: 'Roboto Mono', 'Courier New', monospace;
      font-weight: 600;
      color: var(--danger-color);
      background-color: #fef2f2;
      padding: 6px 12px;
      border-radius: 6px;
      font-size: 0.9rem;
      letter-spacing: 0.05em;
    }

    .date-time {
      white-space: nowrap;
      font-weight: 500;
      color: #475569;
    }

    .stats-grid {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
      gap: 20px;
      margin-top: 40px;
    }

    .stat-card {
      background: white;
      padding: 25px;
      border-radius: 16px;
      box-shadow: var(--card-shadow);
      text-align: center;
      transition: transform 0.2s ease;
      border: 1px solid #f1f5f9;
    }

    .stat-card:hover {
      transform: translateY(-3px);
    }

    .stat-number {
      font-size: 2.5rem;
      font-weight: 700;
      color: var(--primary-color);
      margin-bottom: 8px;
      line-height: 1;
    }

    .stat-label {
      color: var(--secondary-color);
      font-weight: 500;
      font-size: 0.95rem;
      text-transform: uppercase;
      letter-spacing: 0.05em;
    }

    .account-username {
      font-weight: 500;
      color: #1e293b;
      font-family: 'Roboto Mono', 'Courier New', monospace;
      font-size: 0.9rem;
    }

    .executor {
      font-weight: 500;
      color: #475569;
    }

    /* Pagination Styles */
    .pagination-container {
      display: flex;
      justify-content: space-between;
      align-items: center;
      padding: 20px 30px;
      background-color: #f8fafc;
      border-top: 1px solid #e2e8f0;
    }

    .pagination-info {
      color: var(--secondary-color);
      font-size: 0.9rem;
      font-weight: 500;
    }

    .pagination {
      margin: 0;
    }

    .page-link {
      border: 1px solid #e2e8f0;
      color: var(--primary-color);
      font-weight: 500;
      padding: 8px 16px;
      margin: 0 4px;
      border-radius: 8px;
      transition: all 0.2s ease;
    }

    .page-link:hover {
      background-color: var(--primary-color);
      color: white;
      border-color: var(--primary-color);
    }

    .page-item.active .page-link {
      background-color: var(--primary-color);
      border-color: var(--primary-color);
      color: white;
    }

    .page-item.disabled .page-link {
      color: #94a3b8;
      background-color: #f1f5f9;
      border-color: #e2e8f0;
    }

    /* Controls */
    .table-controls {
      display: flex;
      justify-content: space-between;
      align-items: center;
      padding: 20px 30px;
      background-color: white;
      border-bottom: 1px solid #e2e8f0;
    }

    .per-page-select {
      width: auto;
      border: 1px solid #e2e8f0;
      border-radius: 8px;
      padding: 6px 12px;
      font-size: 0.9rem;
      color: var(--primary-color);
    }

    .search-box {
      width: 250px;
      border: 1px solid #e2e8f0;
      border-radius: 8px;
      padding: 6px 12px;
      font-size: 0.9rem;
    }

    /* Responsive design */
    @media (max-width: 768px) {
      body {
        padding: 15px;
      }
      
      .page-title {
        font-size: 2rem;
      }
      
      .card-header {
        padding: 15px 20px;
      }
      
      th, td {
        padding: 12px 15px;
        font-size: 0.85rem;
      }
      
      .stats-grid {
        grid-template-columns: 1fr;
        gap: 15px;
      }
      
      .table-controls {
        flex-direction: column;
        gap: 15px;
        align-items: stretch;
      }
      
      .search-box {
        width: 100%;
      }
      
      .pagination-container {
        flex-direction: column;
        gap: 15px;
        text-align: center;
      }
    }

    /* Animation for table rows */
    @keyframes fadeIn {
      from {
        opacity: 0;
        transform: translateY(10px);
      }
      to {
        opacity: 1;
        transform: translateY(0);
      }
    }

    tbody tr {
      animation: fadeIn 0.3s ease-out;
    }

    tbody tr:nth-child(even) {
      background-color: #fafbfc;
    }

    /* Custom scrollbar */
    .table-responsive::-webkit-scrollbar {
      height: 8px;
    }

    .table-responsive::-webkit-scrollbar-track {
      background: #f1f5f9;
      border-radius: 4px;
    }

    .table-responsive::-webkit-scrollbar-thumb {
      background: #cbd5e1;
      border-radius: 4px;
    }

    .table-responsive::-webkit-scrollbar-thumb:hover {
      background: #94a3b8;
    }
  </style>
</head>
<body>

<div class="container">
  <h1 class="page-title">LỊCH SỬ RESET MẬT KHẨU</h1>
  <p class="page-subtitle">Theo dõi toàn bộ lịch sử khôi phục mật khẩu hệ thống</p>

  <div class="card">
    <div class="card-header">
      <h3 class="mb-0">Bảng dữ liệu lịch sử</h3>
    </div>

    <!-- Controls -->
    <div class="table-controls">
      <div>
        <label for="perPage" class="me-2">Hiển thị:</label>
        <select id="perPage" class="per-page-select" onchange="changePerPage(this.value)">
          <option value="10">10 dòng</option>
          <option value="25">25 dòng</option>
          <option value="50">50 dòng</option>
          <option value="100">100 dòng</option>
        </select>
      </div>
      <div>
        <input type="text" id="searchInput" class="search-box" placeholder="Tìm kiếm tài khoản..." onkeyup="searchTable()">
      </div>
    </div>

    <div class="table-responsive">
      <table class="table table-hover" id="historyTable">
        <thead>
          <tr>
            <th>#</th>
            <th>Loại tài khoản</th>
            <th>Tài khoản</th>
            <th>Mật khẩu mới</th>
            <th>Người thực hiện</th>
            <th>Thời gian reset</th>
            <th>Ngày</th>
            <th>Tháng</th>
            <th>Năm</th>
            <th>Giờ</th>
          </tr>
        </thead>
        <tbody id="tableBody">
          <!-- Dữ liệu sẽ được tải bằng JavaScript -->
        </tbody>
      </table>
    </div>

    <!-- Pagination -->
    <div class="pagination-container">
      <div class="pagination-info" id="paginationInfo">
        Đang tải...
      </div>
      <nav>
        <ul class="pagination" id="pagination">
          <!-- Pagination sẽ được tạo bằng JavaScript -->
        </ul>
      </nav>
    </div>
  </div>

  <!-- Statistics -->
  <div class="stats-grid" id="statsGrid">
    <!-- Thống kê sẽ được tải bằng JavaScript -->
  </div>
</div>

<script>
// Biến toàn cục
let currentPage = 1;
let perPage = 10;
let allData = [];
let filteredData = [];

// Hàm khởi tạo
async function initialize() {
  await loadData();
  renderTable();
  updatePagination();
  renderStats();
}

// Tải dữ liệu từ server
async function loadData() {
  try {
    const response = await fetch('/form4/lich-su-reset');
    const data = await response.json();
    allData = data;
    filteredData = [...data];
  } catch (error) {
    console.error('Lỗi khi tải dữ liệu:', error);
    allData = [];
    filteredData = [];
  }
}

// Render bảng
function renderTable() {
  const tableBody = document.getElementById('tableBody');
  const startIndex = (currentPage - 1) * perPage;
  const endIndex = startIndex + perPage;
  const pageData = filteredData.slice(startIndex, endIndex);

  if (pageData.length === 0) {
    tableBody.innerHTML = `
      <tr>
        <td colspan="10">
          <div class="empty-state">
            <div>🔍</div>
            <h4>Không tìm thấy dữ liệu</h4>
            <p>Không có bản ghi nào phù hợp với tiêu chí tìm kiếm</p>
          </div>
        </td>
      </tr>
    `;
    return;
  }

  tableBody.innerHTML = pageData.map((item, index) => {
    const thoiGian = new Date(item.thoi_gian_reset);
    const globalIndex = startIndex + index + 1;

    let badgeClass = 'bg-secondary';
    let badgeText = item.loai_tai_khoan;
    
    switch(item.loai_tai_khoan) {
      case 'Teams':
        badgeClass = 'badge-teams';
        badgeText = 'Microsoft Teams';
        break;
      case 'VLE':
        badgeClass = 'badge-vle';
        badgeText = 'VLE';
        break;
      case 'Portal':
        badgeClass = 'badge-portal';
        badgeText = 'Portal';
        break;
    }

    return `
      <tr>
        <td style="font-weight: 600; color: var(--secondary-color);">${globalIndex}</td>
        <td><span class="badge ${badgeClass}">${badgeText}</span></td>
        <td><span class="account-username">${item.tai_khoan}</span></td>
        <td><span class="password-cell">${item.mat_khau_moi}</span></td>
        <td><span class="executor">${item.nguoi_thuc_hien || 'Hệ thống'}</span></td>
        <td class="date-time">${formatDateTime(thoiGian)}</td>
        <td style="font-weight: 500;">${thoiGian.getDate().toString().padStart(2, '0')}</td>
        <td style="font-weight: 500;">${(thoiGian.getMonth() + 1).toString().padStart(2, '0')}</td>
        <td style="font-weight: 500;">${thoiGian.getFullYear()}</td>
        <td style="font-weight: 500;">${thoiGian.getHours().toString().padStart(2, '0')}:${thoiGian.getMinutes().toString().padStart(2, '0')}</td>
      </tr>
    `;
  }).join('');
}

// Cập nhật phân trang
function updatePagination() {
  const pagination = document.getElementById('pagination');
  const totalPages = Math.ceil(filteredData.length / perPage);
  const paginationInfo = document.getElementById('paginationInfo');

  // Cập nhật thông tin phân trang
  const startItem = (currentPage - 1) * perPage + 1;
  const endItem = Math.min(currentPage * perPage, filteredData.length);
  paginationInfo.textContent = `Hiển thị ${startItem}-${endItem} của ${filteredData.length} bản ghi`;

  // Tạo phân trang
  let paginationHTML = '';

  // Nút Previous
  paginationHTML += `
    <li class="page-item ${currentPage === 1 ? 'disabled' : ''}">
      <a class="page-link" href="#" onclick="changePage(${currentPage - 1})">‹</a>
    </li>
  `;

  // Các nút trang
  const maxVisiblePages = 5;
  let startPage = Math.max(1, currentPage - Math.floor(maxVisiblePages / 2));
  let endPage = Math.min(totalPages, startPage + maxVisiblePages - 1);

  if (endPage - startPage + 1 < maxVisiblePages) {
    startPage = Math.max(1, endPage - maxVisiblePages + 1);
  }

  for (let i = startPage; i <= endPage; i++) {
    paginationHTML += `
      <li class="page-item ${i === currentPage ? 'active' : ''}">
        <a class="page-link" href="#" onclick="changePage(${i})">${i}</a>
      </li>
    `;
  }

  // Nút Next
  paginationHTML += `
    <li class="page-item ${currentPage === totalPages ? 'disabled' : ''}">
      <a class="page-link" href="#" onclick="changePage(${currentPage + 1})">›</a>
    </li>
  `;

  pagination.innerHTML = paginationHTML;
}

// Render thống kê
function renderStats() {
  const statsGrid = document.getElementById('statsGrid');
  
  const total = allData.length;
  const teams = allData.filter(item => item.loai_tai_khoan === 'Teams').length;
  const vle = allData.filter(item => item.loai_tai_khoan === 'VLE').length;
  const portal = allData.filter(item => item.loai_tai_khoan === 'Portal').length;

  statsGrid.innerHTML = `
    <div class="stat-card">
      <div class="stat-number">${total}</div>
      <div class="stat-label">Tổng số reset</div>
    </div>
    <div class="stat-card">
      <div class="stat-number">${teams}</div>
      <div class="stat-label">Microsoft Teams</div>
    </div>
    <div class="stat-card">
      <div class="stat-number">${vle}</div>
      <div class="stat-label">VLE</div>
    </div>
    <div class="stat-card">
      <div class="stat-number">${portal}</div>
      <div class="stat-label">Portal</div>
    </div>
  `;
}

// Hàm utility
function formatDateTime(date) {
  return `${date.getDate().toString().padStart(2, '0')}/${(date.getMonth() + 1).toString().padStart(2, '0')}/${date.getFullYear()} ${date.getHours().toString().padStart(2, '0')}:${date.getMinutes().toString().padStart(2, '0')}:${date.getSeconds().toString().padStart(2, '0')}`;
}

// Event handlers
function changePage(page) {
  currentPage = page;
  renderTable();
  updatePagination();
  window.scrollTo({ top: 0, behavior: 'smooth' });
}

function changePerPage(value) {
  perPage = parseInt(value);
  currentPage = 1;
  renderTable();
  updatePagination();
}

function searchTable() {
  const searchTerm = document.getElementById('searchInput').value.toLowerCase();
  
  if (searchTerm === '') {
    filteredData = [...allData];
  } else {
    filteredData = allData.filter(item => 
      item.tai_khoan.toLowerCase().includes(searchTerm) ||
      (item.nguoi_thuc_hien && item.nguoi_thuc_hien.toLowerCase().includes(searchTerm)) ||
      item.loai_tai_khoan.toLowerCase().includes(searchTerm)
    );
  }
  
  currentPage = 1;
  renderTable();
  updatePagination();
}

// Khởi tạo khi trang load
document.addEventListener('DOMContentLoaded', initialize);
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>