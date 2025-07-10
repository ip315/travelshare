<?php include __DIR__ . '/../layouts/sidebar.php'; ?>
<div class="sidebar-overlay"></div>
    <button id="sidebar-toggle" class="sidebar-toggle">
      <span></span>
      <span></span>
      <span></span>
    </button>

    <main class="main-content">
      <div class="container">
        <h1>Chỉnh sửa bài viết</h1>
        <form class="edit-post-form" id="editPostForm">
          <div class="form-group">
            <label for="edit-title">Tiêu đề bài viết</label>
            <input
              type="text"
              id="edit-title"
              name="edit-title"
              value=""
              required
            />
          </div>
          <div class="form-group">
            <label for="edit-content">Nội dung</label>
            <textarea id="edit-content" name="edit-content" rows="6" required></textarea>
          </div>
          <div class="form-group">
            <label for="edit-image">Ảnh bài viết</label>
            <div class="image-upload">
              <input
                type="file"
                id="edit-image"
                name="edit-image"
                accept="image/*"
                style="display: none"
              />
              <label for="edit-image" class="upload-btn">Chọn ảnh mới</label>
              <div class="image-preview">
                <img
                  src=""
                  alt="Ảnh hiện tại"
                  class="preview-image"
                  id="currentImage"
                />
              </div>
            </div>
          </div>
          <div class="form-actions">
            <button type="submit" class="btn-primary">Lưu thay đổi</button>
            <a href="<?php echo BASE_URL; ?>public/profile.php" class="btn-secondary">Hủy</a>
          </div>
        </form>
      </div>
    </main>