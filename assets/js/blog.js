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
        (post) => `
    <div class="post-item">
      <img src="${post.avatar ? (window.BASE_URL + 'assets/uploads/' + post.avatar) : (window.IMAGES_URL + 'default_avatar.png')}" class="avatar" />
      <span class="post-username">${post.username || ''}</span>
      <h3>${post.title || "(Không có tiêu đề)"}</h3>
      <p>${post.content}</p>
      ${post.location ? `<p>Địa điểm: ${post.location}</p>` : ""}
      <small>${new Date(post.createdAt).toLocaleString("vi-VN")}</small>
    </div>
  `
      )
      .join("");
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