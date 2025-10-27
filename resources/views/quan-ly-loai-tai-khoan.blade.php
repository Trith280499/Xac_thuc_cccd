<!DOCTYPE html>
<html lang="vi">

<head>
  <meta charset="utf-8">
  <title>Quản lý loại tài khoản</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <style>
    body {
      background-color: #f8f9fa;
    }

    .card {
      border-radius: 10px;
      border: none;
    }

    .table th {
      font-weight: 600;
    }

    .header-icon {
      font-size: 1.8rem;
      margin-right: 10px;
    }

    .btn-outline-primary {
      margin-right: 5px;
    }

    .action-buttons {
      display: flex;
      justify-content: center;
      gap: 5px;
    }

    .status-badge {
      padding: 5px 10px;
      border-radius: 15px;
      font-size: 0.8rem;
      font-weight: 600;
    }

    .status-active {
      background-color: #d1fae5;
      color: #065f46;
    }

    .status-inactive {
      background-color: #fee2e2;
      color: #991b1b;
    }

    .loading-spinner {
      display: none;
      text-align: center;
      padding: 20px;
    }

    .footer-actions {
      display: flex;
      justify-content: space-between;
      align-items: center;
      margin-top: 5px;
      padding-top: 5px;
    }
  </style>
</head>

<body class="bg-light">

  <div class="container">
    <div class="card shadow mt-5 p-4">
      <h4 class="text-center text-primary mb-3">
        <span class="header-icon">🎓</span>Quản Lý Loại Tài Khoản
      </h4>

      <div class="loading-spinner" id="loadingSpinner">
        <div class="spinner-border text-primary" role="status">
          <span class="visually-hidden">Đang tải...</span>
        </div>
        <p class="mt-2">Đang tải dữ liệu...</p>
      </div>

      <div class="d-flex justify-content-end mb-3">
        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addAccountModal">
          Thêm tài khoản
        </button>
      </div>

      <table class="table table-bordered">
        <thead class="table-primary">
          <tr>
            <th>Tên loại</th>
            <th>Mô tả</th>
            <th class="text-center">Trạng thái</th>
            <th class="text-center">Thao tác</th>
          </tr>
        </thead>
        <tbody id="accountList">
          <!-- Dữ liệu tài khoản sẽ được thêm vào đây -->
        </tbody>
      </table>

      <!-- Footer với nút quay lại -->
      <div class="footer-actions">
        <a href="{{ route('quan-ly-xet-duyet.form') }}" class="btn btn-outline-secondary">
          Quay lại
        </a>
        <div></div> 
      </div>
    </div>
  </div>

  <!-- Modal thêm tài khoản -->
  <div class="modal fade" id="addAccountModal" tabindex="-1" aria-labelledby="addAccountModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="addAccountModalLabel">Thêm loại tài khoản mới</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <form id="addAccountForm">
            <div class="mb-3">
              <label for="newAccountType" class="form-label">Tên loại</label>
              <input type="text" class="form-control" id="newAccountType" placeholder="Nhập tên loại tài khoản" required>
            </div>
            <div class="mb-3">
              <label for="newAccountDescription" class="form-label">Mô tả</label>
              <textarea class="form-control" id="newAccountDescription" placeholder="Nhập mô tả" rows="3" required></textarea>
            </div>
            <div class="mb-3">
              <label class="form-label">Trạng thái</label>
              <div>
                <div class="form-check form-check-inline">
                  <input class="form-check-input" type="radio" name="newAccountStatus" id="newStatusActive" value="active" checked>
                  <label class="form-check-label" for="newStatusActive">Active</label>
                </div>
                <div class="form-check form-check-inline">
                  <input class="form-check-input" type="radio" name="newAccountStatus" id="newStatusInactive" value="inactive">
                  <label class="form-check-label" for="newStatusInactive">Inactive</label>
                </div>
              </div>
            </div>
          </form>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
          <button type="button" class="btn btn-primary" id="saveNewAccountBtn">Lưu</button>
        </div>
      </div>
    </div>
  </div>

  <!-- Modal sửa tài khoản -->
  <div class="modal fade" id="editAccountModal" tabindex="-1" aria-labelledby="editAccountModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="editAccountModalLabel">Sửa loại tài khoản</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <form id="editAccountForm">
            <input type="hidden" id="editId" value="">
            <div class="mb-3">
              <label for="editAccountType" class="form-label">Tên loại *</label>
              <input type="text" class="form-control" id="editAccountType" placeholder="Nhập tên loại tài khoản" required>
            </div>
            <div class="mb-3">
              <label for="editAccountDescription" class="form-label">Mô tả *</label>
              <textarea class="form-control" id="editAccountDescription" placeholder="Nhập mô tả" rows="3" required></textarea>
            </div>
            <div class="mb-3">
              <label class="form-label">Trạng thái *</label>
              <div>
                <div class="form-check form-check-inline">
                  <input class="form-check-input" type="radio" name="editAccountStatus" id="editStatusActive" value="active">
                  <label class="form-check-label" for="editStatusActive">Active</label>
                </div>
                <div class="form-check form-check-inline">
                  <input class="form-check-input" type="radio" name="editAccountStatus" id="editStatusInactive" value="inactive">
                  <label class="form-check-label" for="editStatusInactive">Inactive</label>
                </div>
              </div>
            </div>
          </form>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
          <button type="button" class="btn btn-primary" id="updateAccountBtn">Cập nhật</button>
        </div>
      </div>
    </div>
  </div>

  <!-- Modal xác nhận xóa -->
  <div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="deleteModalLabel">Xác nhận xóa</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          Bạn có chắc chắn muốn xóa loại tài khoản "<span id="deleteAccountName"></span>" không?
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
          <button type="button" class="btn btn-danger" id="confirmDeleteBtn">Xóa</button>
        </div>
      </div>
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  <script>
    // Biến toàn cục để lưu dữ liệu từ database
    let accountsData = {
      accounts: []
    };

    let accountToDeleteId = null;

    function showLoading() {
      document.getElementById('loadingSpinner').style.display = 'block';
    }

    function hideLoading() {
      document.getElementById('loadingSpinner').style.display = 'none';
    }

    // Hiển thị dữ liệu tài khoản
    function renderAccounts() {
      const accountList = document.getElementById('accountList');

      if (accountsData.accounts.length === 0) {
        accountList.innerHTML = `
          <tr>
            <td colspan="4" class="text-center text-muted py-4">
              Không có dữ liệu loại tài khoản
            </td>
          </tr>
        `;
        return;
      }

      accountList.innerHTML = accountsData.accounts.map((acc, index) => `
        <tr>
          <td>${acc.ten_loai}</td>
          <td>${acc.mo_ta}</td>
          <td class="text-center">
            <span class="status-badge ${acc.trang_thai === 'active' ? 'status-active' : 'status-inactive'}">
              ${acc.trang_thai === 'active' ? 'Active' : 'Inactive'}
            </span>
          </td>
          <td class="text-center">
            <div class="action-buttons">
              <button class="btn btn-sm btn-outline-primary edit-account" data-id="${acc.id}" data-index="${index}">Sửa</button>
              <button class="btn btn-sm btn-outline-danger delete-account" data-id="${acc.id}" data-name="${acc.ten_loai}">Xóa</button>
            </div>
          </td>
        </tr>
      `).join('');

      // Thêm sự kiện cho các nút Sửa và Xóa
      document.querySelectorAll('.edit-account').forEach(button => {
        button.addEventListener('click', (e) => {
          const id = e.target.dataset.id;
          const index = e.target.dataset.index;
          showEditModal(id, index);
        });
      });

      document.querySelectorAll('.delete-account').forEach(button => {
        button.addEventListener('click', (e) => {
          const id = e.target.dataset.id;
          const name = e.target.dataset.name;
          showDeleteConfirmation(id, name);
        });
      });
    }

    // Hiển thị modal sửa tài khoản
    function showEditModal(id, index) {
      const account = accountsData.accounts[index];
      document.getElementById('editId').value = id;
      document.getElementById('editAccountType').value = account.ten_loai;
      document.getElementById('editAccountDescription').value = account.mo_ta;

      // Thiết lập trạng thái
      if (account.trang_thai === 'active') {
        document.getElementById('editStatusActive').checked = true;
      } else {
        document.getElementById('editStatusInactive').checked = true;
      }

      const editModal = new bootstrap.Modal(document.getElementById('editAccountModal'));
      editModal.show();
    }

    function showDeleteConfirmation(id, name) {
      accountToDeleteId = id;
      document.getElementById('deleteAccountName').textContent = name;
      const deleteModal = new bootstrap.Modal(document.getElementById('deleteModal'));
      deleteModal.show();
    }

    // Gọi API để lấy dữ liệu tài khoản từ database
    async function fetchAccounts() {
      showLoading();

      try {
        const apiUrl = '/quan-ly-loai-tai-khoan/api/danh-sach';
        console.log('Đang gọi API:', apiUrl);

        const response = await fetch(apiUrl);

        console.log('API Response status:', response.status);

        if (response.ok) {
          const result = await response.json();
          console.log('API Result:', result);

          if (result.success && Array.isArray(result.data)) {
            accountsData.accounts = result.data;
            renderAccounts();
          } else {
            console.error('Dữ liệu API không hợp lệ:', result);
            showError('Dữ liệu từ server không hợp lệ');
          }
        } else {
          console.error('Lỗi HTTP khi gọi API:', response.status);
          showError('Không thể kết nối đến server');
        }
      } catch (error) {
        console.error('Lỗi khi gọi API:', error);
        showError('Lỗi kết nối mạng: ' + error.message);
      } finally {
        hideLoading();
      }
    }

    // Hiển thị thông báo lỗi
    function showError(message) {
      const accountList = document.getElementById('accountList');
      accountList.innerHTML = `
        <tr>
          <td colspan="4" class="text-center text-danger py-4">
            ${message}
          </td>
        </tr>
      `;
    }

    // Thêm tài khoản mới vào database
    async function addAccountToDatabase(accountData) {
      try {
        const response = await fetch('/quan-ly-loai-tai-khoan/api/them-moi', {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
          },
          body: JSON.stringify(accountData)
        });

        const result = await response.json();
        return result;

      } catch (error) {
        console.error('Lỗi khi thêm tài khoản:', error);
        return {
          success: false,
          message: 'Lỗi kết nối mạng: ' + error.message
        };
      }
    }

    // Cập nhật tài khoản trong database
    async function updateAccountInDatabase(id, accountData) {
      try {
        const response = await fetch(`/quan-ly-loai-tai-khoan/api/cap-nhat/${id}`, {
          method: 'PUT',
          headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
          },
          body: JSON.stringify(accountData)
        });

        const result = await response.json();
        return result;

      } catch (error) {
        console.error('Lỗi khi cập nhật tài khoản:', error);
        return {
          success: false,
          message: 'Lỗi kết nối mạng: ' + error.message
        };
      }
    }

    // Xóa tài khoản từ database
    async function deleteAccountFromDatabase(id) {
      try {
        const response = await fetch(`/quan-ly-loai-tai-khoan/api/xoa/${id}`, {
          method: 'DELETE',
          headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
          }
        });

        const result = await response.json();
        return result;

      } catch (error) {
        console.error('Lỗi khi xóa tài khoản:', error);
        return {
          success: false,
          message: 'Lỗi kết nối mạng: ' + error.message
        };
      }
    }

    document.addEventListener('DOMContentLoaded', function() {
      // Gọi API để lấy dữ liệu từ database
      fetchAccounts();

      // Xử lý thêm tài khoản mới
      document.getElementById('saveNewAccountBtn').addEventListener('click', async function() {
        const ten_loai = document.getElementById('newAccountType').value.trim();
        const mo_ta = document.getElementById('newAccountDescription').value.trim();
        const trang_thai = document.querySelector('input[name="newAccountStatus"]:checked').value;

        if (!ten_loai || !mo_ta) {
          alert('⚠️ Vui lòng nhập đầy đủ thông tin!');
          return;
        }

        const newAccount = {
          ten_loai,
          mo_ta,
          trang_thai
        };

        // Thêm vào database
        const result = await addAccountToDatabase(newAccount);

        if (result.success) {
          // Làm mới dữ liệu từ server
          fetchAccounts();

          const addModal = bootstrap.Modal.getInstance(document.getElementById('addAccountModal'));
          addModal.hide();
          document.getElementById('addAccountForm').reset();

          alert("✅ Thêm loại tài khoản thành công!");
        } else {
          let errorMessage = "❌ Lỗi khi thêm loại tài khoản!";
          if (result.errors) {
            errorMessage += "\n" + Object.values(result.errors).join('\n');
          } else if (result.message) {
            errorMessage += "\n" + result.message;
          }
          alert(errorMessage);
        }
      });

      // Xử lý cập nhật tài khoản
      document.getElementById('updateAccountBtn').addEventListener('click', async function() {
        const id = document.getElementById('editId').value;
        const ten_loai = document.getElementById('editAccountType').value.trim();
        const mo_ta = document.getElementById('editAccountDescription').value.trim();
        const trang_thai = document.querySelector('input[name="editAccountStatus"]:checked').value;

        if (!id || !ten_loai || !mo_ta) {
          alert('⚠️ Dữ liệu không hợp lệ!');
          return;
        }

        const updatedAccount = {
          ten_loai,
          mo_ta,
          trang_thai
        };

        // Cập nhật trong database
        const result = await updateAccountInDatabase(id, updatedAccount);

        if (result.success) {
          // Làm mới dữ liệu từ server
          fetchAccounts();

          const editModal = bootstrap.Modal.getInstance(document.getElementById('editAccountModal'));
          editModal.hide();
          document.getElementById('editAccountForm').reset();

          alert("✅ Cập nhật loại tài khoản thành công!");
        } else {
          let errorMessage = "❌ Lỗi khi cập nhật loại tài khoản!";
          if (result.errors) {
            errorMessage += "\n" + Object.values(result.errors).join('\n');
          } else if (result.message) {
            errorMessage += "\n" + result.message;
          }
          alert(errorMessage);
        }
      });

      document.getElementById('confirmDeleteBtn').addEventListener('click', async function() {
        if (accountToDeleteId) {
          // Xóa từ database
          const result = await deleteAccountFromDatabase(accountToDeleteId);

          if (result.success) {
            // Làm mới dữ liệu từ server
            fetchAccounts();

            const deleteModal = bootstrap.Modal.getInstance(document.getElementById('deleteModal'));
            deleteModal.hide();
            alert("✅ Xóa loại tài khoản thành công!");
          } else {
            alert("❌ Lỗi khi xóa loại tài khoản: " + (result.message || ''));
          }
          accountToDeleteId = null;
        }
      });
    });
  </script>

</body>

</html>