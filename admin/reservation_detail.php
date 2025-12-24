<?php
// File: admin/reservation_detail.php
include '../includes/config.php';

if (!isLoggedIn() || !isAdmin()) {
    redirect('../login.php');
}

if (!isset($_GET['id'])) {
    redirect('dashboard.php');
}

$reservation_id = sanitize($_GET['id']);

// Ambil data reservasi
$stmt = $pdo->prepare("
    SELECT r.*, s.name as service_name, s.location, s.description as service_description, s.price as service_price,
           h.name as hotel_name, h.description as hotel_description, h.price_per_night,
           u.name as user_name, u.email, u.phone
    FROM reservations r
    JOIN services s ON r.service_id = s.id
    LEFT JOIN hotels h ON r.hotel_id = h.id
    JOIN users u ON r.user_id = u.id
    WHERE r.id = ?
");
$stmt->execute([$reservation_id]);
$reservation = $stmt->fetch();

if (!$reservation) {
    $_SESSION['error'] = "Reservasi tidak ditemukan.";
    redirect('dashboard.php');
}

// Cek apakah ada pengajuan pembatalan
$stmt = $pdo->prepare("SELECT * FROM cancellations WHERE reservation_id = ?");
$stmt->execute([$reservation_id]);
$cancellation = $stmt->fetch();
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Reservasi - HealingKuy!.id</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        .detail-card {
            border: none;
            border-radius: 15px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        }
        .status-badge {
            font-size: 0.9rem;
            padding: 0.5rem 1rem;
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
                    <i class="fas fa-user-shield me-1"></i> Admin: <?php echo $_SESSION['name']; ?>
                </span>
                <a class="nav-link" href="dashboard.php"><i class="fas fa-tachometer-alt me-1"></i> Dashboard</a>
                <a class="nav-link" href="../logout.php"><i class="fas fa-sign-out-alt me-1"></i> Logout</a>
            </div>
        </div>
    </nav>

    <div class="container mt-4">
        <div class="row justify-content-center">
            <div class="col-md-10">
                <div class="card detail-card">
                    <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                        <h4 class="mb-0"><i class="fas fa-info-circle me-2"></i>Detail Reservasi</h4>
                        <a href="dashboard.php" class="btn btn-light btn-sm">
                            <i class="fas fa-arrow-left me-1"></i> Kembali
                        </a>
                    </div>
                    <div class="card-body">
                        <!-- Header Info -->
                        <div class="row mb-4">
                            <div class="col-md-8">
                                <h3><?php echo $reservation['service_name']; ?></h3>
                                <p class="text-muted mb-0">
                                    <i class="fas fa-map-marker-alt me-1"></i><?php echo $reservation['location']; ?>
                                </p>
                            </div>
                            <div class="col-md-4 text-end">
                                <span class="badge status-badge
                                    <?php
                                    switch($reservation['status']) {
                                        case 'confirmed': echo 'bg-success'; break;
                                        case 'pending': echo 'bg-warning'; break;
                                        case 'cancelled': echo 'bg-danger'; break;
                                        case 'completed': echo 'bg-info'; break;
                                        default: echo 'bg-secondary';
                                    }
                                    ?>
                                ">
                                    <?php
                                    $status_text = [
                                        'confirmed' => 'Dikonfirmasi',
                                        'pending' => 'Menunggu Konfirmasi',
                                        'cancelled' => 'Dibatalkan',
                                        'completed' => 'Selesai'
                                    ];
                                    echo $status_text[$reservation['status']] ?? ucfirst($reservation['status']);
                                    ?>
                                </span>
                                <div class="mt-2">
                                    <strong>Kode Booking:</strong>
                                    <span class="text-primary fw-bold"><?php echo $reservation['booking_code']; ?></span>
                                </div>
                            </div>
                        </div>

                        <!-- Informasi Reservasi -->
                        <div class="row">
                            <div class="col-md-6">
                                <div class="card mb-4">
                                    <div class="card-header bg-light">
                                        <h6 class="mb-0"><i class="fas fa-calendar-alt me-2"></i>Informasi Reservasi</h6>
                                    </div>
                                    <div class="card-body">
                                        <table class="table table-borderless table-sm">
                                            <tr>
                                                <th width="40%">Tanggal Reservasi</th>
                                                <td><?php echo date('d F Y', strtotime($reservation['reservation_date'])); ?></td>
                                            </tr>
                                            <tr>
                                                <th>Jumlah Tamu</th>
                                                <td><?php echo $reservation['guests']; ?> orang</td>
                                            </tr>
                                            <tr>
                                                <th>Total Biaya</th>
                                                <td class="fw-bold text-primary">Rp <?php echo number_format($reservation['total_price'], 0, ',', '.'); ?></td>
                                            </tr>
                                            <tr>
                                                <th>Tanggal Pemesanan</th>
                                                <td><?php echo date('d F Y H:i', strtotime($reservation['created_at'])); ?></td>
                                            </tr>
                                        </table>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="card mb-4">
                                    <div class="card-header bg-light">
                                        <h6 class="mb-0"><i class="fas fa-user me-2"></i>Informasi Pemesan</h6>
                                    </div>
                                    <div class="card-body">
                                        <table class="table table-borderless table-sm">
                                            <tr>
                                                <th width="40%">Nama</th>
                                                <td><?php echo $reservation['user_name']; ?></td>
                                            </tr>
                                            <tr>
                                                <th>Email</th>
                                                <td><?php echo $reservation['email']; ?></td>
                                            </tr>
                                            <tr>
                                                <th>Telepon</th>
                                                <td><?php echo $reservation['phone'] ?: '-'; ?></td>
                                            </tr>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Deskripsi Layanan -->
                        <div class="card mb-4">
                            <div class="card-header bg-light">
                                <h6 class="mb-0"><i class="fas fa-info-circle me-2"></i>Deskripsi Layanan</h6>
                            </div>
                            <div class="card-body">
                                <p class="mb-0"><?php echo $reservation['service_description']; ?></p>
                            </div>
                        </div>

                        <!-- Informasi Hotel -->
                        <?php if (!empty($reservation['hotel_name'])): ?>
                        <div class="card mb-4">
                            <div class="card-header bg-light">
                                <h6 class="mb-0"><i class="fas fa-hotel me-2"></i>Informasi Hotel</h6>
                            </div>
                            <div class="card-body">
                                <h5><?php echo $reservation['hotel_name']; ?></h5>
                                <p class="mb-2"><?php echo $reservation['hotel_description']; ?></p>
                                <p class="text-success fw-bold">Rp <?php echo number_format($reservation['price_per_night'], 0, ',', '.'); ?>/malam</p>
                            </div>
                        </div>
                        <?php endif; ?>

                        <?php if (!empty($reservation['special_requests'])): ?>
                        <div class="card mb-4">
                            <div class="card-header bg-light">
                                <h6 class="mb-0"><i class="fas fa-sticky-note me-2"></i>Permintaan Khusus</h6>
                            </div>
                            <div class="card-body">
                                <p class="mb-0"><?php echo $reservation['special_requests']; ?></p>
                            </div>
                        </div>
                        <?php endif; ?>

                        <?php if ($cancellation): ?>
                        <div class="card mb-4 border-warning">
                            <div class="card-header bg-warning text-dark">
                                <h6 class="mb-0"><i class="fas fa-exclamation-triangle me-2"></i>Pengajuan Pembatalan</h6>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <p><strong>Alasan:</strong><br><?php echo $cancellation['reason']; ?></p>
                                    </div>
                                    <div class="col-md-6">
                                        <p><strong>Status:</strong>
                                            <span class="badge
                                                <?php
                                                switch($cancellation['status']) {
                                                    case 'approved': echo 'bg-success'; break;
                                                    case 'pending': echo 'bg-warning'; break;
                                                    case 'rejected': echo 'bg-danger'; break;
                                                    default: echo 'bg-secondary';
                                                }
                                                ?>
                                            ">
                                                <?php
                                                $cancel_status_text = [
                                                    'approved' => 'Disetujui',
                                                    'pending' => 'Menunggu',
                                                    'rejected' => 'Ditolak'
                                                ];
                                                echo $cancel_status_text[$cancellation['status']] ?? ucfirst($cancellation['status']);
                                                ?>
                                            </span>
                                        </p>
                                        <?php if ($cancellation['admin_notes']): ?>
                                        <p><strong>Catatan Admin:</strong><br><?php echo $cancellation['admin_notes']; ?></p>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php endif; ?>

                        <!-- Action Buttons -->
                        <div class="d-flex gap-2 justify-content-end">
                            <a href="dashboard.php" class="btn btn-secondary">
                                <i class="fas fa-arrow-left me-1"></i> Kembali
                            </a>
                            <?php if ($reservation['status'] == 'pending'): ?>
                                <form method="post" action="update_reservation.php" class="d-inline">
                                    <input type="hidden" name="reservation_id" value="<?php echo $reservation['id']; ?>">
                                    <input type="hidden" name="action" value="confirm">
                                    <button type="submit" class="btn btn-success">
                                        <i class="fas fa-check me-1"></i> Konfirmasi
                                    </button>
                                </form>
                                <form method="post" action="update_reservation.php" class="d-inline">
                                    <input type="hidden" name="reservation_id" value="<?php echo $reservation['id']; ?>">
                                    <input type="hidden" name="action" value="cancel">
                                    <button type="submit" class="btn btn-danger">
                                        <i class="fas fa-times me-1"></i> Batalkan
                                    </button>
                                </form>
                            <?php endif; ?>
                            <?php if ($reservation['status'] == 'confirmed'): ?>
                                <form method="post" action="update_reservation.php" class="d-inline">
                                    <input type="hidden" name="reservation_id" value="<?php echo $reservation['id']; ?>">
                                    <input type="hidden" name="action" value="complete">
                                    <button type="submit" class="btn btn-info">
                                        <i class="fas fa-check-circle me-1"></i> Selesai
                                    </button>
                                </form>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
