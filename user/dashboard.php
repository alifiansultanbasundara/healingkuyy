<?php
// File: user/dashboard.php
include '../includes/config.php';

if (!isLoggedIn() || isAdmin()) {
    redirect('../login.php');
}

// Get user reservations
$stmt = $pdo->prepare("
    SELECT r.*, s.name as service_name, s.location 
    FROM reservations r 
    JOIN services s ON r.service_id = s.id 
    WHERE r.user_id = ? 
    ORDER BY r.created_at DESC
");
$stmt->execute([$_SESSION['user_id']]);
$reservations = $stmt->fetchAll();

// Get pending cancellations
$stmt = $pdo->prepare("
    SELECT c.*, r.booking_code 
    FROM cancellations c 
    JOIN reservations r ON c.reservation_id = r.id 
    WHERE r.user_id = ? AND c.status = 'pending'
");
$stmt->execute([$_SESSION['user_id']]);
$pending_cancellations = $stmt->fetchAll();

// Get confirmed reservations for today
$today = date('Y-m-d');
$stmt = $pdo->prepare("
    SELECT COUNT(*) as today_reservations 
    FROM reservations 
    WHERE user_id = ? AND reservation_date = ? AND status = 'confirmed'
");
$stmt->execute([$_SESSION['user_id'], $today]);
$today_reservations = $stmt->fetchColumn();
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard User - HealingKuy!.id</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        .sidebar {
            background-color: #f8f9fa;
            border-right: 1px solid #dee2e6;
            min-height: calc(100vh - 56px);
        }
        .sidebar .nav-link {
            color: #333;
            padding: 0.75rem 1rem;
            border-radius: 0.375rem;
            margin-bottom: 0.25rem;
        }
        .sidebar .nav-link.active {
            background-color: #0d6efd;
            color: white;
        }
        .sidebar .nav-link:hover {
            background-color: #e9ecef;
        }
        .sidebar .nav-link.active:hover {
            background-color: #0b5ed7;
        }
        .stat-card {
            border-left: 4px solid #0d6efd;
            transition: transform 0.2s;
        }
        .stat-card:hover {
            transform: translateY(-2px);
        }
        .navbar-brand {
            font-weight: 700;
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
                    <i class="fas fa-user me-1"></i> Halo, <?php echo $_SESSION['name']; ?>
                </span>
                <a class="nav-link" href="../index.php"><i class="fas fa-home me-1"></i> Home</a>
                <a class="nav-link" href="dashboard.php"><i class="fas fa-tachometer-alt me-1"></i> Dashboard</a>
                <a class="nav-link" href="../logout.php"><i class="fas fa-sign-out-alt me-1"></i> Logout</a>
            </div>
        </div>
    </nav>

    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar -->
            <div class="col-md-3 sidebar p-3">
                <div class="list-group list-group-flush">
                    <a href="dashboard.php" class="list-group-item list-group-item-action active">
                        <i class="fas fa-tachometer-alt me-2"></i>Dashboard
                    </a>
                    <a href="reservation.php" class="list-group-item list-group-item-action">
                        <i class="fas fa-calendar-plus me-2"></i>Reservasi Baru
                    </a>
                    <a href="checkin.php" class="list-group-item list-group-item-action">
                        <i class="fas fa-check-circle me-2"></i>Check-in Online
                    </a>
                    <a href="cancellation.php" class="list-group-item list-group-item-action">
                        <i class="fas fa-times-circle me-2"></i>Pengajuan Pembatalan
                    </a>
                    <a href="blog.php" class="list-group-item list-group-item-action">
                        <i class="fas fa-blog me-2"></i>Blog
                    </a>
                    <a href="infographic.php" class="list-group-item list-group-item-action">
                        <i class="fas fa-chart-bar me-2"></i>Infografis
                    </a>
                    <a href="profile.php" class="list-group-item list-group-item-action">
                        <i class="fas fa-user me-2"></i>Profil
                    </a>
                </div>
                
                <!-- Quick Stats -->
                <div class="card mt-4">
                    <div class="card-header">
                        <h6 class="mb-0"><i class="fas fa-chart-pie me-2"></i>Statistik Cepat</h6>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <small class="text-muted">Total Reservasi</small>
                            <h5 class="mb-0 text-primary"><?php echo count($reservations); ?></h5>
                        </div>
                        <div class="mb-3">
                            <small class="text-muted">Pembatalan Menunggu</small>
                            <h5 class="mb-0 text-warning"><?php echo count($pending_cancellations); ?></h5>
                        </div>
                        <div class="mb-3">
                            <small class="text-muted">Reservasi Hari Ini</small>
                            <h5 class="mb-0 text-success"><?php echo $today_reservations; ?></h5>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Main Content -->
            <div class="col-md-9 p-4">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h2 class="mb-0">Dashboard User</h2>
                    <a href="reservation.php" class="btn btn-primary">
                        <i class="fas fa-plus me-1"></i> Reservasi Baru
                    </a>
                </div>

                <!-- Welcome Card -->
                <div class="card bg-primary text-white mb-4">
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col-md-8">
                                <h4 class="card-title">Selamat Datang, <?php echo $_SESSION['name']; ?>!</h4>
                                <p class="card-text mb-0">Semoga hari Anda menyenangkan. Kelola reservasi dan rencana perjalanan Anda dengan mudah.</p>
                            </div>
                            <div class="col-md-4 text-center">
                                <i class="fas fa-compass fa-4x opacity-75"></i>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Quick Stats -->
                <div class="row mb-4">
                    <div class="col-md-4">
                        <div class="card stat-card">
                            <div class="card-body">
                                <div class="d-flex justify-content-between">
                                    <div>
                                        <h5 class="card-title text-muted">Total Reservasi</h5>
                                        <h3 class="card-text text-primary"><?php echo count($reservations); ?></h3>
                                    </div>
                                    <div class="align-self-center">
                                        <i class="fas fa-calendar-alt fa-2x text-primary opacity-50"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card stat-card">
                            <div class="card-body">
                                <div class="d-flex justify-content-between">
                                    <div>
                                        <h5 class="card-title text-muted">Pembatalan Menunggu</h5>
                                        <h3 class="card-text text-warning"><?php echo count($pending_cancellations); ?></h3>
                                    </div>
                                    <div class="align-self-center">
                                        <i class="fas fa-clock fa-2x text-warning opacity-50"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card stat-card">
                            <div class="card-body">
                                <div class="d-flex justify-content-between">
                                    <div>
                                        <h5 class="card-title text-muted">Status Akun</h5>
                                        <h3 class="card-text text-success">Aktif</h3>
                                    </div>
                                    <div class="align-self-center">
                                        <i class="fas fa-user-check fa-2x text-success opacity-50"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Recent Reservations -->
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h4 class="mb-0">Riwayat Reservasi Terbaru</h4>
                        <a href="reservations.php" class="btn btn-sm btn-outline-primary">
                            Lihat Semua
                        </a>
                    </div>
                    <div class="card-body">
                        <?php if(empty($reservations)): ?>
                            <div class="text-center py-4">
                                <i class="fas fa-calendar-times fa-3x text-muted mb-3"></i>
                                <p class="text-muted">Belum ada reservasi</p>
                                <a href="reservation.php" class="btn btn-primary">Buat Reservasi Pertama</a>
                            </div>
                        <?php else: ?>
                            <div class="table-responsive">
                                <table class="table table-striped">
                                    <thead>
                                        <tr>
                                            <th>Kode Booking</th>
                                            <th>Layanan</th>
                                            <th>Tanggal</th>
                                            <th>Jumlah</th>
                                            <th>Total</th>
                                            <th>Status</th>
                                            <th>Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach(array_slice($reservations, 0, 5) as $reservation): ?>
                                        <tr>
                                            <td>
                                                <strong><?php echo $reservation['booking_code']; ?></strong>
                                            </td>
                                            <td>
                                                <div>
                                                    <strong><?php echo $reservation['service_name']; ?></strong>
                                                    <br>
                                                    <small class="text-muted"><?php echo $reservation['location']; ?></small>
                                                </div>
                                            </td>
                                            <td><?php echo date('d/m/Y', strtotime($reservation['reservation_date'])); ?></td>
                                            <td><?php echo $reservation['guests']; ?> orang</td>
                                            <td>Rp <?php echo number_format($reservation['total_price'], 0, ',', '.'); ?></td>
                                            <td>
                                                <span class="badge 
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
                                                        'pending' => 'Menunggu',
                                                        'cancelled' => 'Dibatalkan',
                                                        'completed' => 'Selesai'
                                                    ];
                                                    echo $status_text[$reservation['status']] ?? ucfirst($reservation['status']); 
                                                    ?>
                                                </span>
                                            </td>
                                            <td>
                                                <div class="btn-group btn-group-sm">
                                                    <a href="reservation_detail.php?id=<?php echo $reservation['id']; ?>" class="btn btn-outline-primary" title="Detail">
                                                        <i class="fas fa-eye"></i>
                                                    </a>
                                                    <?php if($reservation['status'] == 'pending' || $reservation['status'] == 'confirmed'): ?>
                                                    <a href="cancellation.php?reservation_id=<?php echo $reservation['id']; ?>" class="btn btn-outline-danger" title="Ajukan Pembatalan">
                                                        <i class="fas fa-times"></i>
                                                    </a>
                                                    <?php endif; ?>
                                                </div>
                                            </td>
                                        </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                            <?php if(count($reservations) > 5): ?>
                            <div class="text-center mt-3">
                                <a href="reservations.php" class="btn btn-outline-primary">Lihat Semua Reservasi</a>
                            </div>
                            <?php endif; ?>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Quick Actions -->
                <div class="row mt-4">
                    <div class="col-md-4">
                        <div class="card text-center">
                            <div class="card-body">
                                <i class="fas fa-calendar-plus fa-3x text-primary mb-3"></i>
                                <h5>Reservasi Baru</h5>
                                <p class="text-muted">Buat reservasi untuk destinasi wisata pilihan Anda</p>
                                <a href="reservation.php" class="btn btn-primary">Pesan Sekarang</a>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card text-center">
                            <div class="card-body">
                                <i class="fas fa-check-circle fa-3x text-success mb-3"></i>
                                <h5>Check-in Online</h5>
                                <p class="text-muted">Lakukan check-in untuk reservasi yang sudah dikonfirmasi</p>
                                <a href="checkin.php" class="btn btn-success">Check-in</a>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card text-center">
                            <div class="card-body">
                                <i class="fas fa-chart-bar fa-3x text-info mb-3"></i>
                                <h5>Lihat Infografis</h5>
                                <p class="text-muted">Analisis data reservasi dan statistik perjalanan Anda</p>
                                <a href="infographic.php" class="btn btn-info">Lihat Grafik</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Auto-hide alerts after 5 seconds
        setTimeout(function() {
            var alerts = document.querySelectorAll('.alert');
            alerts.forEach(function(alert) {
                var bsAlert = new bootstrap.Alert(alert);
                bsAlert.close();
            });
        }, 5000);
    </script>
</body>
</html>