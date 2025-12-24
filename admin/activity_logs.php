<?php
// File: admin/activity_logs.php
include '../includes/config.php';
include '../includes/auth.php';

if (!isLoggedIn() || !isAdmin()) {
    redirect('../login.php');
}

// Ambil semua log aktivitas
$logs = $pdo->query("
    SELECT a.*, u.name as user_name 
    FROM activity_logs a 
    JOIN users u ON a.user_id = u.id 
    ORDER BY a.created_at DESC
")->fetchAll();
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Log Aktivitas - HealingKuy!.id</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        .card {
            border: none;
            border-radius: 15px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        }
        .table th {
            border-top: none;
            font-weight: 600;
        }
        .log-action {
            font-weight: 500;
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
        <h2 class="mb-4">Log Aktivitas Sistem</h2>

        <!-- Log Aktivitas -->
        <div class="card">
            <div class="card-header bg-light">
                <h5 class="mb-0"><i class="fas fa-history me-2"></i>Riwayat Aktivitas</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>User</th>
                                <th>Aksi</th>
                                <th>Deskripsi</th>
                                <th>IP Address</th>
                                <th>Waktu</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach($logs as $index => $log): ?>
                            <tr>
                                <td><?php echo $index + 1; ?></td>
                                <td>
                                    <div>
                                        <strong><?php echo $log['user_name']; ?></strong>
                                        <br>
                                        <small class="text-muted">ID: <?php echo $log['user_id']; ?></small>
                                    </div>
                                </td>
                                <td>
                                    <span class="log-action 
                                        <?php 
                                        // Beri warna berdasarkan jenis aksi
                                        if (strpos($log['action'], 'delete') !== false) {
                                            echo 'text-danger';
                                        } elseif (strpos($log['action'], 'add') !== false || strpos($log['action'], 'create') !== false) {
                                            echo 'text-success';
                                        } elseif (strpos($log['action'], 'edit') !== false || strpos($log['action'], 'update') !== false) {
                                            echo 'text-warning';
                                        } else {
                                            echo 'text-primary';
                                        }
                                        ?>
                                    ">
                                        <?php echo ucwords(str_replace('_', ' ', $log['action'])); ?>
                                    </span>
                                </td>
                                <td><?php echo $log['description']; ?></td>
                                <td>
                                    <code><?php echo $log['ip_address']; ?></code>
                                </td>
                                <td>
                                    <?php echo date('d/m/Y H:i', strtotime($log['created_at'])); ?>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>