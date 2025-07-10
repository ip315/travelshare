document.addEventListener("DOMContentLoaded", function () {
    const profileAvatar = document.getElementById("profile-avatar");
    const profileUsername = document.getElementById("profile-username");
    const profileBio = document.getElementById("profile-bio");
    const postCountEl = document.getElementById("post-count");
    const followerCountEl = document.getElementById("follower-count");
    const followingCountEl = document.getElementById("following-count");
    const postsGrid = document.getElementById("user-posts-grid");

    // Cập nhật thông tin người dùng trên UI
    function updateProfileUI(user) {
        if (!user) return;
        profileAvatar.src = user.avatar
          ? (window.BASE_URL + 'assets/uploads/' + user.avatar)
          : (window.IMAGES_URL + 'default.jpg');
        profileAvatar.onerror = function() {
          this.onerror = null;
          this.src = window.IMAGES_URL + 'default.jpg';
        };
        profileUsername.textContent = user.username;
        if(document.getElementById('profile-email')) {
          document.getElementById('profile-email').textContent = user.email || '';
        }
        profileBio.textContent = user.bio || "Chưa có mô tả.";
        postCountEl.textContent = user.postsCount;
        followerCountEl.textContent = user.followersCount;
        followingCountEl.textContent = user.followingCount;
    }

    let allUserPosts = []; // Lưu trữ tất cả bài đăng để render lại sau khi xóa

    // Render bài đăng của người dùng
    function renderUserPosts(posts) {
        allUserPosts = posts; // Cập nhật danh sách bài đăng
        if (!postsGrid) return;
        if (posts.length === 0) {
            postsGrid.innerHTML = "<p>Chưa có bài đăng nào.</p>";
            return;
        }

        postsGrid.innerHTML = posts.map(post => `
            <div class="profile-post-item">
                <img src="${post.image_url || (window.IMAGES_URL + 'placeholder.png')}" alt="Post Image" />
                <div class="profile-post-content">
                    <h3>${post.title}</h3>
                    <p>${post.content}</p>
                    <div class="post-actions">
                        <button class="edit-btn" data-id="${post.id}">Sửa</button>
                        <button class="delete-btn" data-id="${post.id}">Xóa</button>
                    </div>
                </div>
            </div>
        `).join("");

        // Gắn sự kiện cho các nút Sửa/Xóa
        document.querySelectorAll(".edit-btn").forEach(button => {
            button.addEventListener("click", function() {
                const postId = this.dataset.id;
                window.location.href = `${window.BASE_URL}public/edit_post.php?id=${postId}`;
            });
        });

        document.querySelectorAll(".delete-btn").forEach(button => {
            button.addEventListener("click", function() {
                const postId = this.dataset.id;
                showDeleteConfirmModal(postId);
            });
        });
    }

    // Modal xác nhận xóa
    const deleteModal = document.getElementById('deletePostModal');
    const confirmDeleteBtn = document.getElementById('confirmDeleteBtn');
    const cancelDeleteBtn = document.getElementById('cancelDeleteBtn');
    let postIdToDelete = null;

    function showDeleteConfirmModal(postId) {
        postIdToDelete = postId;
        if (deleteModal) {
            deleteModal.classList.add('active');
        }
    }

    if (cancelDeleteBtn) {
        cancelDeleteBtn.addEventListener('click', () => {
            if (deleteModal) {
                deleteModal.classList.remove('active');
            }
            postIdToDelete = null;
        });
    }

    if (confirmDeleteBtn) {
        confirmDeleteBtn.addEventListener('click', async () => {
            if (postIdToDelete) {
                // TODO: Gọi API để xóa bài đăng
                try {
                     const response = await fetch(`${window.BASE_URL}api/index.php?action=delete_post&id=${postIdToDelete}`, {
                         method: 'DELETE'
                     });
                     const result = await response.json();
                     if (result.success) {
                         alert("Bài đăng đã được xóa thành công!");
                         // Cập nhật lại danh sách bài đăng sau khi xóa
                         renderUserPosts(allUserPosts.filter(p => p.id != postIdToDelete));
                     } else {
                         alert("Lỗi khi xóa bài đăng: " + result.message);
                     }

                } catch (error) {
                    console.error("Lỗi khi xóa bài đăng:", error);
                    alert("Đã xảy ra lỗi khi xóa bài đăng. Vui lòng thử lại.");
                } finally {
                    if (deleteModal) {
                        deleteModal.classList.remove('active');
                    }
                    postIdToDelete = null;
                }
            }
        });
    }

    // Fetching user data và posts từ backend API
    async function fetchUserProfile() {
      if(postsGrid) postsGrid.innerHTML = "<p>Đang tải dữ liệu trang cá nhân...</p>";
      try {
        const response = await fetch(window.BASE_URL + 'api/index.php?action=get_user_profile');
        const data = await response.json();
        
        if (data.success) {
            updateProfileUI(data.user);
            renderUserPosts(data.posts);
        } else {
            throw new Error(data.message || "Không thể tải dữ liệu.");
        }

      } catch (error) {
        console.error("Error fetching profile data:", error);
        if(postsGrid) postsGrid.innerHTML = `<p style="color: red;">Lỗi: ${error.message}</p>`;
      }
    }

    // Tải dữ liệu khi trang được load
    fetchUserProfile();
});