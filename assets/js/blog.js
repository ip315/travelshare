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
      <button class="like-btn" data-post-id="${post.id}" data-liked="${post.liked ? '1' : '0'}">
        <i class="fa${post.liked ? 's' : 'r'} fa-thumbs-up"></i> Thích (<span class="like-count">${post.likeCount || 0}</span>)
      </button>
      <button class="comment-btn" data-post-id="${post.id}">
        <i class="far fa-comment"></i> Bình luận (<span class="comment-count">${post.commentCount || 0}</span>)
      </button>
    </div>
    <div class="comments-section" style="display:none;">
      <div class="comments-list"></div>
      <div class="comment-form">
        <input type="text" class="comment-input" placeholder="Viết bình luận..." maxlength="1000" />
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
         // Lọc theo user nếu có
         const urlParams = new URLSearchParams(window.location.search);
         const user = urlParams.get('user');
         let posts = data.posts;
         if (user) {
           posts = posts.filter(post => post.username === user);
         }
         renderPosts(posts);
         // Cuộn tới bài viết nếu có id
         const postId = urlParams.get('id');
         if (postId) {
           setTimeout(function() {
             const postElem = document.querySelector(`[data-post-id='${postId}']`);
             if (postElem) {
               postElem.scrollIntoView({ behavior: 'smooth', block: 'center' });
               postElem.style.background = '#ffffcc';
               setTimeout(() => postElem.style.background = '', 2000);
             }
           }, 500);
         }
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
  // Xử lý like
  document.querySelectorAll('.like-btn').forEach(btn => {
    btn.addEventListener('click', async function() {
      const postId = this.getAttribute('data-post-id');
      const likeCountSpan = this.querySelector('.like-count');
      const likeIcon = this.querySelector('i');
      
      // Disable button để tránh spam click
      this.disabled = true;
      
      try {
        const formData = new FormData();
        formData.append('post_id', postId);
        
        const response = await fetch(window.BASE_URL + 'public/api_like.php', {
          method: 'POST',
          body: formData
        });
        
        const data = await response.json();
        
        if (data.success) {
          // Cập nhật UI
          const liked = data.action === 'liked';
          this.setAttribute('data-liked', liked ? '1' : '0');
          
          if (liked) {
            likeIcon.classList.remove('far');
            likeIcon.classList.add('fas');
          } else {
            likeIcon.classList.remove('fas');
            likeIcon.classList.add('far');
          }
          
          // Cập nhật số lượng like
          likeCountSpan.textContent = data.likeCount;
          
          // Hiển thị thông báo
          showNotification(data.message, 'success');
        } else {
          showNotification(data.message || 'Có lỗi xảy ra', 'error');
        }
      } catch (error) {
        console.error('Error liking post:', error);
        showNotification('Có lỗi xảy ra khi thích bài đăng', 'error');
      } finally {
        // Re-enable button
        this.disabled = false;
      }
    });
  });
  
  // Xử lý comment
  document.querySelectorAll('.comment-btn').forEach(btn => {
    btn.addEventListener('click', async function() {
      const postId = this.getAttribute('data-post-id');
      const postItem = this.closest('.post-item');
      const commentsSection = postItem.querySelector('.comments-section');
      const commentsList = postItem.querySelector('.comments-list');
      
      // Toggle hiển thị comments
      const isVisible = commentsSection.style.display === 'block';
      
      if (!isVisible) {
        // Load comments khi mở
        commentsList.innerHTML = '<p>Đang tải bình luận...</p>';
        commentsSection.style.display = 'block';
        
        try {
          const response = await fetch(`${window.BASE_URL}public/api_get_comments.php?post_id=${postId}`);
          const data = await response.json();
          
          if (data.success) {
            renderComments(commentsList, data.comments);
          } else {
            commentsList.innerHTML = '<p>Không thể tải bình luận</p>';
          }
        } catch (error) {
          console.error('Error loading comments:', error);
          commentsList.innerHTML = '<p>Lỗi khi tải bình luận</p>';
        }
      } else {
        commentsSection.style.display = 'none';
      }
    });
  });
  
  // Xử lý gửi comment
  document.querySelectorAll('.send-comment-btn').forEach(btn => {
    btn.addEventListener('click', async function() {
      const postItem = this.closest('.post-item');
      const input = postItem.querySelector('.comment-input');
      const commentsList = postItem.querySelector('.comments-list');
      const commentCountSpan = postItem.querySelector('.comment-count');
      const postId = postItem.getAttribute('data-post-id');
      const commentText = input.value.trim();
      
      if (!commentText) {
        showNotification('Vui lòng nhập nội dung bình luận', 'error');
        return;
      }
      
      if (commentText.length > 1000) {
        showNotification('Bình luận quá dài (tối đa 1000 ký tự)', 'error');
        return;
      }
      
      // Disable button
      this.disabled = true;
      
      try {
        const formData = new FormData();
        formData.append('post_id', postId);
        formData.append('content', commentText);
        
        const response = await fetch(window.BASE_URL + 'public/api_comment.php', {
          method: 'POST',
          body: formData
        });
        
        const data = await response.json();
        
        if (data.success) {
          // Thêm comment mới vào danh sách
          const commentHtml = createCommentHTML(data.comment);
          commentsList.insertAdjacentHTML('beforeend', commentHtml);
          
          // Cập nhật số lượng comment
          commentCountSpan.textContent = data.commentCount;
          
          // Xóa nội dung input
          input.value = '';
          
          // Hiển thị thông báo
          showNotification(data.message, 'success');
          
          // Gắn sự kiện xóa cho comment mới
          attachCommentDeleteEvents();
        } else {
          showNotification(data.message || 'Có lỗi xảy ra', 'error');
        }
      } catch (error) {
        console.error('Error adding comment:', error);
        showNotification('Có lỗi xảy ra khi thêm bình luận', 'error');
      } finally {
        // Re-enable button
        this.disabled = false;
      }
    });
  });
  
  // Xử lý Enter key trong comment input
  document.querySelectorAll('.comment-input').forEach(input => {
    input.addEventListener('keypress', function(e) {
      if (e.key === 'Enter' && !e.shiftKey) {
        e.preventDefault();
        this.closest('.comment-form').querySelector('.send-comment-btn').click();
      }
    });
  });
}

// Hàm render comments
function renderComments(commentsList, comments) {
  if (comments.length === 0) {
    commentsList.innerHTML = '<p class="no-comments">Chưa có bình luận nào. Hãy là người đầu tiên bình luận!</p>';
    return;
  }
  
  const commentsHtml = comments.map(comment => createCommentHTML(comment)).join('');
  commentsList.innerHTML = commentsHtml;
  
  // Gắn sự kiện xóa comment
  attachCommentDeleteEvents();
}

// Hàm tạo HTML cho comment
function createCommentHTML(comment) {
  const avatarSrc = comment.avatar 
    ? (window.BASE_URL + 'assets/uploads/' + comment.avatar)
    : (window.IMAGES_URL + 'default_avatar.png');
  
  const timeAgo = getTimeAgo(comment.created_at);
  
  return `
    <div class="comment-item" data-comment-id="${comment.id}">
      <img src="${avatarSrc}" class="comment-avatar" alt="${comment.username}" />
      <div class="comment-content">
        <div class="comment-header">
          <span class="comment-username">${comment.username}</span>
          <span class="comment-time">${timeAgo}</span>
        </div>
        <div class="comment-text">${escapeHtml(comment.content)}</div>
        <button class="delete-comment-btn" data-comment-id="${comment.id}" style="display:none;">
          <i class="fa fa-trash"></i> Xóa
        </button>
      </div>
    </div>
  `;
}

// Hàm gắn sự kiện xóa comment
function attachCommentDeleteEvents() {
  document.querySelectorAll('.delete-comment-btn').forEach(btn => {
    btn.addEventListener('click', async function() {
      const commentId = this.getAttribute('data-comment-id');
      const commentItem = this.closest('.comment-item');
      const postItem = this.closest('.post-item');
      const commentCountSpan = postItem.querySelector('.comment-count');
      
      if (!confirm('Bạn có chắc muốn xóa bình luận này?')) {
        return;
      }
      
      try {
        const formData = new FormData();
        formData.append('comment_id', commentId);
        
        const response = await fetch(window.BASE_URL + 'public/api_delete_comment.php', {
          method: 'POST',
          body: formData
        });
        
        const data = await response.json();
        
        if (data.success) {
          // Xóa comment khỏi UI
          commentItem.remove();
          
          // Cập nhật số lượng comment
          commentCountSpan.textContent = data.commentCount;
          
          // Hiển thị thông báo
          showNotification(data.message, 'success');
          
          // Kiểm tra nếu không còn comment nào
          const commentsList = postItem.querySelector('.comments-list');
          if (commentsList.children.length === 0) {
            commentsList.innerHTML = '<p class="no-comments">Chưa có bình luận nào. Hãy là người đầu tiên bình luận!</p>';
          }
        } else {
          showNotification(data.message || 'Có lỗi xảy ra', 'error');
        }
      } catch (error) {
        console.error('Error deleting comment:', error);
        showNotification('Có lỗi xảy ra khi xóa bình luận', 'error');
      }
    });
  });
  
  // Hiển thị nút xóa khi hover (chỉ cho comment của user hiện tại)
  document.querySelectorAll('.comment-item').forEach(item => {
    item.addEventListener('mouseenter', function() {
      const deleteBtn = this.querySelector('.delete-comment-btn');
      if (deleteBtn) {
        deleteBtn.style.display = 'inline-block';
      }
    });
    
    item.addEventListener('mouseleave', function() {
      const deleteBtn = this.querySelector('.delete-comment-btn');
      if (deleteBtn) {
        deleteBtn.style.display = 'none';
      }
    });
  });
}

// Hàm tính thời gian trước
function getTimeAgo(dateString) {
  const now = new Date();
  const date = new Date(dateString);
  const diffInSeconds = Math.floor((now - date) / 1000);
  
  if (diffInSeconds < 60) {
    return 'Vừa xong';
  } else if (diffInSeconds < 3600) {
    const minutes = Math.floor(diffInSeconds / 60);
    return `${minutes} phút trước`;
  } else if (diffInSeconds < 86400) {
    const hours = Math.floor(diffInSeconds / 3600);
    return `${hours} giờ trước`;
  } else if (diffInSeconds < 2592000) {
    const days = Math.floor(diffInSeconds / 86400);
    return `${days} ngày trước`;
  } else {
    return date.toLocaleDateString('vi-VN');
  }
}

// Hàm escape HTML
function escapeHtml(text) {
  const div = document.createElement('div');
  div.textContent = text;
  return div.innerHTML;
}

// Hàm hiển thị thông báo
function showNotification(message, type = 'info') {
  // Tạo element thông báo
  const notification = document.createElement('div');
  notification.className = `notification notification-${type}`;
  notification.style.cssText = `
    position: fixed;
    top: 20px;
    right: 20px;
    padding: 12px 20px;
    border-radius: 8px;
    color: white;
    font-weight: 500;
    z-index: 10000;
    max-width: 300px;
    word-wrap: break-word;
    box-shadow: 0 4px 12px rgba(0,0,0,0.15);
    transform: translateX(100%);
    transition: transform 0.3s ease;
  `;
  
  // Màu sắc theo loại thông báo
  if (type === 'success') {
    notification.style.backgroundColor = '#4caf50';
  } else if (type === 'error') {
    notification.style.backgroundColor = '#f44336';
  } else {
    notification.style.backgroundColor = '#2196f3';
  }
  
  notification.textContent = message;
  document.body.appendChild(notification);
  
  // Hiển thị thông báo
  setTimeout(() => {
    notification.style.transform = 'translateX(0)';
  }, 100);
  
  // Tự động ẩn sau 3 giây
  setTimeout(() => {
    notification.style.transform = 'translateX(100%)';
    setTimeout(() => {
      document.body.removeChild(notification);
    }, 300);
  }, 3000);
}