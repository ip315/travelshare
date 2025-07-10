<?php
// Trang chủ TravelShare - giao diện sát với HTML/CSS mẫu
?>
<link rel="stylesheet" href="<?php echo CSS_URL; ?>layout.css">

<!-- Start Landing -->
<div class="landing" id="Home">
    <div class="dotss dotss-up">
        <img src="<?php echo IMAGES_URL; ?>dotss.png.jpg" alt="">
    </div>
    <div class="container">
        <div class="info">
            <div class="text">
                <h2>Chia sẻ trải nghiệm du lịch của bạn</h2>
                <p>
                    Cùng chúng tôi khám phá những điểm đến tuyệt vời và chia sẻ câu chuyện du lịch đáng nhớ của bạn với cộng đồng.
                </p>
                <div>
                    <a href="#" class="btn">Khám phá ngay</a>
                    <a href="#" class="btn2">Xem thêm</a>
                </div>
            </div>
        </div>
        <img src="<?php echo IMAGES_URL; ?>couch1.png" alt="Du khách đang thư giãn">
    </div>
</div>
<!-- End Landing -->

<!-- Start Product Section -->
<div class="product-section" id="product-section">
    <div class="container">
        <div class="info">
            <div class="text">
                <h2>Điểm đến nổi bật.</h2>
                <p>
                    Khám phá những địa điểm du lịch được yêu thích nhất từ cộng đồng của chúng tôi.
                </p>
                <a href="#" class="btn2">Xem tất cả</a>
            </div>
        </div>
        <a href="#" class="box">
            <div class="images">
                <img src="<?php echo IMAGES_URL; ?>product-1.png" alt="Đà Lạt">
            </div>
            <div class="description">
                <p>Đà Lạt</p>
                <p class="price">4.8★</p>
                <i class="fa-solid fa-plus"></i>
            </div>
        </a>
        <a href="#" class="box">
            <div class="images">
                <img src="<?php echo IMAGES_URL; ?>product-2.png" alt="Hạ Long">
            </div>
            <div class="description">
                <p>Vịnh Hạ Long</p>
                <p class="price">4.9★</p>
                <i class="fa-solid fa-plus"></i>
            </div>
        </a>
        <a href="#" class="box">
            <div class="images">
                <img src="<?php echo IMAGES_URL; ?>product-3.png" alt="Phú Quốc">
            </div>
            <div class="description">
                <p>Phú Quốc</p>
                <p class="price">4.7★</p>
            </div>
            <i class="fa-solid fa-plus"></i>
        </a>
    </div>
</div>
<!-- End Product Section -->

<!-- Start Why Us -->
<div class="why-us" id="why-us">
    <div class="container">
        <div class="info">
            <div class="text">
                <h2>Tại sao chọn chúng tôi</h2>
                <p>
                    TravelShare mang đến cho bạn nền tảng chia sẻ trải nghiệm du lịch chân thực và hữu ích nhất.
                </p>
                <div class="boxs">
                    <div class="box">
                        <i class="fa-solid fa-map"></i>
                        <h3>Bản đồ chi tiết</h3>
                        <p>Cung cấp bản đồ và chỉ dẫn chi tiết đến các điểm du lịch hấp dẫn.</p>
                    </div>
                    <div class="box">
                        <i class="fa-solid fa-users"></i>
                        <h3>Cộng đồng tin cậy</h3>
                        <p>Kết nối với cộng đồng du lịch uy tín và chia sẻ kinh nghiệm.</p>
                    </div>
                    <div class="box">
                        <i class="fa-solid fa-camera"></i>
                        <h3>Kho ảnh phong phú</h3>
                        <p>Hàng ngàn hình ảnh chân thực từ các thành viên khắp nơi.</p>
                    </div>
                    <div class="box">
                        <i class="fa-solid fa-comments"></i>
                        <h3>Đánh giá chân thực</h3>
                        <p>Nhận xét và đánh giá từ những người đã từng trải nghiệm.</p>
                    </div>
                </div>
            </div>
            <div class="images">
                <img src="<?php echo IMAGES_URL; ?>why-choose-us-img.jpg" alt="Nhóm du khách" class="main-img">
                <img src="<?php echo IMAGES_URL; ?>dots-yellwo.png.jpg" alt="" class="sec-img">
            </div>
        </div>
    </div>
</div>
<!-- End Why Us -->

<!-- Start Help-Section -->
<div class="help-section" id="help-section">
    <div class="container">
        <div class="images">
            <img src="<?php echo IMAGES_URL; ?>grid.png.jpg" alt="Kế hoạch du lịch" class="img1">
        </div>
        <div class="text">
            <h2>Chúng tôi giúp bạn lên kế hoạch du lịch hoàn hảo</h2>
            <p>
                Với kinh nghiệm và cộng đồng đông đảo, chúng tôi sẽ giúp bạn có những chuyến đi đáng nhớ với chi phí hợp lý nhất. Từ khâu chuẩn bị đến khi kết thúc chuyến đi, mọi thứ đều trở nên dễ dàng.
            </p>
            <div class="boxs">
                <div class="box"><p>Lên lịch trình chi tiết theo ngân sách</p></div>
                <div class="box"><p>Gợi ý địa điểm ăn uống, nghỉ ngơi</p></div>
                <div class="box"><p>Đặt vé và dịch vụ với giá ưu đãi</p></div>
                <div class="box"><p>Hỗ trợ 24/7 trong suốt chuyến đi</p></div>
            </div>
            <a href="#" class="btn2">Bắt đầu ngay</a>
        </div>
    </div>
</div>
<!-- End Help-Section -->

<!-- Start Popular Section -->
<div class="popular-section">
    <div class="container">
        <div class="box">
            <div class="image">
                <img src="<?php echo IMAGES_URL; ?>product-1.png" alt="Hành trình miền Bắc">
            </div>
            <div class="text">
                <h3>Hành trình miền Bắc</h3>
                <p>Khám phá vẻ đẹp văn hóa và thiên nhiên vùng núi phía Bắc</p>
                <a href="#">Xem chi tiết</a>
            </div>
        </div>
        <div class="box">
            <div class="image">
                <img src="<?php echo IMAGES_URL; ?>product-2.png" alt="Trung tâm thành phố">
            </div>
            <div class="text">
                <h3>Trung tâm thành phố</h3>
                <p>Trải nghiệm cuộc sống sôi động tại các đô thị lớn</p>
                <a href="#">Xem chi tiết</a>
            </div>
        </div>
        <div class="box">
            <div class="image">
                <img src="<?php echo IMAGES_URL; ?>product-3.png" alt="Miền Tây sông nước">
            </div>
            <div class="text">
                <h3>Miền Tây sông nước</h3>
                <p>Đắm chìm trong khung cảnh thanh bình của vùng sông nước</p>
                <a href="#">Xem chi tiết</a>
            </div>
        </div>
    </div>
</div>
<!-- End Popular Section -->

<!-- Start Testimonials -->
<div class="testimonials">
    <ul class="slid">
        <li></li>
        <li class="active"></li>
        <li></li>
    </ul>
    <div class="container">
        <div class="text">
            <h2>Cảm nhận thành viên</h2>
            <p>
                "Tôi đã có trải nghiệm tuyệt vời khi sử dụng TravelShare cho chuyến đi Đà Lạt vừa qua. Nhờ những chia sẻ từ cộng đồng, tôi đã khám phá được nhiều điểm đến ẩn mình mà không sách hướng dẫn nào nhắc đến. Đặc biệt là các quán cà phê nhỏ xinh với view đẹp ngất ngây."
            </p>
            <img src="<?php echo IMAGES_URL; ?>person-1.png" alt="Nguyễn Thị Hương">
            <p class="name">Nguyễn Thị Hương</p>
            <p class="title">Thành viên từ 2022</p>
        </div>
        <i class="fa-solid fa-angle-left left"></i>
        <i class="fa-solid fa-angle-right right"></i>
    </div>
</div>
<!-- End Testimonials -->

<!-- Start Blog-Section -->
<div class="bolg-section" id="Blog">
    <div class="container">
        <div class="text">
            <h2>Bài viết mới nhất</h2>
            <a href="#">Xem tất cả</a>
        </div>
        <div class="boxs">
        <?php
        require_once dirname(__DIR__, 2) . '/config/db.php';
        // Lấy danh sách bài đăng
        $result = $mysqli->query("SELECT p.*, u.username, u.avatar FROM posts p JOIN users u ON p.user_id = u.id ORDER BY p.created_at DESC");
        while ($post = $result->fetch_assoc()):
        ?>
            <div class="box" id="post-<?= $post['id'] ?>">
                <?php if ($post['image']): ?>
                    <a href="#"><img src="<?= $post['image'] ?>" alt="<?= htmlspecialchars($post['title']) ?>" style="max-width:100%;height:auto;"></a>
                <?php endif; ?>
                <a href="#"><?= htmlspecialchars($post['title']) ?></a>
                <p>
                    bởi <a href="#"><?= htmlspecialchars($post['username']) ?></a> vào <a href="#"><?= $post['created_at'] ?></a>
                </p>
                <div><?= htmlspecialchars($post['location']) ?></div>
                <div><?= nl2br(htmlspecialchars($post['content'])) ?></div>
            </div>
        <?php endwhile; ?>
        </div>
    </div>
</div>
<!-- End Blog-Section -->
<script>
document.addEventListener('DOMContentLoaded', function() {
  const params = new URLSearchParams(window.location.search);
  const postId = params.get('id');
  if (postId) {
    const postElem = document.getElementById('post-' + postId);
    if (postElem) {
      postElem.scrollIntoView({ behavior: 'smooth', block: 'center' });
      postElem.style.background = '#ffffcc';
      setTimeout(() => postElem.style.background = '', 2000);
    }
  }
});
</script>

<!-- Start Subscribe-Section -->
<div class="subscribe-section" id="subscribe-section">
    <div class="container">
        <div class="sms">
            <i class="fa-solid fa-envelope"></i>
            <p>Đăng ký nhận bản tin</p>
        </div>
        <div class="contact">
            <div class="text">
                <input class="input" type="text" placeholder="Họ và tên" name="name"/>
                <input class="input" type="email" placeholder="Email của bạn" name="email"/>
                <input class="input" type="text" placeholder="Số điện thoại" name="phone"/>
            </div>
            <div class="btn">
                <a href="#">Đăng ký ngay</a>
            </div>
        </div>
    </div>
</div>
<!-- End Subscribe-Section -->
