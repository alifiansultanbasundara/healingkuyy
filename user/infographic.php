<?php
// File: user/infographic.php
include '../includes/config.php';
include '../includes/auth.php';

if (!isLoggedIn() || isAdmin()) {
    redirect('../login.php');
}

$user_id = $_SESSION['user_id'];

// Data statistik user
$total_reservations = $pdo->prepare("SELECT COUNT(*) FROM reservations WHERE user_id = ?");
$total_reservations->execute([$user_id]);
$total_reservations = $total_reservations->fetchColumn();

$completed_reservations = $pdo->prepare("SELECT COUNT(*) FROM reservations WHERE user_id = ? AND status = 'completed'");
$completed_reservations->execute([$user_id]);
$completed_reservations = $completed_reservations->fetchColumn();

$cancelled_reservations = $pdo->prepare("SELECT COUNT(*) FROM reservations WHERE user_id = ? AND status = 'cancelled'");
$cancelled_reservations->execute([$user_id]);
$cancelled_reservations = $cancelled_reservations->fetchColumn();

$pending_reservations = $pdo->prepare("SELECT COUNT(*) FROM reservations WHERE user_id = ? AND status = 'pending'");
$pending_reservations->execute([$user_id]);
$pending_reservations = $pending_reservations->fetchColumn();

$total_spent = $pdo->prepare("SELECT SUM(total_price) FROM reservations WHERE user_id = ? AND status IN ('completed', 'confirmed')");
$total_spent->execute([$user_id]);
$total_spent = $total_spent->fetchColumn() ?: 0;

$service_spent = $pdo->prepare("SELECT SUM(service_total) FROM reservations WHERE user_id = ? AND status IN ('completed', 'confirmed')");
$service_spent->execute([$user_id]);
$service_spent = $service_spent->fetchColumn() ?: 0;

$hotel_spent = $pdo->prepare("SELECT SUM(hotel_total) FROM reservations WHERE user_id = ? AND status IN ('completed', 'confirmed')");
$hotel_spent->execute([$user_id]);
$hotel_spent = $hotel_spent->fetchColumn() ?: 0;

// Data untuk chart (reservasi per bulan)
$monthly_data = $pdo->prepare("
    SELECT 
        MONTH(created_at) as month,
        COUNT(*) as count
    FROM reservations 
    WHERE user_id = ? AND YEAR(created_at) = YEAR(CURDATE())
    GROUP BY MONTH(created_at)
    ORDER BY month
");
$monthly_data->execute([$user_id]);
$monthly_stats = $monthly_data->fetchAll();

// Data destinasi favorit
$favorite_destinations = $pdo->prepare("
    SELECT s.name, COUNT(*) as visit_count
    FROM reservations r 
    JOIN services s ON r.service_id = s.id 
    WHERE r.user_id = ? AND r.status = 'completed'
    GROUP BY s.id 
    ORDER BY visit_count DESC 
    LIMIT 5
");
$favorite_destinations->execute([$user_id]);
$favorites = $favorite_destinations->fetchAll();

// Siapkan data untuk chart
$months = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'];
$reservation_counts = array_fill(0, 12, 0);
foreach ($monthly_stats as $stat) {
    $reservation_counts[$stat['month'] - 1] = $stat['count'];
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Infografis - HealingKuy!.id</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        .stat-card {
            border: none;
            border-radius: 15px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
            transition: transform 0.3s;
        }
        .stat-card:hover {
            transform: translateY(-5px);
        }
        .chart-container {
            background: white;
            border-radius: 15px;
            padding: 2rem;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
            margin-bottom: 2rem;
        }
        .achievement-badge {
            width: 80px;
            height: 80px;
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
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2>Infografis & Rekap Data</h2>
            <div class="text-muted">
                <i class="fas fa-calendar me-1"></i> Update: <?php echo date('d F Y'); ?>
            </div>
        </div>

        <!-- Statistik Utama -->
        <div class="row mb-5">
            <div class="col-md-3 mb-3">
                <div class="card stat-card text-white bg-primary">
                    <div class="card-body text-center">
                        <i class="fas fa-calendar-alt fa-3x mb-3"></i>
                        <h3><?php echo $total_reservations; ?></h3>
                        <p class="mb-0">Total Reservasi</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3 mb-3">
                <div class="card stat-card text-white bg-success">
                    <div class="card-body text-center">
                        <i class="fas fa-check-circle fa-3x mb-3"></i>
                        <h3><?php echo $completed_reservations; ?></h3>
                        <p class="mb-0">Selesai</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3 mb-3">
                <div class="card stat-card text-white bg-danger">
                    <div class="card-body text-center">
                        <i class="fas fa-times-circle fa-3x mb-3"></i>
                        <h3><?php echo $cancelled_reservations; ?></h3>
                        <p class="mb-0">Dibatalkan</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3 mb-3">
                <div class="card stat-card text-white bg-info">
                    <div class="card-body text-center">
                        <i class="fas fa-money-bill-wave fa-3x mb-3"></i>
                        <h3>Rp <?php echo number_format($total_spent, 0, ',', '.'); ?></h3>
                        <p class="mb-0">Total Pengeluaran</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Breakdown Pengeluaran -->
        <div class="row mb-4">
            <div class="col-md-6">
                <div class="chart-container">
                    <h4 class="mb-4">Breakdown Pengeluaran</h4>
                    <canvas id="expenseBreakdownChart" height="200"></canvas>
                </div>
            </div>
            <div class="col-md-6">
                <div class="chart-container">
                    <h4 class="mb-4">Detail Pengeluaran</h4>
                    <div class="row text-center">
                        <div class="col-6">
                            <div class="border rounded p-3 mb-3">
                                <h4 class="text-primary">Rp <?php echo number_format($service_spent, 0, ',', '.'); ?></h4>
                                <small class="text-muted">Biaya Layanan</small>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="border rounded p-3 mb-3">
                                <h4 class="text-success">Rp <?php echo number_format($hotel_spent, 0, ',', '.'); ?></h4>
                                <small class="text-muted">Biaya Hotel</small>
                            </div>
                        </div>
                    </div>
                    <div class="alert alert-info">
                        <h6><i class="fas fa-info-circle me-2"></i>Informasi</h6>
                        <p class="mb-0">Total pengeluaran mencakup biaya layanan wisata dan akomodasi hotel yang telah dikonfirmasi.</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Charts -->
        <div class="row">
            <div class="col-md-8">
                <div class="chart-container">
                    <h4 class="mb-4">Aktivitas Reservasi Tahun <?php echo date('Y'); ?></h4>
                    <canvas id="reservationChart" height="250"></canvas>
                </div>
            </div>
            <div class="col-md-4">
                <div class="chart-container">
                    <h4 class="mb-4">Status Reservasi</h4>
                    <canvas id="statusChart" height="250"></canvas>
                </div>
            </div>
        </div>

        <!-- Destinasi Favorit & Pencapaian -->
        <div class="row mt-4">
            <div class="col-md-6">
                <div class="chart-container">
                    <h4 class="mb-4">Destinasi Favorit</h4>
                    <?php if(empty($favorites)): ?>
                        <div class="text-center py-4">
                            <i class="fas fa-map-marker-alt fa-3x text-muted mb-3"></i>
                            <p class="text-muted">Belum ada data destinasi favorit</p>
                        </div>
                    <?php else: ?>
                        <div class="list-group">
                            <?php foreach($favorites as $index => $fav): ?>
                            <div class="list-group-item d-flex justify-content-between align-items-center">
                                <div>
                                    <span class="badge bg-primary me-2">#<?php echo $index + 1; ?></span>
                                    <?php echo $fav['name']; ?>
                                </div>
                                <span class="badge bg-success rounded-pill"><?php echo $fav['visit_count']; ?>x</span>
                            </div>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
            <div class="col-md-6">
                <div class="chart-container">
                    <h4 class="mb-4">Pencapaian</h4>
                    <div class="row text-center">
                        <div class="col-4">
                            <div class="achievement-badge bg-warning text-white rounded-circle d-inline-flex align-items-center justify-content-center mb-2">
                                <i class="fas fa-<?php echo $total_reservations >= 1 ? 'check' : 'clock'; ?>"></i>
                            </div>
                            <h6>Explorer Pemula</h6>
                            <small class="text-muted">1 Reservasi</small>
                        </div>
                        <div class="col-4">
                            <div class="achievement-badge bg-success text-white rounded-circle d-inline-flex align-items-center justify-content-center mb-2">
                                <i class="fas fa-<?php echo $total_reservations >= 3 ? 'check' : 'clock'; ?>"></i>
                            </div>
                            <h6>Petualang Sejati</h6>
                            <small class="text-muted">3 Reservasi</small>
                        </div>
                        <div class="col-4">
                            <div class="achievement-badge bg-primary text-white rounded-circle d-inline-flex align-items-center justify-content-center mb-2">
                                <i class="fas fa-<?php echo $total_reservations >= 5 ? 'check' : 'clock'; ?>"></i>
                            </div>
                            <h6>Wisatawan Pro</h6>
                            <small class="text-muted">5 Reservasi</small>
                        </div>
                    </div>
                </div>

                <!-- Tips & Rekomendasi -->
                <div class="chart-container mt-4">
                    <h4 class="mb-4">Tips Untuk Anda</h4>
                    <div class="alert alert-info">
                        <h6><i class="fas fa-lightbulb me-2"></i>Rencanakan Lebih Awal</h6>
                        <p class="mb-0">Booking 2 minggu sebelumnya untuk harga terbaik</p>
                    </div>
                    <div class="alert alert-success">
                        <h6><i class="fas fa-users me-2"></i>Diskon Group</h6>
                        <p class="mb-0">Dapatkan diskon 15% untuk reservasi > 10 orang</p>
                    </div>
                    <div class="alert alert-warning">
                        <h6><i class="fas fa-calendar me-2"></i>Musim Ramai</h6>
                        <p class="mb-0">Hindari weekend untuk pengalaman yang lebih nyaman</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Ringkasan Bulanan -->
        <div class="chart-container mt-4">
            <h4 class="mb-4">Ringkasan Performa</h4>
            <div class="row text-center">
                <div class="col-md-3">
                    <div class="border rounded p-3">
                        <h3 class="text-primary"><?php echo $completed_reservations; ?></h3>
                        <small class="text-muted">Perjalanan Selesai</small>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="border rounded p-3">
                        <h3 class="text-success"><?php echo count($favorites); ?></h3>
                        <small class="text-muted">Destinasi Dikunjungi</small>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="border rounded p-3">
                        <h3 class="text-info"><?php echo $pending_reservations; ?></h3>
                        <small class="text-muted">Dalam Proses</small>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="border rounded p-3">
                        <h3 class="text-warning"><?php echo $total_reservations > 0 ? round(($completed_reservations / $total_reservations) * 100, 1) : 0; ?>%</h3>
                        <small class="text-muted">Rate Penyelesaian</small>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Reservation Activity Chart
        const reservationCtx = document.getElementById('reservationChart').getContext('2d');
        const reservationChart = new Chart(reservationCtx, {
            type: 'bar',
            data: {
                labels: <?php echo json_encode($months); ?>,
                datasets: [{
                    label: 'Jumlah Reservasi',
                    data: <?php echo json_encode($reservation_counts); ?>,
                    backgroundColor: 'rgba(54, 162, 235, 0.5)',
                    borderColor: 'rgba(54, 162, 235, 1)',
                    borderWidth: 1
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
                        ticks: {
                            stepSize: 1
                        }
                    }
                }
            }
        });

        // Check if chart rendered successfully
        if (!reservationChart) {
            console.error('Failed to create reservation chart');
        }

        // Status Chart
        const statusCtx = document.getElementById('statusChart').getContext('2d');
        const statusChart = new Chart(statusCtx, {
            type: 'doughnut',
            data: {
                labels: ['Selesai', 'Dibatalkan', 'Menunggu', 'Lainnya'],
                datasets: [{
                    data: [
                        <?php echo $completed_reservations; ?>,
                        <?php echo $cancelled_reservations; ?>,
                        <?php echo $pending_reservations; ?>,
                        <?php echo $total_reservations - $completed_reservations - $cancelled_reservations - $pending_reservations; ?>
                    ],
                    backgroundColor: [
                        '#28a745',
                        '#dc3545',
                        '#ffc107',
                        '#6c757d'
                    ]
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'bottom'
                    }
                }
            }
        });

        // Expense Breakdown Chart
        const expenseCtx = document.getElementById('expenseBreakdownChart').getContext('2d');
        const expenseChart = new Chart(expenseCtx, {
            type: 'pie',
            data: {
                labels: ['Biaya Layanan', 'Biaya Hotel'],
                datasets: [{
                    data: [
                        <?php echo $service_spent; ?>,
                        <?php echo $hotel_spent; ?>
                    ],
                    backgroundColor: [
                        '#007bff',
                        '#28a745'
                    ],
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'bottom'
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                let label = context.label || '';
                                if (label) {
                                    label += ': ';
                                }
                                label += 'Rp ' + context.parsed.toLocaleString('id-ID');
                                return label;
                            }
                        }
                    }
                }
            }
        });
    </script>
</body>
</html>