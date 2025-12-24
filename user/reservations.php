<?php
// File: user/reservations.php
include '../includes/config.php';
include '../includes/auth.php';

if (!isLoggedIn() || isAdmin()) {
    redirect('../login.php');
}

// Get user reservations
$reservations = $pdo->query("
    SELECT r.*, s.name as service_name, s.location
    FROM reservations r
    JOIN services s ON r.service_id = s.id
    WHERE r.user_id = " . $_SESSION['user_id'] . "
    ORDER BY r.created_at DESC
")->fetchAll();
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Riwayat Reservasi - HealingKuy!.id</title>
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
        .card {
            border: none;
            border-radius: 15px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        }
        .table th {
            border-top: none;
            font-weight: 600;
        }
        .status-badge {
            font-size: 0.875rem;
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
                    <a href="dashboard.php" class="list-group-item list-group-item-action">
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
                    <a href="../infografis" class="list-group-item list-group-item-action">
                        <i class="fas fa-chart-bar me-2"></i>Infografis
                    </a>
                    <a href="profile.php" class="list-group-item list-group-item-action">
                        <i class="fas fa-user me-2"></i>Profil
                    </a>
                </div>
            </div>

            <!-- Main Content -->
            <div class="col-md-9 p-4">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h2 class="mb-0">Riwayat Reservasi</h2>
                    <a href="reservation.php" class="btn btn-primary">
                        <i class="fas fa-plus me-1"></i> Reservasi Baru
                    </a>
                </div>

                <!-- Reservations List -->
                <div class="card">
                    <div class="card-header bg-light">
                        <h5 class="mb-0"><i class="fas fa-calendar-alt me-2"></i>Daftar Reservasi Saya</h5>
                    </div>
                    <div class="card-body">
                        <?php if(empty($reservations)): ?>
                            <div class="text-center py-5">
                                <i class="fas fa-calendar-times fa-4x text-muted mb-3"></i>
                                <h4 class="text-muted">Belum ada reservasi</h4>
                                <p class="text-muted">Anda belum membuat reservasi apapun. Mulai perjalanan Anda sekarang!</p>
                                <a href="reservation.php" class="btn btn-primary btn-lg">
                                    <i class="fas fa-plus me-2"></i>Buat Reservasi Pertama
                                </a>
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
                                        <?php foreach($reservations as $reservation): ?>
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
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
