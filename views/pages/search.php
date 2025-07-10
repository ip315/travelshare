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
        require_once dirname(__DIR__, 2) . '/config/db.php';
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

<div id="suggestionBox" class="suggestion-list" style="display:none;"></div>
<style>
.suggestion-list {
  border: 1px solid #ccc;
  background: #fff;
  position: absolute;
  z-index: 1000;
  width: 100%;
  max-height: 200px;
  overflow-y: auto;
}
.suggestion-item {
  padding: 8px 12px;
  cursor: pointer;
}
.suggestion-item:hover {
  background: #f0f0f0;
}
.suggestion-item.post-suggestion {
  display: flex;
  align-items: flex-start;
  gap: 12px;
  background: #f8fafd;
  border-radius: 8px;
  padding: 12px 16px;
  margin-bottom: 8px;
  box-shadow: 0 2px 8px rgba(0,0,0,0.04);
  cursor: pointer;
  border: 1px solid #e3e8ee;
  transition: background 0.2s, box-shadow 0.2s;
}
.suggestion-item.post-suggestion:hover {
  background: #e6f0ff;
  box-shadow: 0 4px 16px rgba(0,0,0,0.08);
}
.suggestion-icon {
  font-size: 28px;
  color: #1976d2;
  margin-top: 2px;
}
.suggestion-content {
  flex: 1;
}
.suggestion-title {
  font-weight: bold;
  font-size: 16px;
  color: #222;
}
.suggestion-meta {
  font-size: 13px;
  color: #888;
  margin-top: 2px;
}
.suggestion-snippet {
  font-size: 14px;
  color: #444;
  margin-top: 4px;
}
#searchResults {
  display: none;
}
</style>
<script>
const searchInput = document.getElementById('searchInput');
const suggestionBox = document.getElementById('suggestionBox');
const searchResults = document.getElementById('searchResults');

searchInput.addEventListener('input', function() {
  const query = this.value.trim();
  if (query.length === 0) {
    suggestionBox.style.display = 'none';
    if (searchResults) searchResults.style.display = 'none';
    return;
  }
  fetch('/travelshare_web/public/api_suggest.php?q=' + encodeURIComponent(query))
    .then(res => res.json())
    .then(data => {
      if (data.length === 0) {
        suggestionBox.style.display = 'none';
        if (searchResults) searchResults.style.display = 'none';
        return;
      }
      suggestionBox.innerHTML = data.map(item => {
        if (item.type === 'post') {
          return `<div class="suggestion-item post-suggestion" onclick="goToPost(${item.id})">
            <div class="suggestion-icon"><i class='fa fa-file-alt'></i></div>
            <div class="suggestion-content">
              <div class="suggestion-title">${item.title}</div>
              <div class="suggestion-meta">${item.location ? 'Địa điểm: ' + item.location : ''}</div>
              <div class="suggestion-snippet">${item.content ? item.content.substring(0, 50) + '...' : ''}</div>
            </div>
          </div>`;
        }
        if (item.type === 'user') {
          return `<div class="suggestion-item" onclick="goToUser('${item.username.replace(/'/g, "\\'")}')">
            <strong>Người dùng:</strong> ${item.username}
          </div>`;
        }
        if (item.type === 'location') {
          return `<div class="suggestion-item" onclick="selectSuggestion('${item.location.replace(/'/g, "\\'")}')">
            <strong>Địa điểm:</strong> ${item.location}
          </div>`;
        }
      }).join('');
      const rect = searchInput.getBoundingClientRect();
      suggestionBox.style.top = (searchInput.offsetTop + searchInput.offsetHeight) + 'px';
      suggestionBox.style.left = searchInput.offsetLeft + 'px';
      suggestionBox.style.width = searchInput.offsetWidth + 'px';
      suggestionBox.style.display = 'block';
      if (searchResults) searchResults.style.display = 'block';
    });
});

function selectSuggestion(value) {
  searchInput.value = value;
  suggestionBox.style.display = 'none';
}
document.addEventListener('click', function(e) {
  if (!suggestionBox.contains(e.target) && e.target !== searchInput) {
    suggestionBox.style.display = 'none';
  }
});

function goToPost(postId) {
  window.location.href = '/travelshare_web/public/blog.php?id=' + postId;
}
function goToUser(username) {
  window.location.href = '/travelshare_web/public/index.php?user=' + encodeURIComponent(username);
}
</script>

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