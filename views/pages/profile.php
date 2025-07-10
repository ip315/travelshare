<?php include __DIR__ . '/../layouts/sidebar.php'; ?>
<div class="sidebar-overlay"></div>
    <button id="sidebar-toggle" class="sidebar-toggle">
      <span></span>
      <span></span>
      <span></span>
    </button>

    <main class="main-content">
      <div class="container">
        <div class="profile-header">
          <img src="<?php echo IMAGES_URL; ?>default_avatar.png" alt="avatar" class="profile-avatar" id="profile-avatar" />
          <div class="profile-info">
            <h1 id="profile-username">Tên người dùng</h1>
            <p id="profile-email" style="color:#888;font-size:14px;margin:2px 0 6px 0;"></p>
            <p id="profile-bio">Mô tả ngắn</p>
          </div>
        </div>
        <div class="profile-stats">
          <div class="stat-item">
            <strong id="post-count">0</strong>
            <span>Bài viết</span>
          </div>
          <div class="stat-item">
            <strong id="follower-count">0</strong>
            <span>Người theo dõi</span>
          </div>
          <div class="stat-item">
            <strong id="following-count">0</strong>
            <span>Đang theo dõi</span>
          </div>
        </div>
        <div class="profile-content">
          <nav style="margin-bottom: 24px">
            <a href="#" class="tab active" style="text-decoration: none"
              >📝 Bài viết</a
            >
            <a href="<?php echo BASE_URL; ?>public/edit_post.php" class="tab" style="text-decoration: none">📅 Chỉnh sửa bài viết</a>
          </nav>
          <div class="posts-grid" id="user-posts-grid"></div>
        </div>
      </div>
    </main>