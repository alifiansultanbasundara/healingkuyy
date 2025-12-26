<?php
// File: admin/reports.php - Reports and Infographics
include '../includes/config.php';

if (!isLoggedIn() || !isAdmin()) {
    redirect('../login.php');
}

// Status yang dihitung sebagai pendapatan
$revenueStatuses = ['confirmed', 'completed'];
$placeholders = implode(',', array_fill(0, count($revenueStatuses), '?'));

// Fallback pendapatan: kalau total_price NULL/0 -> service_total + hotel_total
$revenueExpr = "
    CASE
        WHEN r.total_price IS NULL OR r.total_price = 0
            THEN COALESCE(r.service_total,0) + COALESCE(r.hotel_total,0)
        ELSE r.total_price
    END
";

// KPI
$total_reservations = (int)$pdo->query("SELECT COUNT(*) FROM reservations")->fetchColumn();
$total_users = (int)$pdo->query("SELECT COUNT(*) FROM users WHERE role = 'user'")->fetchColumn();
$total_services = (int)$pdo->query("SELECT COUNT(*) FROM services WHERE status = 'active'")->fetchColumn();
$total_hotels = (int)$pdo->query("SELECT COUNT(*) FROM hotels WHERE status = 'active'")->fetchColumn();

// Total revenue (confirmed + completed)
$stmt = $pdo->prepare("SELECT COALESCE(SUM($revenueExpr),0) FROM reservations r WHERE r.status IN ($placeholders)");
$stmt->execute($revenueStatuses);
$total_revenue = (float)$stmt->fetchColumn();

// Breakdown revenue
$stmt = $pdo->prepare("SELECT COALESCE(SUM(COALESCE(service_total,0)),0) FROM reservations WHERE status IN ($placeholders)");
$stmt->execute($revenueStatuses);
$service_revenue = (float)$stmt->fetchColumn();

$stmt = $pdo->prepare("SELECT COALESCE(SUM(COALESCE(hotel_total,0)),0) FROM reservations WHERE status IN ($placeholders)");
$stmt->execute($revenueStatuses);
$hotel_revenue = (float)$stmt->fetchColumn();

// Monthly revenue (tahun berjalan)
$stmt = $pdo->prepare("
    SELECT
        MONTH(r.created_at) as month,
        COALESCE(SUM($revenueExpr),0) as revenue
    FROM reservations r
    WHERE r.status IN ($placeholders)
      AND r.created_at IS NOT NULL
      AND YEAR(r.created_at) = YEAR(CURDATE())
    GROUP BY MONTH(r.created_at)
    ORDER BY month
");
$stmt->execute($revenueStatuses);
$monthly_revenue = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Status distribution
$status_distribution = $pdo->query("
    SELECT status, COUNT(*) as count
    FROM reservations
    GROUP BY status
    ORDER BY FIELD(status,'pending','confirmed','completed','cancelled'), status
")->fetchAll(PDO::FETCH_ASSOC);

// Top services by reservations
$top_services = $pdo->query("
    SELECT s.name, COUNT(r.id) as reservation_count
    FROM services s
    LEFT JOIN reservations r ON s.id = r.service_id
    GROUP BY s.id, s.name
    ORDER BY reservation_count DESC
    LIMIT 5
")->fetchAll(PDO::FETCH_ASSOC);

// Revenue by service
$stmt = $pdo->prepare("
    SELECT s.name, COALESCE(SUM(COALESCE(r.service_total,0)),0) as revenue
    FROM services s
    LEFT JOIN reservations r
        ON s.id = r.service_id
        AND r.status IN ($placeholders)
    GROUP BY s.id, s.name
    ORDER BY revenue DESC
    LIMIT 5
");
$stmt->execute($revenueStatuses);
$revenue_by_service = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Top hotels by revenue
$stmt = $pdo->prepare("
    SELECT h.name, COALESCE(SUM(COALESCE(r.hotel_total,0)),0) as revenue
    FROM hotels h
    LEFT JOIN reservations r
        ON h.id = r.hotel_id
        AND r.status IN ($placeholders)
    GROUP BY h.id, h.name
    ORDER BY revenue DESC
    LIMIT 5
");
$stmt->execute($revenueStatuses);
$top_hotels = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Build monthly array 12 bulan
$monthlyData = array_fill(0, 12, 0);
foreach ($monthly_revenue as $row) {
    $m = (int)$row['month'];
    if ($m >= 1 && $m <= 12) {
        $monthlyData[$m - 1] = (float)$row['revenue'];
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan & Infografis - HealingKuy!.id</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        .stat-card {
            border-left: 4px solid #0d6efd;
            transition: transform 0.2s;
        }
        .stat-card:hover { transform: translateY(-2px); }
        .chart-container { position: relative; height: 300px; }
    </style>
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container">
        <a class="navbar-brand fw-bold" href="../index.php">
            <i class="fas fa-mountain"></i> HealingKuy!.id
        </a>
        <div class="navbar-nav ms-auto">
            <span class="navbar-text me-3">
                <i class="fas fa-user-shield me-1"></i> Admin: <?php echo htmlspecialchars($_SESSION['name'] ?? 'Administrator'); ?>
            </span>
            <a class="nav-link" href="dashboard.php"><i class="fas fa-tachometer-alt me-1"></i> Dashboard</a>
            <a class="nav-link" href="../logout.php"><i class="fas fa-sign-out-alt me-1"></i> Logout</a>
        </div>
    </div>
</nav>

<div class="container mt-4">
    <h2 class="mb-4"><i class="fas fa-chart-bar me-2"></i>Laporan & Infografis</h2>

    <!-- Statistics Overview -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card stat-card">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h5 class="card-title text-muted">Total Reservasi</h5>
                            <h3 class="card-text text-primary"><?php echo number_format($total_reservations); ?></h3>
                        </div>
                        <div class="align-self-center">
                            <i class="fas fa-calendar-alt fa-2x text-primary opacity-50"></i>
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
                            <h5 class="card-title text-muted">Total Pendapatan</h5>
                            <h3 class="card-text text-success">Rp <?php echo number_format($total_revenue, 0, ',', '.'); ?></h3>
                        </div>
                        <div class="align-self-center">
                            <i class="fas fa-money-bill-wave fa-2x text-success opacity-50"></i>
                        </div>
                    </div>
                    <small class="text-muted d-block mt-2">Dihitung dari: confirmed + completed</small>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card stat-card">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h5 class="card-title text-muted">Total User</h5>
                            <h3 class="card-text text-info"><?php echo number_format($total_users); ?></h3>
                        </div>
                        <div class="align-self-center">
                            <i class="fas fa-users fa-2x text-info opacity-50"></i>
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
                            <h5 class="card-title text-muted">Total Layanan</h5>
                            <h3 class="card-text text-warning"><?php echo number_format($total_services); ?></h3>
                        </div>
                        <div class="align-self-center">
                            <i class="fas fa-concierge-bell fa-2x text-warning opacity-50"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Charts Row 1 -->
    <div class="row mb-4">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header"><h5 class="mb-0">Pendapatan Bulanan</h5></div>
                <div class="card-body">
                    <div class="chart-container"><canvas id="revenueChart"></canvas></div>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card">
                <div class="card-header"><h5 class="mb-0">Distribusi Status Reservasi</h5></div>
                <div class="card-body">
                    <div class="chart-container"><canvas id="statusChart"></canvas></div>
                </div>
            </div>
        </div>
    </div>

    <!-- Charts Row 2 -->
    <div class="row mb-4">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header"><h5 class="mb-0">Layanan Terpopuler</h5></div>
                <div class="card-body">
                    <div class="chart-container"><canvas id="servicesChart"></canvas></div>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card">
                <div class="card-header"><h5 class="mb-0">Pendapatan per Layanan</h5></div>
                <div class="card-body">
                    <div class="chart-container"><canvas id="revenueServiceChart"></canvas></div>
                </div>
            </div>
        </div>
    </div>

    <!-- Revenue Breakdown + Top Hotels -->
    <div class="row mb-4">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header"><h5 class="mb-0">Breakdown Pendapatan</h5></div>
                <div class="card-body">
                    <div class="chart-container"><canvas id="revenueBreakdownChart"></canvas></div>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card">
                <div class="card-header"><h5 class="mb-0">Top 5 Hotel berdasarkan Pendapatan</h5></div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr><th>Hotel</th><th>Pendapatan</th></tr>
                            </thead>
                            <tbody>
                                <?php if (!empty($top_hotels)): ?>
                                    <?php foreach ($top_hotels as $hotel): ?>
                                        <tr>
                                            <td><?php echo htmlspecialchars($hotel['name']); ?></td>
                                            <td>Rp <?php echo number_format($hotel['revenue'] ?: 0, 0, ',', '.'); ?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr><td colspan="2" class="text-muted">Belum ada data pendapatan hotel.</td></tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Tables -->
    <div class="row">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header"><h5 class="mb-0">Top 5 Layanan berdasarkan Reservasi</h5></div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead><tr><th>Layanan</th><th>Jumlah Reservasi</th></tr></thead>
                            <tbody>
                                <?php if (!empty($top_services)): ?>
                                    <?php foreach($top_services as $service): ?>
                                        <tr>
                                            <td><?php echo htmlspecialchars($service['name']); ?></td>
                                            <td><?php echo (int)$service['reservation_count']; ?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr><td colspan="2" class="text-muted">Belum ada data layanan.</td></tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card">
                <div class="card-header"><h5 class="mb-0">Top 5 Layanan berdasarkan Pendapatan</h5></div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead><tr><th>Layanan</th><th>Pendapatan</th></tr></thead>
                            <tbody>
                                <?php if (!empty($revenue_by_service)): ?>
                                    <?php foreach($revenue_by_service as $service): ?>
                                        <tr>
                                            <td><?php echo htmlspecialchars($service['name']); ?></td>
                                            <td>Rp <?php echo number_format($service['revenue'] ?: 0, 0, ',', '.'); ?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr><td colspan="2" class="text-muted">Belum ada data pendapatan layanan.</td></tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="mt-4 text-center">
        <a href="dashboard.php" class="btn btn-primary">Kembali ke Dashboard</a>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
function rupiah(value){
    const n = Number(value || 0);
    return 'Rp ' + n.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
}

// Revenue Chart
const revenueCtx = document.getElementById('revenueChart').getContext('2d');
new Chart(revenueCtx, {
    type: 'line',
    data: {
        labels: ['Jan','Feb','Mar','Apr','Mei','Jun','Jul','Agu','Sep','Okt','Nov','Des'],
        datasets: [{
            label: 'Pendapatan (Rp)',
            data: [<?php echo implode(', ', $monthlyData); ?>],
            borderColor: '#0d6efd',
            backgroundColor: 'rgba(13, 110, 253, 0.1)',
            borderWidth: 2,
            fill: true,
            tension: 0.4
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: { legend: { display: true } },
        scales: {
            y: {
                beginAtZero: true,
                ticks: { callback: (v) => rupiah(v) }
            }
        }
    }
});

// Status Chart
const statusCtx = document.getElementById('statusChart').getContext('2d');

const statusColorMap = {
    pending:   '#ffc107',
    confirmed: '#28a745',
    cancelled: '#dc3545',
    completed: '#17a2b8'
};

const statusRaw = [
<?php
$raw = [];
$label = [];
$count = [];
foreach ($status_distribution as $row) {
    $st = strtolower($row['status']);
    $raw[]   = "'" . $st . "'";
    $label[] = "'" . ucfirst($st) . "'";
    $count[] = (int)$row['count'];
}
echo implode(', ', $raw);
?>
];

const statusLabels = [<?php echo implode(', ', $label); ?>];
const statusCounts = [<?php echo implode(', ', $count); ?>];
const statusColors = statusRaw.map(s => statusColorMap[s] || '#6c757d');

new Chart(statusCtx, {
    type: 'doughnut',
    data: {
        labels: statusLabels,
        datasets: [{
            data: statusCounts,
            backgroundColor: statusColors,
            borderWidth: 1
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: { legend: { position: 'bottom' } }
    }
});

// Top Services Chart
const servicesCtx = document.getElementById('servicesChart').getContext('2d');
new Chart(servicesCtx, {
    type: 'bar',
    data: {
        labels: [
            <?php
            $serviceLabels = [];
            $serviceData = [];
            foreach ($top_services as $s) {
                $serviceLabels[] = "'" . addslashes($s['name']) . "'";
                $serviceData[] = (int)$s['reservation_count'];
            }
            echo implode(', ', $serviceLabels);
            ?>
        ],
        datasets: [{
            label: 'Jumlah Reservasi',
            data: [<?php echo implode(', ', $serviceData); ?>],
            backgroundColor: 'rgba(54, 162, 235, 0.5)',
            borderColor: 'rgba(54, 162, 235, 1)',
            borderWidth: 1
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        scales: { y: { beginAtZero: true } }
    }
});

// Revenue by Service Chart
const revenueServiceCtx = document.getElementById('revenueServiceChart').getContext('2d');
new Chart(revenueServiceCtx, {
    type: 'bar',
    data: {
        labels: [
            <?php
            $revLabels = [];
            $revData = [];
            foreach ($revenue_by_service as $s) {
                $revLabels[] = "'" . addslashes($s['name']) . "'";
                $revData[] = (float)($s['revenue'] ?: 0);
            }
            echo implode(', ', $revLabels);
            ?>
        ],
        datasets: [{
            label: 'Pendapatan (Rp)',
            data: [<?php echo implode(', ', $revData); ?>],
            backgroundColor: 'rgba(75, 192, 192, 0.5)',
            borderColor: 'rgba(75, 192, 192, 1)',
            borderWidth: 1
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        scales: {
            y: { beginAtZero: true, ticks: { callback: (v) => rupiah(v) } }
        }
    }
});

// Revenue Breakdown
const revenueBreakdownCtx = document.getElementById('revenueBreakdownChart').getContext('2d');
new Chart(revenueBreakdownCtx, {
    type: 'pie',
    data: {
        labels: ['Pendapatan Layanan', 'Pendapatan Hotel'],
        datasets: [{
            data: [<?php echo (float)$service_revenue; ?>, <?php echo (float)$hotel_revenue; ?>],
            backgroundColor: ['#28a745', '#ffc107'],
            borderWidth: 1
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: { legend: { position: 'bottom' } }
    }
});
</script>
</body>
</html>
