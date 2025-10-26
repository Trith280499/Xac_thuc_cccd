<!DOCTYPE html>
<html lang="vi">

<head>
  <meta charset="utf-8">
  <title>Chọn tài khoản kích hoạt</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
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

    .info-section {
      background-color: #f8f9fa;
      border-radius: 8px;
      padding: 15px;
      margin-bottom: 20px;
    }

    .info-item {
      display: flex;
      margin-bottom: 8px;
    }
    
    .info-label {
      min-width: 120px;
      font-weight: 600;
      color: var(--primary-color);
    }
    
    .info-value {
      flex: 1;
    }

    .btn-success {
      background-color: #198754;
      border-color: #198754;
      padding: 10px 30px;
      font-weight: 600;
    }

    .btn-success:hover {
      background-color: #157347;
      border-color: #146c43;
    }

    .btn-outline-primary {
      margin-right: 5px;
    }

    .action-buttons {
      display: flex;
      justify-content: center;
      gap: 5px;
    }

    .select-all-container {
      background-color: #f0f8ff;
      padding: 10px 15px;
      border-radius: 5px;
      margin: 0;
    }
  </style>
</head>

<body class="bg-light">

  <div class="container">
    <div class="card shadow mt-5 p-4">
      <h4 class="text-center text-primary mb-3">
        <span class="header-icon">🎓</span>Chọn Tài Khoản Kích Hoạt
      </h4>

       <!-- Thông tin sinh viên -->
      <div class="info-section">
        <div class="row">
          <div class="col-md-6">
            <div class="info-item">
              <span class="info-label">MSSV:</span>
              <span class="info-value" id="mssvLabel">22123456</span>
            </div>
          </div>
          <div class="col-md-6">
            <div class="info-item">
              <span class="info-label">CCCD:</span>
              <span class="info-value" id="cccdLabel">079123456789</span>
            </div>
          </div>
        </div>
      </div>

      <hr>

      <div class="d-flex justify-content-between align-items-center mb-3">
        <div class="select-all-container">
          <div class="form-check">
            <input class="form-check-input" type="checkbox" id="selectAllCheckbox">
            <label class="form-check-label fw-bold" for="selectAllCheckbox">
              Chọn tất cả tài khoản
            </label>
          </div>
        </div>

        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addAccountModal">
          Thêm tài khoản
        </button>
      </div>

      <table class="table table-bordered">
        <thead class="table-primary">
          <tr>
            <th>Loại tài khoản</th>
            <th>Tài khoản</th>
            <th class="text-center">Chọn</th>
            <th class="text-center">Thao tác</th>
          </tr>
        </thead>
        <tbody id="accountList">
          <!-- Dữ liệu tài khoản sẽ được thêm vào đây -->
        </tbody>
      </table>

      <div class="text-center mt-4">
        <button id="confirmBtn" class="btn btn-success px-4">Kích hoạt tài khoản</button>
      </div>
    </div>
  </div>

  <!-- Modal thêm tài khoản -->
  <div class="modal fade" id="addAccountModal" tabindex="-1" aria-labelledby="addAccountModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="addAccountModalLabel">Thêm tài khoản mới</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <form id="addAccountForm">
            <div class="mb-3">
              <label for="newAccountType" class="form-label">Loại tài khoản</label>
              <input type="text" class="form-control" id="newAccountType" placeholder="Nhập loại tài khoản" required>
            </div>
            <div class="mb-3">
              <label for="newAccountUsername" class="form-label">Tên tài khoản</label>
              <input type="text" class="form-control" id="newAccountUsername" placeholder="Nhập tên tài khoản" required>
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
          <h5 class="modal-title" id="editAccountModalLabel">Sửa tài khoản</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <form id="editAccountForm">
            <input type="hidden" id="editIndex" value="-1">
            <div class="mb-3">
              <label for="editAccountType" class="form-label">Loại tài khoản</label>
              <input type="text" class="form-control" id="editAccountType" placeholder="Nhập loại tài khoản" required>
            </div>
            <div class="mb-3">
              <label for="editAccountUsername" class="form-label">Tên tài khoản</label>
              <input type="text" class="form-control" id="editAccountUsername" placeholder="Nhập tên tài khoản" required>
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
          Bạn có chắc chắn muốn xóa tài khoản này không?
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
    // Dữ liệu mẫu thay thế API
    let accountsData = {
      mssv: "22123456",
      accounts: [{
          type: "Email trường",
          username: "22123456@student.hcmue.edu.vn"
        },
        {
          type: "Hệ thống đào tạo",
          username: "22123456"
        },
        {
          type: "Thư viện số",
          username: "22123456"
        },
        {
          type: "Hệ thống thi trực tuyến",
          username: "22123456"
        }
      ]
    };

    let accountToDeleteIndex = -1;

    // Hiển thị dữ liệu tài khoản
    function renderAccounts() {
      document.getElementById('mssvLabel').textContent = accountsData.mssv;

      const accountList = document.getElementById('accountList');
      accountList.innerHTML = accountsData.accounts.map((acc, index) => `
      <tr>
        <td>${acc.type}</td>
        <td>${acc.username}</td>
        <td class="text-center">
          <input type="checkbox" class="form-check-input choose-account"
            data-type="${acc.type}" data-username="${acc.username}">
        </td>
        <td class="text-center">
          <div class="action-buttons">
            <button class="btn btn-sm btn-outline-primary edit-account" data-index="${index}">Sửa</button>
            <button class="btn btn-sm btn-outline-danger delete-account" data-index="${index}">Xóa</button>
          </div>
        </td>
      </tr>
    `).join('');

      // Thêm sự kiện cho các nút Sửa và Xóa
      document.querySelectorAll('.edit-account').forEach(button => {
        button.addEventListener('click', (e) => {
          const index = e.target.dataset.index;
          showEditModal(index);
        });
      });

      document.querySelectorAll('.delete-account').forEach(button => {
        button.addEventListener('click', (e) => {
          const index = e.target.dataset.index;
          showDeleteConfirmation(index);
        });
      });

      updateSelectAllCheckbox();
    }

    // Hiển thị modal sửa tài khoản
    function showEditModal(index) {
      const account = accountsData.accounts[index];
      document.getElementById('editAccountType').value = account.type;
      document.getElementById('editAccountUsername').value = account.username;
      document.getElementById('editIndex').value = index;

      const editModal = new bootstrap.Modal(document.getElementById('editAccountModal'));
      editModal.show();
    }

    function showDeleteConfirmation(index) {
      accountToDeleteIndex = index;
      const deleteModal = new bootstrap.Modal(document.getElementById('deleteModal'));
      deleteModal.show();
    }

    // Xóa tài khoản
    function deleteAccount() {
      if (accountToDeleteIndex !== -1) {
        accountsData.accounts.splice(accountToDeleteIndex, 1);
        renderAccounts();
        accountToDeleteIndex = -1;

        const deleteModal = bootstrap.Modal.getInstance(document.getElementById('deleteModal'));
        deleteModal.hide();
      }
    }

    // Cập nhật trạng thái của checkbox "Chọn tất cả"
    function updateSelectAllCheckbox() {
      const checkboxes = document.querySelectorAll('.choose-account');
      const selectAllCheckbox = document.getElementById('selectAllCheckbox');

      if (checkboxes.length === 0) {
        selectAllCheckbox.checked = false;
        selectAllCheckbox.indeterminate = false;
        return;
      }

      const checkedCount = document.querySelectorAll('.choose-account:checked').length;

      if (checkedCount === 0) {
        selectAllCheckbox.checked = false;
        selectAllCheckbox.indeterminate = false;
      } else if (checkedCount === checkboxes.length) {
        selectAllCheckbox.checked = true;
        selectAllCheckbox.indeterminate = false;
      } else {
        selectAllCheckbox.checked = false;
        selectAllCheckbox.indeterminate = true;
      }
    }

    document.addEventListener('DOMContentLoaded', function() {
      renderAccounts();

      // Xử lý sự kiện cho checkbox "Chọn tất cả"
      document.getElementById('selectAllCheckbox').addEventListener('change', function() {
        const checkboxes = document.querySelectorAll('.choose-account');
        checkboxes.forEach(checkbox => {
          checkbox.checked = this.checked;
        });
      });

      document.addEventListener('change', function(e) {
        if (e.target.classList.contains('choose-account')) {
          updateSelectAllCheckbox();
        }
      });

      // Xử lý thêm tài khoản mới
      document.getElementById('saveNewAccountBtn').addEventListener('click', function() {
        const type = document.getElementById('newAccountType').value;
        const username = document.getElementById('newAccountUsername').value;

        if (!type || !username) {
          alert('⚠️ Vui lòng nhập đầy đủ thông tin!');
          return;
        }

        // Thêm tài khoản mới
        accountsData.accounts.push({
          type,
          username
        });

        const addModal = bootstrap.Modal.getInstance(document.getElementById('addAccountModal'));
        addModal.hide();

        document.getElementById('addAccountForm').reset();

        renderAccounts();

        alert("✅ Thêm tài khoản thành công!");
      });

      // Xử lý cập nhật tài khoản
      document.getElementById('updateAccountBtn').addEventListener('click', function() {
        const type = document.getElementById('editAccountType').value;
        const username = document.getElementById('editAccountUsername').value;
        const editIndex = parseInt(document.getElementById('editIndex').value);

        if (editIndex !== -1) {
          accountsData.accounts[editIndex] = {
            type,
            username
          };

          const editModal = bootstrap.Modal.getInstance(document.getElementById('editAccountModal'));
          editModal.hide();

          document.getElementById('editAccountForm').reset();

          renderAccounts();

          alert("✅ Cập nhật tài khoản thành công!");
        }
      });

      document.getElementById('confirmDeleteBtn').addEventListener('click', deleteAccount);

      // Xác nhận chọn tài khoản
      document.getElementById('confirmBtn').addEventListener('click', () => {
        const selected = [...document.querySelectorAll('.choose-account:checked')]
          .map(c => ({
            type: c.dataset.type,
            username: c.dataset.username
          }));

        if (selected.length === 0) {
          alert('⚠️ Vui lòng chọn ít nhất một tài khoản!');
          return;
        }

        alert("✅ Bạn đã chọn: " + selected.map(s => s.type).join(", "));

        // Hiển thị kết quả chi tiết hơn
        console.log("Tài khoản đã chọn:", selected);
      });
    });
  </script>

</body>

</html>