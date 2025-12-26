<?php
// File: admin/update_reservation.php
include '../includes/config.php';

if (!isLoggedIn() || !isAdmin()) {
    redirect('../login.php');
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    redirect('dashboard.php');
}

// Ambil input
$reservation_id = isset($_POST['reservation_id']) ? (int) sanitize($_POST['reservation_id']) : 0;
$action         = isset($_POST['action']) ? strtolower(trim(sanitize($_POST['action']))) : '';

if ($reservation_id <= 0 || $action === '') {
    $_SESSION['error'] = "Data tidak lengkap.";
    redirect('dashboard.php');
}

// Ambil data reservasi (cukup field yang dibutuhkan)
$stmt = $pdo->prepare("SELECT id, status, total_price, service_total, hotel_total FROM reservations WHERE id = ?");
$stmt->execute([$reservation_id]);
$reservation = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$reservation) {
    $_SESSION['error'] = "Reservasi tidak ditemukan.";
    redirect('dashboard.php');
}

// Hitung total aman:
// - Jika total_price NULL/0 -> fallback (service_total + hotel_total)
// - Jika total_price sudah valid -> pakai total_price
$service_total = (float)($reservation['service_total'] ?? 0);
$hotel_total   = (float)($reservation['hotel_total'] ?? 0);
$total_price   = (float)($reservation['total_price'] ?? 0);

$final_total = ($total_price > 0) ? $total_price : ($service_total + $hotel_total);

try {
    $pdo->beginTransaction();

    switch ($action) {
        case 'confirm':
            if ($reservation['status'] !== 'pending') {
                $pdo->rollBack();
                $_SESSION['error'] = "Reservasi tidak dapat dikonfirmasi.";
                redirect('reservation_detail.php?id=' . $reservation_id);
            }

            // Konfirmasi + pastikan total_price terisi
            $stmt = $pdo->prepare("
                UPDATE reservations
                SET status = 'confirmed',
                    total_price = ?
                WHERE id = ?
            ");
            $stmt->execute([$final_total, $reservation_id]);

            $_SESSION['success'] = "Reservasi berhasil dikonfirmasi.";
            break;

        case 'cancel':
            if ($reservation['status'] !== 'pending') {
                $pdo->rollBack();
                $_SESSION['error'] = "Reservasi tidak dapat dibatalkan.";
                redirect('reservation_detail.php?id=' . $reservation_id);
            }

            $stmt = $pdo->prepare("
                UPDATE reservations
                SET status = 'cancelled'
                WHERE id = ?
            ");
            $stmt->execute([$reservation_id]);

            $_SESSION['success'] = "Reservasi berhasil dibatalkan.";
            break;

        case 'complete':
            if ($reservation['status'] !== 'confirmed') {
                $pdo->rollBack();
                $_SESSION['error'] = "Reservasi tidak dapat diselesaikan.";
                redirect('reservation_detail.php?id=' . $reservation_id);
            }

            // Selesai + pastikan total_price tetap valid
            $stmt = $pdo->prepare("
                UPDATE reservations
                SET status = 'completed',
                    total_price = ?
                WHERE id = ?
            ");
            $stmt->execute([$final_total, $reservation_id]);

            $_SESSION['success'] = "Reservasi berhasil diselesaikan.";
            break;

        default:
            $pdo->rollBack();
            $_SESSION['error'] = "Aksi tidak valid.";
            redirect('reservation_detail.php?id=' . $reservation_id);
    }

    $pdo->commit();
    redirect('reservation_detail.php?id=' . $reservation_id);

} catch (Exception $e) {
    if ($pdo->inTransaction()) $pdo->rollBack();
    $_SESSION['error'] = "Terjadi kesalahan: " . $e->getMessage();
    redirect('reservation_detail.php?id=' . $reservation_id);
}
?>

