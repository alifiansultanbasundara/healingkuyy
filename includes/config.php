<?php
// File: includes/config.php - UPDATE DENGAN TABLE BARU
session_start();

define('DB_HOST', 'localhost');
define('DB_NAME', 'healingkuy');
define('DB_USER', 'root');
define('DB_PASS', '');

try {
    $pdo = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME, DB_USER, DB_PASS);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    die("Koneksi database gagal: " . $e->getMessage());
}

function sanitize($data) {
    return htmlspecialchars(strip_tags(trim($data)));
}

function isLoggedIn() {
    return isset($_SESSION['user_id']);
}

function isAdmin() {
    return isset($_SESSION['role']) && $_SESSION['role'] === 'admin';
}

function redirect($url) {
    header("Location: $url");
    exit;
}

function generateBookingCode() {
    return 'HK' . date('Ymd') . strtoupper(substr(uniqid(), -6));
}

function logActivity($action, $description = '') {
    global $pdo;
    if (isset($_SESSION['user_id'])) {
        $stmt = $pdo->prepare("INSERT INTO activity_logs (user_id, action, description, ip_address, user_agent) VALUES (?, ?, ?, ?, ?)");
        $stmt->execute([$_SESSION['user_id'], $action, $description, $_SERVER['REMOTE_ADDR'], $_SERVER['HTTP_USER_AGENT']]);
    }
}

// Check if user is logged in for protected pages
function checkAuth() {
    if (!isLoggedIn()) {
        $_SESSION['error'] = "Silakan login terlebih dahulu";
        redirect('login.php');
    }
}

// Check if user is admin for admin pages
function checkAdmin() {
    checkAuth();
    if (!isAdmin()) {
        $_SESSION['error'] = "Akses ditolak. Halaman untuk admin saja.";
        redirect('../index.php');
    }
}

// Get user info
function getUserInfo($user_id) {
    global $pdo;
    $stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
    $stmt->execute([$user_id]);
    return $stmt->fetch();
}

// Format currency helper
function formatCurrency($amount) {
    return 'Rp ' . number_format($amount, 0, ',', '.');
}

// Get service info
function getServiceInfo($service_id) {
    global $pdo;
    $stmt = $pdo->prepare("SELECT * FROM services WHERE id = ?");
    $stmt->execute([$service_id]);
    return $stmt->fetch();
}
?>