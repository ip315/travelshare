document.addEventListener("DOMContentLoaded", function () {
    const editPostForm = document.getElementById("editPostForm");
    const editTitle = document.getElementById("edit-title");
    const editContent = document.getElementById("edit-content");
    const editLocationInput = document.getElementById("editLocationInput");
    const editLocationSuggestions = document.getElementById("editLocationSuggestions");
    const selectedEditLocationDisplay = document.getElementById("selectedEditLocationDisplay");
    const imageUploadEdit = document.getElementById("imageUploadEdit");
    const imagePreviewEdit = document.getElementById("imagePreviewEdit");
    const currentImagesContainer = document.getElementById("currentImagesContainer");

    let currentPostId = null; // Sẽ được lấy từ URL hoặc truyền vào
    let selectedEditLocation = null;
    let newMediaFiles = []; // Các file ảnh/video mới được upload
    let existingMedia = []; // Các URL của ảnh/video hiện có

    // Mock data bài đăng để chỉnh sửa
    const mockPostToEdit = {
        id: 1,
        title: "Chuyến đi Đà Lạt mộng mơ",
        content: "Khám phá thành phố ngàn hoa cùng bạn bè, với những trải nghiệm tuyệt vời và không gian lãng mạn.",
        location: "Đà Lạt",
        media: [
            "<?php echo IMAGES_URL; ?>dalat_edit_1.jpg",
            "<?php echo IMAGES_URL; ?>dalat_edit_2.jpg"
        ]
    };

    // Địa điểm gợi ý (mock data)
    const mockLocations = [
        "Hà Nội", "Hồ Chí Minh", "Đà Nẵng", "Huế", "Hội An",
        "Đà Lạt", "Nha Trang", "Phú Quốc", "Vũng Tàu", "Sapa",
        "Cần Thơ", "Hạ Long", "Mũi Né", "Phong Nha", "Côn Đảo"
    ];

    // Hàm tải dữ liệu bài đăng vào form
    function loadPostData(post) {
        currentPostId = post.id;
        editTitle.value = post.title;
        editContent.value = post.content;

        selectedEditLocation = post.location;
        displaySelectedEditLocation();

        existingMedia = post.media || [];
        renderExistingMedia();
    }

    // Render ảnh/video hiện có
    function renderExistingMedia() {
        currentImagesContainer.innerHTML = "";
        existingMedia.forEach(src => {
            const mediaElement = document.createElement("div");
            mediaElement.className = "fb-media-item"; // Reuse fb-media-item style
            // Determine if it's an image or video based on extension/type
            const isVideo = src.match(/\.(mp4|webm|ogg)$/i);
            mediaElement.innerHTML = `
                ${isVideo ? `<video src="${src}" controls style="object-fit:cover; width:100%; height:100%;"></video>` : `<img src="${src}" alt="Media" style="object-fit:cover; width:100%; height:100%;">`}
                <button class="fb-remove-media" data-src="${src}">
                    <svg width="20" height="20" fill="white" viewBox="0 0 24 24">
                        <path d="M19 6.41L17.59 5 12 10.59 6.41 5 5 6.41 10.59 12 5 17.59 6.41 19 12 13.41 17.59 19 19 17.59 13.41 12z"/>
                    </svg>
                </button>
            `;
            currentImagesContainer.appendChild(mediaElement);

            mediaElement.querySelector(".fb-remove-media").addEventListener("click", (e) => {
                const srcToRemove = e.currentTarget.dataset.src;
                existingMedia = existingMedia.filter(item => item !== srcToRemove);
                mediaElement.remove();
            });
        });
    }

    // Hiển thị media mới được upload
    function addNewMediaToPreview(src, type) {
        const mediaContainer = document.createElement("div");
        mediaContainer.className = "fb-media-item";
        mediaContainer.innerHTML = `
            ${type === 'image' ? `<img src="${src}" alt="Media" style="object-fit:cover; width:100%; height:100%;">` : ''}
            ${type === 'video' ? `<video src="${src}" controls style="object-fit:cover; width:100%; height:100%;"></video>` : ''}
            <button class="fb-remove-media">
                <svg width="20" height="20" fill="white" viewBox="0 0 24 24">
                    <path d="M19 6.41L17.59 5 12 10.59 6.41 5 5 6.41 10.59 12 5 17.59 6.41 19 12 13.41 17.59 19 19 17.59 13.41 12z"/>
                </svg>
            </button>
        `;
        imagePreviewEdit.appendChild(mediaContainer);

        const removeButton = mediaContainer.querySelector(".fb-remove-media");
        removeButton.addEventListener("click", () => {
            removeNewMedia(mediaContainer, src);
        });
    }

    // Xóa media mới khỏi preview
    function removeNewMedia(mediaElement, srcToRemove) {
        mediaElement.remove();
        newMediaFiles = newMediaFiles.filter(item => {
            if (typeof item === 'string' && item.startsWith('blob:')) {
                return item !== srcToRemove; // So sánh URL
            }
            // Nếu là File object, cần cơ chế so sánh khác nếu cần
            return true;
        });
        URL.revokeObjectURL(srcToRemove); // Giải phóng Object URL
    }

    // Xử lý upload ảnh/video mới
    if (imageUploadEdit) {
        imageUploadEdit.addEventListener("change", function (e) {
            Array.from(e.target.files).forEach(file => {
                const url = URL.createObjectURL(file);
                newMediaFiles.push(file); // Lưu trữ File object
                const type = file.type.startsWith('image') ? 'image' : 'video';
                addNewMediaToPreview(url, type);
            });
            e.target.value = ''; // Reset input file
        });
    }

    // Xử lý địa điểm
    if (editLocationInput) {
        editLocationInput.addEventListener("input", function () {
            const query = editLocationInput.value.toLowerCase();
            editLocationSuggestions.innerHTML = "";
            if (query.length > 0) {
                const filteredLocations = mockLocations.filter(loc =>
                    loc.toLowerCase().includes(query)
                );
                filteredLocations.forEach(loc => {
                    const div = document.createElement("div");
                    div.textContent = loc;
                    div.addEventListener("click", () => {
                        selectedEditLocation = loc;
                        editLocationInput.value = "";
                        editLocationSuggestions.innerHTML = "";
                        displaySelectedEditLocation();
                    });
                    editLocationSuggestions.appendChild(div);
                });
                editLocationSuggestions.style.display = "block";
            } else {
                editLocationSuggestions.style.display = "none";
            }
        });

        document.addEventListener("click", function(e) {
            if (!editLocationInput.contains(e.target) && !editLocationSuggestions.contains(e.target)) {
                editLocationSuggestions.style.display = "none";
            }
        });
    }

    function displaySelectedEditLocation() {
        if (selectedEditLocation) {
            selectedEditLocationDisplay.innerHTML = `
                <span>Địa điểm: <strong>${selectedEditLocation}</strong></span>
                <button type="button" id="removeEditLocation">X</button>
            `;
            const removeBtn = document.getElementById("removeEditLocation");
            if (removeBtn) {
                removeBtn.addEventListener("click", () => {
                    selectedEditLocation = null;
                    selectedEditLocationDisplay.innerHTML = "";
                });
            }
            selectedEditLocationDisplay.style.display = "flex";
        } else {
            selectedEditLocationDisplay.style.display = "none";
        }
    }

    // Xử lý submit form
    if (editPostForm) {
        editPostForm.addEventListener("submit", async function (e) {
            e.preventDefault();

            const formData = new FormData();
            formData.append("id", currentPostId);
            formData.append("title", editTitle.value);
            formData.append("content", editContent.value);
            if (selectedEditLocation) {
                formData.append("location", selectedEditLocation);
            }
            formData.append("existing_media", JSON.stringify(existingMedia)); // Gửi các ảnh hiện có dưới dạng JSON string

            newMediaFiles.forEach((file, index) => {
                formData.append(`new_media_${index}`, file); // Gửi các file mới
            });

            console.log("Dữ liệu gửi đi để chỉnh sửa:", Object.fromEntries(formData.entries()));
            alert("Đang gửi dữ liệu chỉnh sửa... (Kiểm tra console)");

            // TODO: Gửi dữ liệu lên server bằng Fetch API (cần backend hỗ trợ upload file và JSON)
            try {
                 const response = await fetch(`<?php echo BASE_URL; ?>api/posts/${currentPostId}`, {
                     method: 'POST', // Hoặc PUT/PATCH tùy API
                     body: formData
                 });

                 const result = await response.json();
                 if (result.success) {
                     alert("Bài đăng đã được cập nhật thành công!");
                     window.location.href = '<?php echo BASE_URL; ?>profile.php'; // Chuyển hướng về trang profile
                 } else {
                     alert("Lỗi khi cập nhật bài đăng: " + result.message);
                 }

                // Giả lập thành công
                setTimeout(() => {
                    alert("Bài đăng giả lập đã được cập nhật thành công!");
                    window.location.href = '<?php echo BASE_URL; ?>profile.php';
                }, 1000);

            } catch (error) {
                console.error("Lỗi khi gửi dữ liệu chỉnh sửa:", error);
                alert("Đã xảy ra lỗi khi cập nhật bài đăng. Vui lòng thử lại.");
            }
        });
    }

    // Tải dữ liệu khi trang được load (giả lập ID bài đăng 1)
    loadPostData(mockPostToEdit);
});