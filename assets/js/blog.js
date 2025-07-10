document.addEventListener("DOMContentLoaded", function () {
  const grid = document.getElementById("postsGrid");

  // Hàm render bài đăng
  function renderPosts(posts) {
    if (!grid) return;
    grid.innerHTML = ''; // Xóa nội dung cũ

    if (!posts.length) {
      grid.innerHTML = "<p>Chưa có bài đăng nào.</p>";
      return;
    }
    grid.innerHTML = posts
      .map(
        (post) => {
          // Xử lý nhiều ảnh
          let imagesHtml = '';
          if (Array.isArray(post.images) && post.images.length > 1) {
            imagesHtml = `<div class="post-images-grid">` +
              post.images.map(img =>
                `<img src="${window.BASE_URL + 'assets/uploads/' + img}" class="post-image-multi" />`
              ).join('') +
              `</div>`;
          } else if (post.image || (Array.isArray(post.images) && post.images.length === 1)) {
            const imgSrc = post.image
              ? (window.BASE_URL + 'assets/uploads/' + post.image)
              : (window.BASE_URL + 'assets/uploads/' + post.images[0]);
            imagesHtml = `<img src="${imgSrc}" class="post-image" />`;
          }

          return `
  <div class="post-item" data-post-id="${post.id}">
    <div class="post-header">
      <img src="${post.avatar ? (window.BASE_URL + 'assets/uploads/' + post.avatar) : (window.IMAGES_URL + 'default_avatar.png')}" class="avatar" />
      <div class="post-user-info">
        <span class="post-username">${post.username || ''}</span>
        <div class="post-meta">
          ${post.location ? `<span class="post-location"><i class="fa fa-map-marker-alt"></i> ${post.location}</span>` : ""}
          ${post.feeling ? `<span class="post-feeling"><i class="fa fa-smile"></i> Đang cảm thấy <b>${post.feeling}</b></span>` : ""}
          <span class="post-time"><i class="fa fa-clock"></i> ${new Date(post.createdAt).toLocaleString("vi-VN")}</span>
        </div>
      </div>
    </div>
    <h3>${post.title || "(Không có tiêu đề)"}</h3>
    <p>${post.content}</p>
    ${imagesHtml}
    <div class="post-actions">
      <button class="like-btn" data-liked="${post.liked ? '1' : '0'}">
        <i class="fa${post.liked ? 's' : 'r'} fa-thumbs-up"></i> Thích (<span class="like-count">${post.likeCount || 0}</span>)
      </button>
      <button class="comment-btn">
        <i class="far fa-comment"></i> Bình luận (<span class="comment-count">${post.commentCount || 0}</span>)
      </button>
    </div>
    <div class="comments-section" style="display:none;">
      <div class="comments-list"></div>
      <div class="comment-form">
        <input type="text" class="comment-input" placeholder="Viết bình luận..." />
        <button class="send-comment-btn"><i class="fa fa-paper-plane"></i></button>
      </div>
    </div>
  </div>
`;
        }
      )
      .join("");

    attachPostEvents();
  }
  
   async function fetchPosts() {
     if (!grid) return;
     grid.innerHTML = "<p>Đang tải bài đăng...</p>";
     try {
       const response = await fetch(window.BASE_URL + 'public/api_get_posts.php');
       if (!response.ok) {
         throw new Error(`HTTP error! status: ${response.status}`);
       }
       const data = await response.json();
       if (data.success) {
         renderPosts(data.posts);
       } else {
         throw new Error(data.message || "Không thể lấy dữ liệu bài đăng.");
       }
     } catch (error) {
       console.error("Error fetching posts:", error);
       grid.innerHTML = "<p>Không thể tải bài đăng. Vui lòng thử lại sau.</p>";
     }
   }

  // fetchPosts();
  fetchPosts();
});

function attachPostEvents() {
  document.querySelectorAll('.like-btn').forEach(btn => {
    btn.addEventListener('click', function() {
      const liked = this.getAttribute('data-liked') === '1';
      const likeCountSpan = this.querySelector('.like-count');
      let count = parseInt(likeCountSpan.textContent) || 0;
      if (liked) {
        this.setAttribute('data-liked', '0');
        this.querySelector('i').classList.remove('fas');
        this.querySelector('i').classList.add('far');
        count = Math.max(0, count - 1);
      } else {
        this.setAttribute('data-liked', '1');
        this.querySelector('i').classList.remove('far');
        this.querySelector('i').classList.add('fas');
        count = count + 1;
      }
      likeCountSpan.textContent = count;
    });
  });
  document.querySelectorAll('.comment-btn').forEach(btn => {
    btn.addEventListener('click', function() {
      const postItem = this.closest('.post-item');
      const commentsSection = postItem.querySelector('.comments-section');
      commentsSection.style.display = commentsSection.style.display === 'block' ? 'none' : 'block';
    });
  });
  document.querySelectorAll('.send-comment-btn').forEach(btn => {
    btn.addEventListener('click', function() {
      const postItem = this.closest('.post-item');
      const input = postItem.querySelector('.comment-input');
      const commentsList = postItem.querySelector('.comments-list');
      const commentText = input.value.trim();
      if (commentText) {
        const commentDiv = document.createElement('div');
        commentDiv.className = 'comment-item';
        commentDiv.innerHTML = `<img src="${window.IMAGES_URL}default_avatar.png" class="comment-avatar" /><div class="comment-content">${commentText}</div>`;
        commentsList.appendChild(commentDiv);
        input.value = '';
        // Tăng số bình luận hiển thị
        const countSpan = postItem.querySelector('.comment-count');
        countSpan.textContent = (parseInt(countSpan.textContent) || 0) + 1;
      }
    });
  });
}