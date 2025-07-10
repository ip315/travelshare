document.addEventListener("DOMContentLoaded", function () {
  // --- 1. Logic để tô sáng mục menu đang hoạt động ---
  const currentPage = document.body.dataset.page;
  if (currentPage) {
    const activeNavItem = document.querySelector(
      `.sidebar-nav .nav-item[data-page='${currentPage}']`
    );
    if (activeNavItem) {
      activeNavItem.classList.add("active");
    }
  }

  // --- 2. Logic cho nút bật/tắt sidebar trên di động ---
  const sidebar = document.getElementById("sidebar");
  const toggleBtn = document.getElementById("sidebar-toggle");
  const overlay = document.querySelector(".sidebar-overlay");

  if (sidebar && toggleBtn && overlay) {
    toggleBtn.addEventListener("click", function () {
      sidebar.classList.toggle("active");
      toggleBtn.classList.toggle("active");
      overlay.classList.toggle("active");
    });

    overlay.addEventListener("click", function () {
      sidebar.classList.remove("active");
      toggleBtn.classList.remove("active");
      overlay.classList.remove("active");
    });
  }

  // --- 3. Logic cho hộp thoại xác nhận đăng xuất ---
  const logoutModal = document.getElementById("logoutModal");

  // Hàm để hiển thị hộp thoại (gọi từ onclick trên thẻ a)
  window.handleLogout = function (event) {
    event.preventDefault();
    if (logoutModal) {
      logoutModal.style.display = "flex";
    }
  };

  // Hàm để đóng hộp thoại
  window.closeLogoutModal = function () {
    if (logoutModal) {
      logoutModal.style.display = "none";
    }
  };

  // Hàm để xác nhận đăng xuất
  window.confirmLogout = async function () {
    console.log("Đang đăng xuất...");
    try {
      const response = await fetch(window.BASE_URL + 'controllers/AuthController.php?action=logout');
      const result = await response.json();
      if (result.success) {
        // Chuyển hướng về trang chủ (index.php) sau khi đăng xuất
        window.location.href = window.BASE_URL + 'index.php';
      } else {
        alert(result.message || 'Đăng xuất không thành công.');
      }
    } catch (error) {
      console.error("Lỗi khi đăng xuất:", error);
      alert("Có lỗi xảy ra, không thể đăng xuất.");
    }
  };

  // Đóng hộp thoại khi nhấn ra ngoài
  window.addEventListener("click", function (event) {
    if (event.target === logoutModal) {
      closeLogoutModal();
    }
  });

  // --- 4. Cập nhật năm hiện tại ở footer ---
  const yearSpan = document.getElementById("current-year");
  if (yearSpan) {
    yearSpan.textContent = new Date().getFullYear();
  }
});
