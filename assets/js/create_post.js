document.addEventListener("DOMContentLoaded", function () {
    const postContent = document.getElementById("postContent");
    const imageUpload = document.getElementById("imageUpload");
    const videoUpload = document.getElementById("videoUpload");
    const gifBtn = document.getElementById("gifBtn");
    const postBtn = document.getElementById("postBtn");
    const imagePreview = document.getElementById("imagePreview");
    const gifMenu = document.getElementById("gifMenu");
    const locationInput = document.getElementById("locationInput");
    const locationSuggestions = document.getElementById("locationSuggestions");
    const selectedLocationDisplay = document.getElementById("selectedLocationDisplay");

    let selectedLocation = null;
    let uploadedMedia = []; // Lưu trữ các file blob hoặc URL

    // Dữ liệu GIF giả định (thay thế bằng API Giphy hoặc Tenor)
    const mockGifs = [
        "https://media.giphy.com/media/v1.giphy.com/media/Q8yVk3i7rB4u9Gq4x3/giphy.gif",
        "https://media.giphy.com/media/v1.giphy.com/media/eIGxU7uF4E6k/giphy.gif",
        "https://media.giphy.com/media/v1.giphy.com/media/l0MYt5jJ6gvX52JdK/giphy.gif",
        "https://media.giphy.com/media/v1.giphy.com/media/l3q2U0L1FhM4m0XgQ/giphy.gif",
        "https://media.giphy.com/media/v1.giphy.com/media/3o7TKEQ6nS6J8k8k/giphy.gif",
        "https://media.giphy.com/media/v1.giphy.com/media/l0MYt5jJ6gvX52JdK/giphy.gif",
        "https://media.giphy.com/media/v1.giphy.com/media/3o7TKEQ6nS6J8k8k/giphy.gif",
    ];

    // Hàm cập nhật trạng thái nút đăng bài
    function updatePostButton() {
        const content = postContent.value.trim();
        postBtn.disabled = !content && uploadedMedia.length === 0 && !selectedLocation;
    }

    // Hiển thị media đã chọn
    function addMediaToPreview(src, type) {
        const mediaContainer = document.createElement("div");
        mediaContainer.className = "fb-media-item";
        mediaContainer.innerHTML = `
            ${type === 'image' ? `<img src="${src}" alt="Media" style="object-fit:cover; width:100%; height:100%;">` : ''}
            ${type === 'video' ? `<video src="${src}" controls style="object-fit:cover; width:100%; height:100%;"></video>` : ''}
            ${type === 'gif' ? `<img src="${src}" alt="GIF" style="object-fit:cover; width:100%; height:100%;">` : ''}
            <button class="fb-remove-media">
                <svg width="20" height="20" fill="white" viewBox="0 0 24 24">
                    <path d="M19 6.41L17.59 5 12 10.59 6.41 5 5 6.41 10.59 12 5 17.59 6.41 19 12 13.41 17.59 19 19 17.59 13.41 12z"/>
                </svg>
            </button>
        `;
        imagePreview.appendChild(mediaContainer);

        const removeButton = mediaContainer.querySelector(".fb-remove-media");
        removeButton.addEventListener("click", () => {
            removeMedia(mediaContainer, src);
        });
        updatePostButton();
    }

    // Xóa media khỏi preview
    function removeMedia(mediaElement, srcToRemove) {
        mediaElement.remove();
        uploadedMedia = uploadedMedia.filter(item => item !== srcToRemove);
        updatePostButton();
    }

    // Xử lý upload ảnh
    if (imageUpload) {
        imageUpload.addEventListener("change", function (e) {
            Array.from(e.target.files).forEach(file => {
                const reader = new FileReader();
                reader.onload = function (ev) {
                    uploadedMedia.push(ev.target.result); // Lưu base64 hoặc blob URL
                    addMediaToPreview(ev.target.result, 'image');
                };
                reader.readAsDataURL(file);
            });
            e.target.value = ''; // Reset input file
        });
    }

    // Xử lý upload video
    if (videoUpload) {
        videoUpload.addEventListener("change", function (e) {
            Array.from(e.target.files).forEach(file => {
                const url = URL.createObjectURL(file);
                uploadedMedia.push(url);
                addMediaToPreview(url, 'video');
            });
            e.target.value = ''; // Reset input file
        });
    }

    // Xử lý GIF button
    if (gifBtn) {
        gifBtn.addEventListener("click", function () {
            gifMenu.style.display = gifMenu.style.display === "block" ? "none" : "flex";
            if (gifMenu.style.display === "flex" && gifMenu.children.length === 0) {
                // Render mock GIFs
                mockGifs.forEach(gifUrl => {
                    const img = document.createElement("img");
                    img.src = gifUrl;
                    img.alt = "GIF";
                    img.addEventListener("click", () => {
                        uploadedMedia.push(gifUrl);
                        addMediaToPreview(gifUrl, 'gif');
                        gifMenu.style.display = "none";
                    });
                    gifMenu.appendChild(img);
                });
            }
        });
    }

    // Đóng menu GIF khi click ngoài
    window.addEventListener("click", function (e) {
        if (gifMenu && gifBtn && !gifMenu.contains(e.target) && !gifBtn.contains(e.target)) {
            gifMenu.style.display = "none";
        }
    });

    // Xử lý nhập nội dung bài đăng
    if (postContent) {
        postContent.addEventListener("input", updatePostButton);
    }

    // Địa điểm gợi ý (mock data)
    const mockLocations = [
        "Hà Nội", "Hồ Chí Minh", "Đà Nẵng", "Huế", "Hội An",
        "Đà Lạt", "Nha Trang", "Phú Quốc", "Vũng Tàu", "Sapa",
        "Cần Thơ", "Hạ Long", "Mũi Né", "Phong Nha", "Côn Đảo"
    ];

    if (locationInput) {
        locationInput.addEventListener("input", function () {
            const query = locationInput.value.toLowerCase();
            locationSuggestions.innerHTML = "";
            if (query.length > 0) {
                const filteredLocations = mockLocations.filter(loc =>
                    loc.toLowerCase().includes(query)
                );
                filteredLocations.forEach(loc => {
                    const div = document.createElement("div");
                    div.textContent = loc;
                    div.addEventListener("click", () => {
                        selectedLocation = loc;
                        locationInput.value = ""; // Xóa input sau khi chọn
                        locationSuggestions.innerHTML = "";
                        displaySelectedLocation();
                        updatePostButton();
                    });
                    locationSuggestions.appendChild(div);
                });
                locationSuggestions.style.display = "block";
            } else {
                locationSuggestions.style.display = "none";
            }
        });

        document.addEventListener("click", function(e) {
            if (!locationInput.contains(e.target) && !locationSuggestions.contains(e.target)) {
                locationSuggestions.style.display = "none";
            }
        });
    }

    function displaySelectedLocation() {
        if (selectedLocation) {
            selectedLocationDisplay.innerHTML = `
                <span>Địa điểm: <strong>${selectedLocation}</strong></span>
                <button type="button" id="removeLocation">X</button>
            `;
            const removeBtn = document.getElementById("removeLocation");
            if (removeBtn) {
                removeBtn.addEventListener("click", () => {
                    selectedLocation = null;
                    selectedLocationDisplay.innerHTML = "";
                    updatePostButton();
                });
            }
            selectedLocationDisplay.style.display = "flex";
        } else {
            selectedLocationDisplay.style.display = "none";
        }
    }

    // Xử lý submit form
    if (postBtn) {
        postBtn.addEventListener("click", async function () {
            const content = postContent.value.trim();
            if (!content && uploadedMedia.length === 0 && !selectedLocation) {
                alert("Vui lòng nhập nội dung, chọn ảnh/video/GIF hoặc địa điểm để đăng bài.");
                return;
            }

            const formData = new FormData();
            formData.append("content", content);
            if (selectedLocation) {
                formData.append("location", selectedLocation);
            }
            const feeling = document.getElementById('feelingInput').value.trim();
            formData.append("feeling", feeling);
            const title = document.getElementById('postTitle').value.trim();
            formData.append("title", title);

            // Xử lý media (ví dụ: chuyển base64 sang Blob nếu cần gửi file thực sự)
            uploadedMedia.forEach((media, index) => {
                // Đây là phần phức tạp hơn, tùy thuộc vào cách backend xử lý.
                // Nếu backend nhận base64, gửi trực tiếp. Nếu cần file, phải chuyển đổi.
                // Ví dụ đơn giản: nếu là base64 image/gif, thêm vào formData dưới dạng chuỗi
                // Nếu là video URL (object URL), có thể cần fetch lại hoặc xử lý khác
                if (media.startsWith('data:')) { // Base64 image
                    formData.append(`media_${index}`, media);
                } else if (media.startsWith('blob:')) { // Video Object URL
                    // Đối với blob URL, bạn sẽ cần fetch blob đó và append vào formData như một file
                    // Đây là một ví dụ phức tạp hơn, tạm thời bỏ qua để giữ ví dụ đơn giản
                    console.warn("Video blob URLs cannot be directly appended to FormData for file upload without fetching the blob.");
                } else if (media.startsWith('http')) { // GIF URL
                    formData.append(`media_${index}`, media);
                }
            });

            const imageInput = document.getElementById('imageInput');
            if (imageInput && imageInput.files.length > 0) {
                formData.append('image', imageInput.files[0]);
            }

            formData.append('latitude', document.getElementById('latitudeInput').value);
            formData.append('longitude', document.getElementById('longitudeInput').value);

            console.log("Dữ liệu gửi đi:", Object.fromEntries(formData.entries()));
            alert("Đang gửi bài đăng... (Kiểm tra console để xem dữ liệu FormData)");

            // TODO: Gửi dữ liệu lên server bằng Fetch API
            try {
                 const response = await fetch(window.BASE_URL + 'public/api_posts.php', {
                     method: 'POST',
                     body: formData
                 });

                 const result = await response.json();
                 if (result.success) {
                     alert("Bài đăng đã được tạo thành công!");
                     window.location.href = window.BASE_URL + 'blog.php'; // Chuyển hướng về trang blog
                 } else {
                     alert("Lỗi khi tạo bài đăng: " + result.message);
                 }

                // Giả lập thành công
                setTimeout(() => {
                    alert("Bài đăng giả lập thành công!");
                    window.location.href = window.BASE_URL + 'blog.php';
                }, 1000);

            } catch (error) {
                console.error("Lỗi khi gửi bài đăng:", error);
                alert("Đã xảy ra lỗi khi tạo bài đăng. Vui lòng thử lại.");
            }
        });
    }

    // Khởi tạo trạng thái nút
    updatePostButton();
    displaySelectedLocation();
});