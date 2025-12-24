<?php
// File: user/checkin.php
include '../includes/config.php';
include '../includes/auth.php';

if (!isLoggedIn() || isAdmin()) {
    redirect('../login.php');
}

$error = '';
$success = '';
$reservation = null;

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $booking_code = sanitize($_POST['booking_code']);

    // Cari reservasi berdasarkan kode booking
    $stmt = $pdo->prepare("
        SELECT r.*, s.name as service_name, s.location
        FROM reservations r 
        JOIN services s ON r.service_id = s.id 
        WHERE r.booking_code = ? AND r.user_id = ?
    ");
    $stmt->execute([$booking_code, $_SESSION['user_id']]);
    $reservation = $stmt->fetch();

    if ($reservation) {
        if ($reservation['status'] == 'confirmed') {
            // Update status menjadi completed (check-in berhasil)
            $stmt = $pdo->prepare("UPDATE reservations SET status = 'completed' WHERE id = ?");
            if ($stmt->execute([$reservation['id']])) {
                logActivity('checkin', 'Check-in successful for booking: ' . $booking_code);
                $success = "Check-in berhasil! Selamat menikmati perjalanan Anda di " . $reservation['service_name'] . ".";
                $reservation = null; // Reset form
            } else {
                $error = "Terjadi kesalahan saat melakukan check-in.";
            }
        } else {
            $error = "Reservasi belum dikonfirmasi atau sudah dibatalkan. Status saat ini: " . $reservation['status'];
        }
    } else {
        $error = "Kode booking tidak ditemukan atau tidak sesuai dengan akun Anda.";
    }
}

// Get user's confirmed reservations for quick selection - PERBAIKAN DI SINI
$confirmed_reservations = $pdo->prepare("
    SELECT r.booking_code, s.name as service_name, r.reservation_date 
    FROM reservations r 
    JOIN services s ON r.service_id = s.id 
    WHERE r.user_id = ? AND r.status = 'confirmed' AND r.reservation_date >= CURDATE()
    ORDER BY r.reservation_date ASC
");
$confirmed_reservations->execute([$_SESSION['user_id']]);
$quick_reservations = $confirmed_reservations->fetchAll();
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Check-in Online - HealingKuy!.id</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        .checkin-card {
            border: none;
            border-radius: 15px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        }
        .quick-select-card {
            transition: all 0.3s;
            cursor: pointer;
        }
        .quick-select-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0,0,0,0.15);
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
                <div class="card checkin-card">
                    <div class="card-header bg-success text-white">
                        <h4 class="mb-0"><i class="fas fa-check-circle me-2"></i>Check-in Online</h4>
                    </div>
                    <div class="card-body p-4">
                        <?php if($error): ?>
                        <div class="alert alert-danger"><?php echo $error; ?></div>
                        <?php endif; ?>
                        
                        <?php if($success): ?>
                        <div class="alert alert-success"><?php echo $success; ?></div>
                        <?php endif; ?>

                        <div class="mb-4">
                            <p class="text-muted">
                                Lakukan check-in online menggunakan kode booking yang Anda terima. 
                                Pastikan Anda melakukan check-in pada hari kunjungan.
                            </p>
                        </div>

                        <form method="POST" id="checkinForm">
                            <div class="mb-4">
                                <label for="booking_code" class="form-label fw-bold">Kode Booking</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light"><i class="fas fa-ticket-alt"></i></span>
                                    <input type="text" class="form-control form-control-lg" id="booking_code" name="booking_code" 
                                           required placeholder="Masukkan kode booking (contoh: HK20241201ABC123)">
                                </div>
                                <div class="form-text">
                                    Kode booking dapat ditemukan di email konfirmasi atau di halaman detail reservasi.
                                </div>
                            </div>

                            <button type="submit" class="btn btn-success btn-lg w-100 py-3">
                                <i class="fas fa-check me-2"></i>Check-in Sekarang
                            </button>
                        </form>

                        <?php if (!empty($quick_reservations)): ?>
                        <div class="mt-5">
                            <h5 class="mb-3">Reservasi yang Dapat Check-in</h5>
                            <div class="row">
                                <?php foreach($quick_reservations as $res): ?>
                                <div class="col-md-6 mb-3">
                                    <div class="card quick-select-card border-success" onclick="document.getElementById('booking_code').value = '<?php echo $res['booking_code']; ?>'">
                                        <div class="card-body">
                                            <h6 class="card-title text-success"><?php echo $res['service_name']; ?></h6>
                                            <p class="card-text mb-1">
                                                <small class="text-muted">Kode: <?php echo $res['booking_code']; ?></small>
                                            </p>
                                            <p class="card-text mb-0">
                                                <small>
                                                    <i class="fas fa-calendar me-1"></i>
                                                    <?php echo date('d M Y', strtotime($res['reservation_date'])); ?>
                                                </small>
                                            </p>
                                        </div>
                                    </div>
                                </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                        <?php endif; ?>

                        <?php if ($reservation): ?>
                        <div class="mt-4 p-4 bg-light rounded">
                            <h5>Detail Reservasi</h5>
                            <div class="row">
                                <div class="col-md-6">
                                    <table class="table table-sm">
                                        <tr>
                                            <th>Kode Booking</th>
                                            <td><?php echo $reservation['booking_code']; ?></td>
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
                                    <table class="table table-sm">
                                        <tr>
                                            <th>Tanggal</th>
                                            <td><?php echo date('d F Y', strtotime($reservation['reservation_date'])); ?></td>
                                        </tr>
                                        <tr>
                                            <th>Jumlah Tamu</th>
                                            <td><?php echo $reservation['guests']; ?> orang</td>
                                        </tr>
                                        <tr>
                                            <th>Status</th>
                                            <td>
                                                <span class="badge bg-success">Siap Check-in</span>
                                            </td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Informasi Check-in -->
                <div class="card mt-4">
                    <div class="card-header bg-info text-white">
                        <h6 class="mb-0"><i class="fas fa-info-circle me-2"></i>Informasi Check-in</h6>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <h6>Keuntungan Check-in Online:</h6>
                                <ul class="mb-0">
                                    <li>Proses lebih cepat saat tiba di lokasi</li>
                                    <li>Hindari antrian panjang</li>
                                    <li>Dapat notifikasi konfirmasi instan</li>
                                </ul>
                            </div>
                            <div class="col-md-6">
                                <h6>Syarat Check-in:</h6>
                                <ul class="mb-0">
                                    <li>Reservasi harus dalam status "Dikonfirmasi"</li>
                                    <li>Check-in hanya bisa dilakukan pada hari kunjungan</li>
                                    <li>Bawa bukti identitas yang valid</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Auto capitalize booking code
        document.getElementById('booking_code').addEventListener('input', function(e) {
            this.value = this.value.toUpperCase();
        });

        // Form validation
        document.getElementById('checkinForm').addEventListener('submit', function(e) {
            const bookingCode = document.getElementById('booking_code').value;
            if (!bookingCode.startsWith('HK')) {
                e.preventDefault();
                alert('Format kode booking tidak valid. Kode booking harus dimulai dengan "HK"');
                return;
            }
        });
    </script>
</body>
</html>