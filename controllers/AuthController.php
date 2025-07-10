<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once __DIR__ . '/../models/AuthModel.php';
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../config/constants.php';

class AuthController {
    private $authModel;

    public function __construct($mysqli) {
        $this->authModel = new AuthModel($mysqli);
    }

    public function handleRequest() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        $action = $_GET['action'] ?? '';

        switch ($action) {
            case 'login':
                $this->login();
                break;
            case 'register':
                $this->register();
                break;
            case 'logout':
                $this->logout();
                break;
            default:
                echo json_encode(['success' => false, 'message' => 'Hành động không hợp lệ.']);
                break;
        }
    }

    private function login() {
        header('Content-Type: application/json');
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = $_POST['email'] ?? '';
            $password = $_POST['password'] ?? '';

            if (empty($email) || empty($password)) {
                echo json_encode(['success' => false, 'message' => 'Vui lòng nhập đầy đủ email và mật khẩu.']);
                exit();
            }

            $user = $this->authModel->authenticateUser($email, $password);

            if ($user) {
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['username'] = $user['username'];
                echo json_encode([
                    'success' => true, 
                    'message' => 'Đăng nhập thành công!', 
                    'redirect' => BASE_URL . 'public/blog.php'
                ]);
            } else {
                echo json_encode(['success' => false, 'message' => 'Email hoặc mật khẩu không đúng.']);
            }
        } else {
            echo json_encode(['success' => false, 'message' => 'Phương thức yêu cầu không hợp lệ.']);
        }
        exit();
    }

    private function register() {
        header('Content-Type: application/json');
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $username = $_POST['username'] ?? '';
            $email = $_POST['email'] ?? '';
            $password = $_POST['password'] ?? '';
            $avatar = null;

            // Xử lý upload ảnh đại diện
            if (isset($_FILES['avatar']) && $_FILES['avatar']['error'] === UPLOAD_ERR_OK) {
                $uploadDir = __DIR__ . '/../assets/uploads/';
                if (!is_dir($uploadDir)) {
                    mkdir($uploadDir, 0777, true);
                }
                $fileTmp = $_FILES['avatar']['tmp_name'];
                $fileName = uniqid() . '_' . basename($_FILES['avatar']['name']);
                $filePath = $uploadDir . $fileName;
                if (move_uploaded_file($fileTmp, $filePath)) {
                    $avatar = $fileName;
                }
            }

            if (empty($username) || empty($email) || empty($password)) {
                echo json_encode(['success' => false, 'message' => 'Vui lòng điền đầy đủ thông tin.']);
                exit();
            }

            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                echo json_encode(['success' => false, 'message' => 'Email không hợp lệ.']);
                exit();
            }

            if (strlen($password) < 6) {
                echo json_encode(['success' => false, 'message' => 'Mật khẩu phải có ít nhất 6 ký tự.']);
                exit();
            }

            $result = $this->authModel->registerUser($username, $email, $password, $avatar);

            if ($result) {
                echo json_encode(['success' => true, 'message' => 'Đăng ký thành công! Vui lòng đăng nhập.']);
            } else {
                echo json_encode(['success' => false, 'message' => 'Email đã tồn tại hoặc lỗi hệ thống.']);
            }
        } else {
            echo json_encode(['success' => false, 'message' => 'Phương thức yêu cầu không hợp lệ.']);
        }
        exit();
    }

    private function logout() {
        header('Content-Type: application/json');
        session_unset();
        session_destroy();
        echo json_encode(['success' => true, 'message' => 'Đăng xuất thành công.']);
        exit();
    }
}

// Chạy controller nếu có action
if (isset($_GET['action'])) {
    if (!isset($mysqli) || $mysqli->connect_error) {
        echo json_encode(['success' => false, 'message' => 'Lỗi kết nối cơ sở dữ liệu.']);
        exit();
    }
    $controller = new AuthController($mysqli);
    $controller->handleRequest();
}
?>