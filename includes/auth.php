<?php
// File: includes/auth.php
function loginUser($email, $password) {
    global $pdo;

    $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch();

    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['name'] = $user['name'];
        $_SESSION['email'] = $user['email'];
        $_SESSION['role'] = $user['role'];

        // Handle remember me
        if (isset($_POST['remember']) && $_POST['remember'] == 'on') {
            setcookie('remember_email', $email, time() + (30 * 24 * 60 * 60), '/'); // 30 days
        } else {
            setcookie('remember_email', '', time() - 3600, '/'); // Delete cookie
        }

        logActivity('login', 'User logged in successfully');
        return true;
    }
    return false;
}

function registerUser($name, $email, $password) {
    global $pdo;
    
    // Check if email exists
    $stmt = $pdo->prepare("SELECT id FROM users WHERE email = ?");
    $stmt->execute([$email]);
    
    if ($stmt->rowCount() > 0) {
        return "Email sudah terdaftar";
    }
    
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
    $stmt = $pdo->prepare("INSERT INTO users (name, email, password) VALUES (?, ?, ?)");
    
    if ($stmt->execute([$name, $email, $hashedPassword])) {
        logActivity('register', 'New user registered: ' . $email);
        return true;
    }
    return "Terjadi kesalahan saat registrasi";
}

function updateProfile($user_id, $name, $phone, $address) {
    global $pdo;
    
    $stmt = $pdo->prepare("UPDATE users SET name = ?, phone = ?, address = ? WHERE id = ?");
    if ($stmt->execute([$name, $phone, $address, $user_id])) {
        $_SESSION['name'] = $name;
        logActivity('profile_update', 'User profile updated');
        return true;
    }
    return false;
}
?>