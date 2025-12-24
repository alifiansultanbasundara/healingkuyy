<?php
// File: user/cancellation.php
include '../includes/config.php';
include '../includes/auth.php';

if (!isLoggedIn() || isAdmin()) {
    redirect('../login.php');
}

$error = '';
$success = '';
$reservation = null;

// Jika ada reservation_id di URL, ambil data reservasi
if (isset($_GET['reservation_id'])) {
    $reservation_id = sanitize($_GET['reservation_id']);
    
    $stmt = $pdo->prepare("
        SELECT r.*, s.name as service_name, s.location, s.price as service_price
        FROM reservations r 
        JOIN services s ON r.service_id = s.id 
        WHERE r.id = ? AND r.user_id = ?
    ");
    $stmt->execute([$reservation_id, $_SESSION['user_id']]);
    $reservation = $stmt->fetch();

    if (!$reservation) {
        $_SESSION['error'] = "Reservasi tidak ditemukan.";
        redirect('dashboard.php');
    }

    // Cek apakah sudah ada pengajuan pembatalan
    $stmt = $pdo->prepare("SELECT * FROM cancellations WHERE reservation_id = ?");
    $stmt->execute([$reservation_id]);
    $existing_cancellation = $stmt->fetch();

    if ($existing_cancellation) {
        $_SESSION['error'] = "Sudah ada pengajuan pembatalan untuk reservasi ini.";
        redirect('dashboard.php');
    }

    // Cek apakah reservasi bisa dibatalkan
    if ($reservation['status'] == 'cancelled' || $reservation['status'] == 'completed') {
        $_SESSION['error'] = "Reservasi ini tidak dapat dibatalkan.";
        redirect('dashboard.php');
    }
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $reservation_id = sanitize($_POST['reservation_id']);
    $reason = sanitize($_POST['reason']);

    // Validasi
    if (empty($reason)) {
        $error = "Alasan pembatalan wajib diisi.";
    } else {
        // Buat pengajuan pembatalan
        $stmt = $pdo->prepare("
            INSERT INTO cancellations (reservation_id, reason) 
            VALUES (?, ?)
        ");

        if ($stmt->execute([$reservation_id, $reason])) {
            logActivity('cancellation_request', 'Cancellation requested for reservation: ' . $reservation_id);
            $success = "Pengajuan pembatalan berhasil dikirim. Silakan tunggu konfirmasi dari admin.";
            $reservation = null; // Reset form
        } else {
            $error = "Terjadi kesalahan saat mengajukan pembatalan.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pengajuan Pembatalan - HealingKuy!.id</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        .cancellation-card {
            border: none;
            border-radius: 15px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        }
        .refund-info {
            background: linear-gradient(135deg, #fff3cd 0%, #ffeaa7 100%);
            border-left: 4px solid #ffc107;
        }
    </style>
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container">
            <a class="navbar-brand fw-bold" href="../index.php">
                <i class="fas fa-mountain"></i> HealingKuy!.id
            </a>
            <div class="navbar-nav ms-auto">
                <span class="navbar-text me-3">
                    <i class="fas fa-user me-1"></i> <?php echo $_SESSION['name']; ?>
                </span>
                <a class="nav-link" href="../index.php"><i class="fas fa-home me-1"></i> Home</a>
                <a class="nav-link" href="dashboard.php"><i class="fas fa-tachometer-alt me-1"></i> Dashboard</a>
                <a class="nav-link" href="../logout.php"><i class="fas fa-sign-out-alt me-1"></i> Logout</a>
            </div>
        </div>
    </nav>

    <div class="container mt-4">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card cancellation-card">
                    <div class="card-header bg-warning text-dark">
                        <h4 class="mb-0"><i class="fas fa-times-circle me-2"></i>Pengajuan Pembatalan</h4>
                    </div>
                    <div class="card-body p-4">
                        <?php if($error): ?>
                        <div class="alert alert-danger"><?php echo $error; ?></div>
                        <?php endif; ?>
                        
                        <?php if($success): ?>
                        <div class="alert alert-success"><?php echo $success; ?></div>
                        <?php endif; ?>

                        <?php if ($reservation): ?>
                        <!-- Detail Reservasi -->
                        <div class="card mb-4 border-warning">
                            <div class="card-header bg-light">
                                <h5 class="mb-0">Detail Reservasi yang Akan Dibatalkan</h5>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <table class="table table-sm table-borderless">
                                            <tr>
                                                <th width="40%">Kode Booking</th>
                                                <td class="fw-bold text-primary"><?php echo $reservation['booking_code']; ?></td>
                                            </tr>
                                            <tr>
                                                <th>Layanan</th>
                                                <td><?php echo $reservation['service_name']; ?></td>
                                            </tr>
                                            <tr>
                                                <th>Lokasi</th>
                                                <td><?php echo $reservation['location']; ?></td>
                                            </tr>
                                        </table>
                                    </div>
                                    <div class="col-md-6">
                                        <table class="table table-sm table-borderless">
                                            <tr>
                                                <th width="40%">Tanggal</th>
                                                <td><?php echo date('d F Y', strtotime($reservation['reservation_date'])); ?></td>
                                            </tr>
                                            <tr>
                                                <th>Jumlah Tamu</th>
                                                <td><?php echo $reservation['guests']; ?> orang</td>
                                            </tr>
                                            <tr>
                                                <th>Total Biaya</th>
                                                <td class="fw-bold">Rp <?php echo number_format($reservation['total_price'], 0, ',', '.'); ?></td>
                                            </tr>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <form method="POST">
                            <input type="hidden" name="reservation_id" value="<?php echo $reservation['id']; ?>">
                            
                            <!-- Alasan Pembatalan -->
                            <div class="mb-4">
                                <label for="reason" class="form-label fw-bold">Alasan Pembatalan</label>
                                <textarea class="form-control" id="reason" name="reason" rows="4" required 
                                          placeholder="Jelaskan alasan Anda membatalkan reservasi ini..."></textarea>
                                <div class="form-text">
                                    Berikan alasan yang jelas untuk membantu kami memahami situasi Anda.
                                </div>
                            </div>

                            <!-- Informasi Pengembalian Dana -->
                            <div class="mb-4 p-4 refund-info rounded">
                                <h6><i class="fas fa-exclamation-triangle me-2"></i>Kebijakan Pengembalian Dana</h6>
                                <ul class="mb-0">
                                    <li>Pembatalan lebih dari 7 hari sebelum tanggal reservasi: Pengembalian 100%</li>
                                    <li>Pembatalan 3-7 hari sebelum tanggal reservasi: Pengembalian 50%</li>
                                    <li>Pembatalan kurang dari 3 hari sebelum tanggal reservasi: Tidak ada pengembalian</li>
                                    <li>Biaya administrasi 10% akan dikenakan untuk semua pembatalan</li>
                                </ul>
                            </div>

                            <!-- Konfirmasi -->
                            <div class="mb-4">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="confirm_cancellation" required>
                                    <label class="form-check-label" for="confirm_cancellation">
                                        Saya memahami bahwa pengajuan pembatalan ini akan diproses dalam 1x24 jam dan 
                                        mungkin dikenakan biaya administrasi sesuai kebijakan yang berlaku.
                                    </label>
                                </div>
                            </div>

                            <button type="submit" class="btn btn-warning btn-lg w-100 py-3">
                                <i class="fas fa-paper-plane me-2"></i>Ajukan Pembatalan
                            </button>
                        </form>
                        <?php else: ?>
                            <!-- Jika tidak ada reservasi yang dipilih -->
                            <div class="text-center py-5">
                                <i class="fas fa-calendar-times fa-4x text-muted mb-3"></i>
                                <h5 class="text-muted">Tidak ada reservasi yang dipilih</h5>
                                <p class="text-muted mb-4">Silakan pilih reservasi yang ingin dibatalkan dari dashboard Anda.</p>
                                <a href="dashboard.php" class="btn btn-primary">
                                    <i class="fas fa-arrow-left me-1"></i> Kembali ke Dashboard
                                </a>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Informasi Proses -->
                <div class="card mt-4">
                    <div class="card-header bg-info text-white">
                        <h6 class="mb-0"><i class="fas fa-info-circle me-2"></i>Proses Pembatalan</h6>
                    </div>
                    <div class="card-body">
                        <div class="row text-center">
                            <div class="col-md-3">
                                <div class="bg-primary text-white rounded-circle d-inline-flex align-items-center justify-content-center mb-2" style="width: 50px; height: 50px;">
                                    <i class="fas fa-paper-plane"></i>
                                </div>
                                <h6>1. Ajukan</h6>
                                <small class="text-muted">Isi form pengajuan pembatalan</small>
                            </div>
                            <div class="col-md-3">
                                <div class="bg-warning text-white rounded-circle d-inline-flex align-items-center justify-content-center mb-2" style="width: 50px; height: 50px;">
                                    <i class="fas fa-clock"></i>
                                </div>
                                <h6>2. Review</h6>
                                <small class="text-muted">Admin akan memproses dalam 24 jam</small>
                            </div>
                            <div class="col-md-3">
                                <div class="bg-success text-white rounded-circle d-inline-flex align-items-center justify-content-center mb-2" style="width: 50px; height: 50px;">
                                    <i class="fas fa-check"></i>
                                </div>
                                <h6>3. Konfirmasi</h6>
                                <small class="text-muted">Dapatkan notifikasi hasil</small>
                            </div>
                            <div class="col-md-3">
                                <div class="bg-info text-white rounded-circle d-inline-flex align-items-center justify-content-center mb-2" style="width: 50px; height: 50px;">
                                    <i class="fas fa-money-bill-wave"></i>
                                </div>
                                <h6>4. Refund</h6>
                                <small class="text-muted">Pengembalian dana (jika berlaku)</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>