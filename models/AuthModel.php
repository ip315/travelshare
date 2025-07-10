<?php
class AuthModel {
    private $mysqli;

    public function __construct($mysqli) {
        $this->mysqli = $mysqli;
    }
    public function authenticateUser($email, $password) {
        $stmt = $this->mysqli->prepare("SELECT id, username, password FROM users WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $id=null;
        $username=null; 
        $storedPassword=null;
        $stmt->store_result();
        $stmt->bind_result($id, $username, $storedPassword);
        
        if ($stmt->fetch()) {
            $stmt->close();
            
            // So sánh mật khẩu đơn giản (KHÔNG mã hóa)
            if ($password === $storedPassword) {
                return ['id' => $id, 'username' => $username];
            } else {
                return false;
            }
        }

        $stmt->close();
        return false;
    }
    // Đăng ký người dùng mới
    public function registerUser($username, $email, $password, $avatar = null) {
        // Kiểm tra xem email đã tồn tại chưa
        $stmt = $this->mysqli->prepare("SELECT id FROM users WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            return false; // Email đã tồn tại
        }

        $stmt->close();

        // Không hash mật khẩu (theo yêu cầu của bạn), nhưng KHÔNG khuyến nghị
        $stmt = $this->mysqli->prepare("INSERT INTO users (username, email, password, avatar) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssss", $username, $email, $password, $avatar);

        $result = $stmt->execute();
        $stmt->close();

        return $result;
    }

    // Xác thực đăng nhập
    
}
?>
