<?php
// File: admin/dashboard.php
include '../includes/config.php';

if (!isLoggedIn() || !isAdmin()) {
    redirect('../login.php');
}

// Get statistics
$today = date('Y-m-d');
$reservations_today = $pdo->query("SELECT COUNT(*) FROM reservations WHERE DATE(created_at) = '$today'")->fetchColumn();
$pending_cancellations = $pdo->query("SELECT COUNT(*) FROM cancellations WHERE status = 'pending'")->fetchColumn();
$total_revenue = $pdo->query("SELECT SUM(total_price) FROM reservations WHERE status IN ('confirmed', 'completed') AND DATE(created_at) = '$today'")->fetchColumn();
$total_users = $pdo->query("SELECT COUNT(*) FROM users WHERE role = 'user'")->fetchColumn();

// Recent transactions
$recent_transactions = $pdo->query("
    SELECT r.*, u.name as user_name, s.name as service_name 
    FROM reservations r 
    JOIN users u ON r.user_id = u.id 
    JOIN services s ON r.service_id = s.id 
    ORDER BY r.created_at DESC 
    LIMIT 10
")->fetchAll();

// Monthly revenue data for chart
$monthly_revenue = $pdo->query("
    SELECT
        MONTH(created_at) as month,
        SUM(total_price) as revenue
    FROM reservations
    WHERE status IN ('confirmed', 'completed')
    AND YEAR(created_at) = YEAR(CURDATE())
    GROUP BY MONTH(created_at)
    ORDER BY month
")->fetchAll();
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Admin - HealingKuy!.id</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        .sidebar {
            background-color: #343a40;
            color: white;
            min-height: calc(100vh - 56px);
        }
        .sidebar .nav-link {
            color: white;
            padding: 0.75rem 1rem;
            border-radius: 0.375rem;
            margin-bottom: 0.25rem;
        }
        .sidebar .nav-link:hover {
            background-color: #495057;
        }
        .sidebar .nav-link.active {
            background-color: #0d6efd;
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
                    <i class="fas fa-user-shield me-1"></i> Admin: <?php echo $_SESSION['name']; ?>
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
        <a href="manage_users.php" class="list-group-item list-group-item-action">
            <i class="fas fa-users me-2"></i>Manajemen User
        </a>
        <a href="manage_services.php" class="list-group-item list-group-item-action">
            <i class="fas fa-concierge-bell me-2"></i>Manajemen Layanan
        </a>
        <a href="manage_reservations.php" class="list-group-item list-group-item-action">
            <i class="fas fa-calendar-alt me-2"></i>Manajemen Reservasi
        </a>
        <a href="manage_cancellations.php" class="list-group-item list-group-item-action">
            <i class="fas fa-times-circle me-2"></i>Manajemen Pembatalan
        </a>
        <a href="manage_blog.php" class="list-group-item list-group-item-action">
            <i class="fas fa-blog me-2"></i>Manajemen Blog
        </a>
        <a href="reports.php" class="list-group-item list-group-item-action">
            <i class="fas fa-chart-bar me-2"></i>Laporan & Infografis
        </a>
        <a href="activity_logs.php" class="list-group-item list-group-item-action">
            <i class="fas fa-history me-2"></i>Log Aktivitas
        </a>
    </div>
</div>

            <!-- Main Content -->
            <div class="col-md-9 p-4">
                <h2 class="mb-4">Dashboard Admin</h2>

                <!-- Statistics -->
                <div class="row mb-4">
                    <div class="col-md-3">
                        <div class="card stat-card">
                            <div class="card-body">
                                <div class="d-flex justify-content-between">
                                    <div>
                                        <h5 class="card-title text-muted">Reservasi Hari Ini</h5>
                                        <h3 class="card-text text-primary"><?php echo $reservations_today; ?></h3>
                                    </div>
                                    <div class="align-self-center">
                                        <i class="fas fa-calendar-day fa-2x text-primary opacity-50"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card stat-card">
                            <div class="card-body">
                                <div class="d-flex justify-content-between">
                                    <div>
                                        <h5 class="card-title text-muted">Pembatalan Menunggu</h5>
                                        <h3 class="card-text text-warning"><?php echo $pending_cancellations; ?></h3>
                                    </div>
                                    <div class="align-self-center">
                                        <i class="fas fa-clock fa-2x text-warning opacity-50"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card stat-card">
                            <div class="card-body">
                                <div class="d-flex justify-content-between">
                                    <div>
                                        <h5 class="card-title text-muted">Pendapatan Hari Ini</h5>
                                        <h3 class="card-text text-success">Rp <?php echo number_format($total_revenue ?: 0, 0, ',', '.'); ?></h3>
                                    </div>
                                    <div class="align-self-center">
                                        <i class="fas fa-money-bill-wave fa-2x text-success opacity-50"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card stat-card">
                            <div class="card-body">
                                <div class="d-flex justify-content-between">
                                    <div>
                                        <h5 class="card-title text-muted">Total User</h5>
                                        <h3 class="card-text text-info"><?php echo $total_users; ?></h3>
                                    </div>
                                    <div class="align-self-center">
                                        <i class="fas fa-users fa-2x text-info opacity-50"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Charts and Recent Transactions -->
                <div class="row">
                    <div class="col-md-8">
                        <div class="card">
                            <div class="card-header">
                                <h5 class="mb-0">Pendapatan Bulanan</h5>
                            </div>
                            <div class="card-body">
                                <canvas id="revenueChart" height="250"></canvas>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card">
                            <div class="card-header">
                                <h5 class="mb-0">Quick Actions</h5>
                            </div>
                            <div class="card-body">
                                <div class="d-grid gap-2">
                                    <a href="services.php" class="btn btn-primary">
                                        <i class="fas fa-plus me-2"></i>Tambah Layanan
                                    </a>
                                    <a href="reservations.php" class="btn btn-success">
                                        <i class="fas fa-eye me-2"></i>Lihat Reservasi
                                    </a>
                                    <a href="cancellations.php" class="btn btn-warning">
                                        <i class="fas fa-tasks me-2"></i>Kelola Pembatalan
                                    </a>
                                    <a href="reports.php" class="btn btn-info">
                                        <i class="fas fa-chart-bar me-2"></i>Lihat Laporan
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Recent Transactions -->
                <div class="card mt-4">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">Transaksi Terbaru</h5>
                        <a href="reservations.php" class="btn btn-sm btn-outline-primary">Lihat Semua</a>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>Kode Booking</th>
                                        <th>User</th>
                                        <th>Layanan</th>
                                        <th>Tanggal</th>
                                        <th>Total</th>
                                        <th>Status</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach($recent_transactions as $transaction): ?>
                                    <tr>
                                        <td>
                                            <strong><?php echo $transaction['booking_code']; ?></strong>
                                        </td>
                                        <td><?php echo $transaction['user_name']; ?></td>
                                        <td><?php echo $transaction['service_name']; ?></td>
                                        <td><?php echo date('d/m/Y', strtotime($transaction['reservation_date'])); ?></td>
                                        <td>Rp <?php echo number_format($transaction['total_price'], 0, ',', '.'); ?></td>
                                        <td>
                                            <span class="badge 
                                                <?php 
                                                switch($transaction['status']) {
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
                                                echo $status_text[$transaction['status']] ?? ucfirst($transaction['status']); 
                                                ?>
                                            </span>
                                        </td>
                                        <td>
                                            <div class="btn-group btn-group-sm">
                                                <a href="reservation_detail.php?id=<?php echo $transaction['id']; ?>" class="btn btn-outline-primary">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                <a href="reservations.php?action=edit&id=<?php echo $transaction['id']; ?>" class="btn btn-outline-success">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Revenue Chart
        const revenueCtx = document.getElementById('revenueChart').getContext('2d');
        const revenueChart = new Chart(revenueCtx, {
            type: 'line',
            data: {
                labels: ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'],
                datasets: [{
                    label: 'Pendapatan (Rp)',
                    data: [
                        <?php
                        $monthlyData = array_fill(0, 12, 0);
                        foreach ($monthly_revenue as $data) {
                            $monthlyData[$data['month'] - 1] = $data['revenue'];
                        }
                        echo implode(', ', $monthlyData);
                        ?>
                    ],
                    borderColor: '#0d6efd',
                    backgroundColor: 'rgba(13, 110, 253, 0.1)',
                    borderWidth: 2,
                    fill: true,
                    tension: 0.4
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        display: true
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        max: 100000000,
                        ticks: {
                            callback: function(value) {
                                return 'Rp ' + value.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
                            }
                        }
                    }
                }
            }
        });
    </script>
</body>
</html>