<div id="sidebar" class="sidebar">
  <div class="sidebar-header">
    <?php
    if (session_status() === PHP_SESSION_NONE) session_start();
    $avatar = isset($_SESSION['avatar']) && $_SESSION['avatar']
        ? (BASE_URL . 'assets/uploads/' . $_SESSION['avatar'])
        : (IMAGES_URL . '@defause.jpg');
    $username = $_SESSION['username'] ?? 'Khách';
    ?>
    <div style="display:flex;align-items:center;flex-direction:column;margin-top:10px;">
      <img src="<?php echo $avatar; ?>" alt="Avatar" class="sidebar-user-avatar" style="width:48px;height:48px;border-radius:50%;margin-bottom:4px;" onerror="this.onerror=null;this.src='<?php echo IMAGES_URL; ?>@defause.jpg';">
      <span class="sidebar-user-name" style="font-weight:bold;font-size:15px;line-height:1;"><?php echo htmlspecialchars($username); ?></span>
    </div>
  </div>
  <nav class="sidebar-nav">
    <a href="<?php echo BASE_URL; ?>public/blog.php" class="nav-item" data-page="home">
      <img src="<?php echo IMAGES_URL; ?>home.png" alt="Home" class="nav-icon" id="home-icon" />
      <span>Trang chủ</span>
    </a>
    <a href="<?php echo BASE_URL; ?>public/search.php" class="nav-item" data-page="search">
      <img src="<?php echo IMAGES_URL; ?>search.png" alt="Search" class="nav-icon" id="search-icon" />
      <span>Tìm kiếm</span>
    </a>
    <a href="<?php echo BASE_URL; ?>public/notifications.php" class="nav-item" data-page="notifications">
      <img src="<?php echo IMAGES_URL; ?>notification.png" alt="Notifications" class="nav-icon" id="notifications-icon" />
      <span>Thông báo</span>
    </a>
    <a href="<?php echo BASE_URL; ?>public/create_post.php" class="nav-item" data-page="create">
      <img src="<?php echo IMAGES_URL; ?>create_post.png" alt="Create" class="nav-icon" id="create-icon" />
      <span>Tạo bài đăng</span>
    </a>
    <a href="<?php echo BASE_URL; ?>public/profile.php" class="nav-item" data-page="profile">
      <?php
      $avatarIcon = isset($_SESSION['avatar']) && $_SESSION['avatar'] ? (BASE_URL . 'assets/uploads/' . $_SESSION['avatar']) : (IMAGES_URL . 'profile.svg');
      ?>
      <img src="<?php echo $avatarIcon; ?>" alt="Profile" class="nav-icon profile-img" id="profile-icon" style="object-fit:cover;border-radius:50%;width:32px;height:32px;" />
      <span>Trang cá nhân</span>
    </a>
    <a href="#" class="nav-item" data-page="logout" onclick="handleLogout(event)">
      <img src="<?php echo IMAGES_URL; ?>logout.png" alt="Logout" class="nav-icon" id="logout-icon" />
      <span>Đăng xuất</span>
    </a>
  </nav>
  <div class="sidebar-footer">
    <p>&copy; <span id="current-year"></span> TravelShare</p>
  </div>

  <div id="logoutModal" class="logout-modal">
    <div class="logout-modal-content">
      <h3>Bạn có chắc muốn đăng xuất?</h3>
      <div class="logout-modal-buttons">
        <button type="button" onclick="closeLogoutModal()" class="logout-cancel-btn">Hủy</button>
        <button type="button" onclick="confirmLogout()" class="logout-confirm-btn">Đăng xuất</button>
      </div>
    </div>
  </div>
</div>