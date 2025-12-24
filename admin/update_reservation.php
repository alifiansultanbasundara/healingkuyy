<?php
// File: admin/update_reservation.php
include '../includes/config.php';

if (!isLoggedIn() || !isAdmin()) {
    redirect('../login.php');
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    redirect('dashboard.php');
}

$reservation_id = sanitize($_POST['reservation_id']);
$action = sanitize($_POST['action']);

if (!$reservation_id || !$action) {
    $_SESSION['error'] = "Data tidak lengkap.";
    redirect('dashboard.php');
}

// Ambil data reservasi
$stmt = $pdo->prepare("SELECT * FROM reservations WHERE id = ?");
$stmt->execute([$reservation_id]);
$reservation = $stmt->fetch();

if (!$reservation) {
    $_SESSION['error'] = "Reservasi tidak ditemukan.";
    redirect('dashboard.php');
}

try {
    switch ($action) {
        case 'confirm':
            if ($reservation['status'] !== 'pending') {
                $_SESSION['error'] = "Reservasi tidak dapat dikonfirmasi.";
                redirect('reservation_detail.php?id=' . $reservation_id);
            }

            $stmt = $pdo->prepare("UPDATE reservations SET status = 'confirmed' WHERE id = ?");
            $stmt->execute([$reservation_id]);

            $_SESSION['success'] = "Reservasi berhasil dikonfirmasi.";
            break;

        case 'cancel':
            if ($reservation['status'] !== 'pending') {
                $_SESSION['error'] = "Reservasi tidak dapat dibatalkan.";
                redirect('reservation_detail.php?id=' . $reservation_id);
            }

            $stmt = $pdo->prepare("UPDATE reservations SET status = 'cancelled' WHERE id = ?");
            $stmt->execute([$reservation_id]);

            $_SESSION['success'] = "Reservasi berhasil dibatalkan.";
            break;

        case 'complete':
            if ($reservation['status'] !== 'confirmed') {
                $_SESSION['error'] = "Reservasi tidak dapat diselesaikan.";
                redirect('reservation_detail.php?id=' . $reservation_id);
            }

            $stmt = $pdo->prepare("UPDATE reservations SET status = 'completed' WHERE id = ?");
            $stmt->execute([$reservation_id]);

            $_SESSION['success'] = "Reservasi berhasil diselesaikan.";
            break;

        default:
            $_SESSION['error'] = "Aksi tidak valid.";
            redirect('reservation_detail.php?id=' . $reservation_id);
    }

    redirect('reservation_detail.php?id=' . $reservation_id);

} catch (Exception $e) {
    $_SESSION['error'] = "Terjadi kesalahan: " . $e->getMessage();
    redirect('reservation_detail.php?id=' . $reservation_id);
}
?>
