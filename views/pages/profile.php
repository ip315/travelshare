<?php include __DIR__ . '/../layouts/sidebar.php'; ?>
<?php
require_once __DIR__ . '/../../config/db.php';
$user = $_SESSION['user_data'];
$avatar = $user['avatar']
    ? (BASE_URL . 'assets/uploads/' . $user['avatar'])
    : (IMAGES_URL . 'default.jpg');
// Đếm số lượng bài viết của user
$user_id = intval($user['id']);
$post_count = 0;
if ($user_id > 0) {
    $result = $mysqli->query("SELECT COUNT(*) as total FROM posts WHERE user_id = $user_id");
    if ($result) {
        $row = $result->fetch_assoc();
        $post_count = $row['total'];
    }
}
?>
<div class="sidebar-overlay"></div>
<button id="sidebar-toggle" class="sidebar-toggle">
  <span></span>
  <span></span>
  <span></span>
</button>
<main class="main-content">
  <div class="container profile-container">
    <div class="profile-header-modern">
      <div class="profile-avatar-wrap">
        <img src="<?php echo $avatar; ?>" alt="avatar" class="profile-avatar" id="profile-avatar" onerror="this.onerror=null;this.src='<?php echo IMAGES_URL; ?>default.jpg';" />
      </div>
      <div class="profile-info-modern">
        <h1 id="profile-username" class="profile-username-modern">Tên người dùng</h1>
        <p id="profile-email" class="profile-email-modern"></p>
        <p id="profile-bio" class="profile-bio-modern">Mô tả ngắn</p>
      </div>
    </div>
    <div class="profile-stats-modern">
      <div class="stat-item-modern">
        <strong id="post-count"><?php echo $post_count; ?></strong>
        <span>Bài viết</span>
      </div>
      <div class="stat-item-modern">
        <strong id="follower-count">0</strong>
        <span>Người theo dõi</span>
      </div>
      <div class="stat-item-modern">
        <strong id="following-count">0</strong>
        <span>Đang theo dõi</span>
      </div>
    </div>
    <div class="profile-content">
      <nav class="profile-nav-modern">
        <a href="#" class="tab active">📝 Bài viết</a>
        <a href="<?php echo BASE_URL; ?>public/edit_post.php" class="tab">📅 Chỉnh sửa bài viết</a>
      </nav>
      <div class="posts-grid" id="user-posts-grid"></div>
    </div>
  </div>
</main>