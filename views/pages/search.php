<?php include __DIR__ . '/../layouts/sidebar.php'; ?>
<div class="sidebar-overlay"></div>
<button id="sidebar-toggle" class="sidebar-toggle">
  <span></span>
  <span></span>
  <span></span>
</button>

<main class="main-content">
  <div class="container">
    <div class="search-section">
      <div class="search-card">
        <h1 class="search-title">Tìm kiếm</h1>
        <p class="search-desc">Tìm kiếm địa điểm, bài đăng, người dùng một cách nhanh chóng và dễ dàng.</p>
        <div class="search-box">
          <input
            type="text"
            id="searchInput"
            placeholder="Tìm kiếm địa điểm, bài đăng, người dùng..."
          />
          <button onclick="performSearch()" class="search-btn">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path
                stroke-linecap="round"
                stroke-linejoin="round"
                stroke-width="2"
                d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"
              ></path>
            </svg>
            <span>Tìm kiếm</span>
          </button>
        </div>
      </div>
      <div class="search-results" id="searchResults">
        <?php
        require_once __DIR__ . '/../config/db.php';
        // Lấy danh sách bài đăng
        $result = $mysqli->query("SELECT p.*, u.username, u.avatar FROM posts p JOIN users u ON p.user_id = u.id ORDER BY p.created_at DESC");
        while ($post = $result->fetch_assoc()):
        ?>
        <div class="post-card">
          <div class="post-header">
            <img src="<?= $post['avatar'] ? (BASE_URL . 'assets/uploads/' . $post['avatar']) : (IMAGES_URL . 'default_avatar.png') ?>" class="avatar">
            <span><?= htmlspecialchars($post['username']) ?></span>
            <span class="post-time"><?= $post['created_at'] ?></span>
          </div>
          <h3><?= htmlspecialchars($post['title']) ?></h3>
          <div><?= htmlspecialchars($post['location']) ?></div>
          <div><?= nl2br(htmlspecialchars($post['content'])) ?></div>
          <?php if ($post['image']): ?>
            <img src="<?= $post['image'] ?>" class="post-image">
          <?php endif; ?>
          <div class="post-actions">
            <button>Like (<?= getLikeCount($post['id']) ?>)</button>
            <button>Bình luận (<?= getCommentCount($post['id']) ?>)</button>
          </div>
        </div>
        <?php endwhile; ?>
      </div>
    </div>
  </div>
</main>

<?php
function getLikeCount($post_id) {
    global $mysqli;
    $result = $mysqli->query("SELECT COUNT(*) as total FROM likes WHERE post_id = " . intval($post_id));
    $row = $result->fetch_assoc();
    return $row['total'];
}

function getCommentCount($post_id) {
    global $mysqli;
    $result = $mysqli->query("SELECT COUNT(*) as total FROM comments WHERE post_id = " . intval($post_id));
    $row = $result->fetch_assoc();
    return $row['total'];
}
?>