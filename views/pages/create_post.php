<?php include __DIR__ . '/../layouts/sidebar.php'; ?>
<div class="sidebar-overlay"></div>

<button id="sidebar-toggle" class="sidebar-toggle">
  <span></span>
  <span></span>
  <span></span>
</button>

<main class="main-content">
  <div class="container">
    <div class="create-post-card">
      <div class="create-post-header">
        <h2>Tạo bài đăng</h2>
        <button class="create-post-close" onclick="window.history.back()">
          <svg width="24" height="24" fill="currentColor" viewBox="0 0 24 24">
            <path d="M19 6.41L17.59 5 12 10.59 6.41 5 5 6.41 10.59 12 5 17.59 6.41 19 12 13.41 17.59 19 19 17.59 13.41 12z" />
          </svg>
        </button>
      </div>
      <div class="create-post-user">
        <?php
        require_once __DIR__ . '/../../config/db.php';
        if (session_status() === PHP_SESSION_NONE) session_start();
        $avatar = IMAGES_URL . 'profile.svg';
        $username = 'Người dùng';
        if (isset($_SESSION['user_id'])) {
            $user_id = $_SESSION['user_id'];
            $stmt = $mysqli->prepare("SELECT username, avatar FROM users WHERE id = ?");
            $stmt->bind_param("i", $user_id);
            $stmt->execute();
            $stmt->bind_result($username_db, $avatar_db);
            if ($stmt->fetch()) {
                $username = $username_db;
                if ($avatar_db) {
                    $avatar = BASE_URL . 'assets/uploads/' . $avatar_db;
                }
            }
            $stmt->close();
        }
        ?>
        <img src="<?php echo $avatar; ?>" alt="User Avatar" class="create-post-avatar" id="post-user-avatar" />
        <div class="create-post-user-details">
          <h3 id="post-username"><?php echo htmlspecialchars($username); ?></h3>
          <div class="create-post-privacy">
            <button class="create-post-privacy-btn" onclick="togglePrivacyMenu()">
              <svg width="16" height="16" fill="currentColor" viewBox="0 0 24 24">
                <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z" />
              </svg>
              <span>Công khai</span>
              <svg width="16" height="16" fill="currentColor" viewBox="0 0 24 24">
                <path d="M7 10l5 5 5-5z" />
              </svg>
            </button>
            <div id="privacyMenu" class="create-post-privacy-menu">
              <div class="privacy-option" onclick="selectPrivacy('public')">
                <svg width="20" height="20" fill="currentColor" viewBox="0 0 24 24">
                  <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z" />
                </svg>
                <div>
                  <strong>Công khai</strong>
                  <p>Mọi người đều có thể xem</p>
                </div>
              </div>
              <div class="privacy-option" onclick="selectPrivacy('friends')">
                <svg width="20" height="20" fill="currentColor" viewBox="0 0 24 24">
                  <path d="M16 4c0-1.11.89-2 2-2s2 .89 2 2-.89 2-2 2-2-.89-2-2zm4 18v-6h2.5l-2.54-7.63A2.996 2.996 0 0 0 17.06 7H16c-.8 0-1.54.37-2.01.99L12 10l-1.99-2.01A2.99 2.99 0 0 0 8 7H6.94c-1.4 0-2.59.93-2.9 2.37L1.5 16H4v6h2v-6h2.5l2.5-3 2.5 3H16v6h4z" />
                </svg>
                <div>
                  <strong>Bạn bè</strong>
                  <p>Chỉ bạn bè có thể xem</p>
                </div>
              </div>
              <div class="privacy-option" onclick="selectPrivacy('private')">
                <svg width="20" height="20" fill="currentColor" viewBox="0 0 24 24">
                  <path d="M18 8h-1V6c0-2.76-2.24-5-5-5S7 3.24 7 6v2H6c-1.1 0-2 .9-2 2v10c0 1.1.9 2 2 2h12c1.1 0 2-.9 2-2V10c0-1.1-.9-2-2-2zm-6 9c-1.1 0-2-.9-2-2s.9-2 2-2 2 .9 2 2-.9 2-2 2zm3.1-9H8.9V6c0-1.71 1.39-3.1 3.1-3.1 1.71 0 3.1 1.39 3.1 3.1v2z" />
                </svg>
                <div>
                  <strong>Chỉ mình tôi</strong>
                  <p>Chỉ bạn có thể xem</p>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="create-post-content">
        <div class="form-group">
          <input type="text" id="postTitle" placeholder="Tiêu đề bài đăng" class="create-post-title" style="width:100%;margin-bottom:12px;padding:10px;font-size:16px;border-radius:6px;border:1px solid #ccc;" />
        </div>
        <textarea
          id="postContent"
          placeholder="Bạn đang nghĩ gì về chuyến du lịch này?"
          class="create-post-textarea"
          oninput="adjustTextareaHeight(this)"
        ></textarea>
        <div class="create-post-location" id="locationInput">
          <svg width="20" height="20" fill="currentColor" viewBox="0 0 24 24">
            <path d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7zm0 9.5c-1.38 0-2.5-1.12-2.5-2.5s1.12-2.5 2.5-2.5 2.5 1.12 2.5 2.5-1.12 2.5-2.5 2.5z" />
          </svg>
          <div style="position:relative;">
            <input type="text" id="locationText" placeholder="Bạn đang ở đâu?" autocomplete="off" />
            <div id="locationSuggestions" style="position:absolute;top:100%;left:0;right:0;z-index:20;background:#fff;border:1px solid #ccc;border-radius:0 0 8px 8px;display:none;"></div>
          </div>
          <button onclick="removeLocation()" class="create-post-location-remove">
            <svg width="16" height="16" fill="currentColor" viewBox="0 0 24 24">
              <path d="M19 6.41L17.59 5 12 10.59 6.41 5 5 6.41 10.59 12 5 17.59 6.41 19 12 13.41 17.59 19 19 17.59 13.41 12z" />
            </svg>
          </button>
        </div>
        <div id="imagePreview" class="create-post-image-preview"></div>
      </div>
      <div class="create-post-actions">
        <span id="addToPostLabel">Thêm vào bài đăng của bạn</span>
        <div class="create-post-options">
          <button class="create-post-option-btn" onclick="document.getElementById('imageInput').click()" title="Ảnh/Video">
            <svg width="24" height="24" fill="#45bd62" viewBox="0 0 24 24">
              <path d="M19 7v2.99s-1.99.01-2 0V7h-3s.01-1.99 0-2h3V2h2v3h3v2h-3zm-3 4V8h-3V5H5c-1.1 0-2 .9-2 2v12c0 1.1.9 2 2 2h12c1.1 0 2-.9 2-2v-8h-3zM5 19l3-4 2 3 3-4 4 5H5z" />
            </svg>
          </button>
          <button class="create-post-option-btn" onclick="toggleLocationInput()" title="Check in">
            <svg width="24" height="24" fill="#f5533d" viewBox="0 0 24 24">
              <path d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7zm0 9.5c-1.38 0-2.5-1.12-2.5-2.5s1.12-2.5 2.5-2.5 2.5 1.12 2.5 2.5-1.12 2.5-2.5 2.5z" />
            </svg>
          </button>
          <button type="button" id="chooseFeelingBtn" class="create-post-option-btn" title="Chọn cảm xúc">😊</button>
          <div id="feelingDropdown" style="display:none;position:absolute;z-index:10;background:#fff;border:1px solid #ccc;border-radius:8px;padding:8px;">
            <div class="feeling-option" data-feeling="hạnh phúc">😊 Hạnh phúc</div>
            <div class="feeling-option" data-feeling="buồn">😢 Buồn</div>
            <div class="feeling-option" data-feeling="phấn khích">🤩 Phấn khích</div>
            <div class="feeling-option" data-feeling="mệt mỏi">😩 Mệt mỏi</div>
          </div>
          <span id="selectedFeeling" style="margin-left:10px;"></span>
          <input type="hidden" name="feeling" id="feelingInput" value="">
        </div>
      </div>
      <button class="create-post-btn" id="postBtn" onclick="publishPost()" disabled>
        Đăng
      </button>
    </div>
    <input
      type="file"
      id="imageInput"
      name="image"
      accept="image/*,video/*"
      style="display: none"
      onchange="handleFileSelect(event)"
      multiple
    />
    <span id="imageFilename" style="margin-left:10px;color:#555;font-style:italic;"></span>
  </div>
</main>
<script>
document.addEventListener('DOMContentLoaded', function() {
  const chooseBtn = document.getElementById('chooseFeelingBtn');
  const dropdown = document.getElementById('feelingDropdown');
  const selectedFeeling = document.getElementById('selectedFeeling');
  const feelingInput = document.getElementById('feelingInput');

  chooseBtn.addEventListener('click', function(e) {
    dropdown.style.display = dropdown.style.display === 'block' ? 'none' : 'block';
    dropdown.style.left = chooseBtn.offsetLeft + 'px';
    dropdown.style.top = (chooseBtn.offsetTop + chooseBtn.offsetHeight + 5) + 'px';
  });

  dropdown.querySelectorAll('.feeling-option').forEach(function(option) {
    option.addEventListener('click', function() {
      const icon = this.textContent.trim().split(' ')[0];
      const text = this.getAttribute('data-feeling');
      selectedFeeling.textContent = icon + ' Đang cảm thấy ' + text;
      feelingInput.value = text;
      dropdown.style.display = 'none';
    });
  });

  document.addEventListener('click', function(e) {
    if (!dropdown.contains(e.target) && e.target !== chooseBtn) {
      dropdown.style.display = 'none';
    }
  });

  const locations = [
    "An Giang", "Bà Rịa - Vũng Tàu", "Bắc Giang", "Bắc Kạn", "Bạc Liêu", "Bắc Ninh", "Bến Tre", "Bình Định",
    "Bình Dương", "Bình Phước", "Bình Thuận", "Cà Mau", "Cần Thơ", "Cao Bằng", "Đà Nẵng", "Đắk Lắk", "Đắk Nông",
    "Điện Biên", "Đồng Nai", "Đồng Tháp", "Gia Lai", "Hà Giang", "Hà Nam", "Hà Nội", "Hà Tĩnh", "Hải Dương",
    "Hải Phòng", "Hậu Giang", "Hòa Bình", "Hưng Yên", "Khánh Hòa", "Kiên Giang", "Kon Tum", "Lai Châu", "Lâm Đồng",
    "Lạng Sơn", "Lào Cai", "Long An", "Nam Định", "Nghệ An", "Ninh Bình", "Ninh Thuận", "Phú Thọ", "Phú Yên",
    "Quảng Bình", "Quảng Nam", "Quảng Ngãi", "Quảng Ninh", "Quảng Trị", "Sóc Trăng", "Sơn La", "Tây Ninh",
    "Thái Bình", "Thái Nguyên", "Thanh Hóa", "Thừa Thiên Huế", "Tiền Giang", "TP. Hồ Chí Minh", "Trà Vinh",
    "Tuyên Quang", "Vĩnh Long", "Vĩnh Phúc", "Yên Bái"
  ];
  const input = document.getElementById('locationText');
  const suggestions = document.getElementById('locationSuggestions');

  input.addEventListener('input', function() {
    const value = input.value.trim().toLowerCase();
    if (!value) {
      suggestions.style.display = 'none';
      suggestions.innerHTML = '';
      return;
    }
    const filtered = locations.filter(loc => loc.toLowerCase().includes(value));
    if (filtered.length === 0) {
      suggestions.style.display = 'none';
      suggestions.innerHTML = '';
      return;
    }
    suggestions.innerHTML = filtered.map(loc => `<div class="location-suggestion-item" style="padding:8px;cursor:pointer;">${loc}</div>`).join('');
    suggestions.style.display = 'block';
  });

  suggestions.addEventListener('click', function(e) {
    if (e.target.classList.contains('location-suggestion-item')) {
      input.value = e.target.textContent;
      suggestions.style.display = 'none';
    }
  });

  document.addEventListener('click', function(e) {
    if (!suggestions.contains(e.target) && e.target !== input) {
      suggestions.style.display = 'none';
    }
  });

  const imageInput = document.getElementById('imageInput');
  const addToPostLabel = document.getElementById('addToPostLabel');
  if (imageInput && addToPostLabel) {
    imageInput.addEventListener('change', function(e) {
      if (e.target.files && e.target.files.length > 0) {
        addToPostLabel.textContent = Array.from(e.target.files).map(f => f.name).join(', ');
      } else {
        addToPostLabel.textContent = 'Thêm vào bài đăng của bạn';
      }
    });
  }
});
</script>
<style>
.feeling-option {
  padding: 6px 12px;
  cursor: pointer;
  border-radius: 4px;
}
.feeling-option:hover {
  background: #f0f0f0;
}
.location-suggestion-item:hover {
  background: #f0f0f0;
}
</style>