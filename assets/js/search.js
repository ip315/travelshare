document.addEventListener("DOMContentLoaded", function () {
  const searchInput = document.getElementById("searchInput");
  const categoryFilter = document.getElementById("categoryFilter");
  const searchResultsDiv = document.getElementById("searchResults");

  const mockSearchResults = {
    locations: [
      { id: 1, name: "Đà Lạt", description: "Thành phố ngàn hoa." },
      { id: 2, name: "Hội An", description: "Phố cổ di sản văn hóa." },
      { id: 3, name: "Phú Quốc", description: "Đảo ngọc với bãi biển đẹp." },
      { id: 4, name: "Sapa", description: "Thị trấn trong sương." },
    ],
    posts: [
      { id: 101, title: "Khám phá Đà Lạt", snippet: "Chuyến đi tuyệt vời tại Đà Lạt...", user: "nhuyyyy213" },
      { id: 102, title: "Ẩm thực Hội An", snippet: "Các món ăn phải thử khi đến Hội An...", user: "minhanh_travel" },
      { id: 103, title: "Lặn biển Phú Quốc", snippet: "Trải nghiệm lặn ngắm san hô...", user: "traveler_vn" },
    ],
    users: [
      { id: 1, username: "nhuyyyy213", avatar: window.IMAGES_URL + "image-75.png" },
      { id: 2, username: "minhanh_travel", avatar: window.IMAGES_URL + "avatar2.png" },
      { id: 3, username: "traveler_vn", avatar: window.IMAGES_URL + "avatar3.png" },
    ]
  };

  function performSearch() {
    const query = searchInput.value.toLowerCase();
    const category = categoryFilter ? categoryFilter.value : 'all';

    searchResultsDiv.innerHTML = '';

    let filteredLocations = [];
    let filteredPosts = [];
    let filteredUsers = [];

    if (query) {
      if (category === 'all' || category === 'locations') {
        filteredLocations = mockSearchResults.locations.filter(loc =>
          loc.name.toLowerCase().includes(query) || loc.description.toLowerCase().includes(query)
        );
      }
      if (category === 'all' || category === 'posts') {
        filteredPosts = mockSearchResults.posts.filter(post =>
          post.title.toLowerCase().includes(query) || post.snippet.toLowerCase().includes(query) || post.user.toLowerCase().includes(query)
        );
      }
      if (category === 'all' || category === 'users') {
        filteredUsers = mockSearchResults.users.filter(user =>
          user.username.toLowerCase().includes(query)
        );
      }
    } else {
      // Show all if no query
      if (category === 'all' || category === 'locations') filteredLocations = mockSearchResults.locations;
      if (category === 'all' || category === 'posts') filteredPosts = mockSearchResults.posts;
      if (category === 'all' || category === 'users') filteredUsers = mockSearchResults.users;
    }

    renderResults(filteredLocations, filteredPosts, filteredUsers);
  }

  function renderResults(locations, posts, users) {
    let hasResults = false;

    if (locations.length > 0) {
      hasResults = true;
      let locationsHtml = locations.map(loc => `<div class="result-item">${loc.name}</div>`).join('');
      searchResultsDiv.innerHTML += `
        <div class="result-category">
          <h3>Địa điểm</h3>
          <div class="result-items">${locationsHtml}</div>
        </div>
      `;
    }

    if (posts.length > 0) {
      hasResults = true;
      let postsHtml = posts.map(post => `
        <div class="result-item">
          <strong>${post.title}</strong>
          <p>${post.snippet}</p>
          <small>Người đăng: ${post.user}</small>
        </div>
      `).join('');
      searchResultsDiv.innerHTML += `
        <div class="result-category">
          <h3>Bài đăng</h3>
          <div class="result-items">${postsHtml}</div>
        </div>
      `;
    }

    if (users.length > 0) {
      hasResults = true;
      let usersHtml = users.map(user => `
        <div class="result-item user-item">
          <img src="${user.avatar || window.IMAGES_URL + 'placeholder-avatar.png'}" alt="User" class="user-avatar" />
          <span>${user.username}</span>
        </div>
      `).join('');
      searchResultsDiv.innerHTML += `
        <div class="result-category">
          <h3>Người dùng</h3>
          <div class="result-items">${usersHtml}</div>
        </div>
      `;
    }

    if (!hasResults) {
      searchResultsDiv.innerHTML = "<p>Không tìm thấy kết quả nào phù hợp.</p>";
    }
  }

  // Event listeners
  if (searchInput) {
    searchInput.addEventListener("input", performSearch);
  }
  if (categoryFilter) {
    categoryFilter.addEventListener("change", performSearch);
  }

  // Initial render
  performSearch();

  // TODO: Kết nối với API thực tế
  async function fetchSearchResults(query, category) {
    try {
      const response = await fetch(`${window.BASE_URL}api/search?q=${encodeURIComponent(query)}&category=${category}`);
      const data = await response.json();
      renderResults(data.locations, data.posts, data.users);
    } catch (error) {
      console.error("Error fetching search results:", error);
      searchResultsDiv.innerHTML = "<p>Đã xảy ra lỗi khi tìm kiếm. Vui lòng thử lại.</p>";
    }
  }

  // Gọi hàm này thay cho performSearch() nếu muốn dùng dữ liệu thật:
  // fetchSearchResults('đà lạt', 'all');
});
