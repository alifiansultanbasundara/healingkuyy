<?php
// File: user/profile.php
include '../includes/config.php';
include '../includes/auth.php';

if (!isLoggedIn() || isAdmin()) {
    redirect('../login.php');
}

// Ambil data user
$stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
$stmt->execute([$_SESSION['user_id']]);
$user = $stmt->fetch();

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = sanitize($_POST['name']);
    $phone = sanitize($_POST['phone']);
    $address = sanitize($_POST['address']);

    // Validasi
    if (empty($name)) {
        $error = "Nama wajib diisi.";
    } else {
        if (updateProfile($_SESSION['user_id'], $name, $phone, $address)) {
            $success = "Profil berhasil diperbarui.";
            // Refresh data user
            $stmt->execute([$_SESSION['user_id']]);
            $user = $stmt->fetch();
        } else {
            $error = "Terjadi kesalahan saat memperbarui profil.";
        }
    }
}

// Hitung statistik user
$reservation_stats = $pdo->prepare("
    SELECT 
        COUNT(*) as total,
        SUM(CASE WHEN status = 'completed' THEN 1 ELSE 0 END) as completed,
        SUM(CASE WHEN status = 'cancelled' THEN 1 ELSE 0 END) as cancelled
    FROM reservations 
    WHERE user_id = ?
");
$reservation_stats->execute([$_SESSION['user_id']]);
$stats = $reservation_stats->fetch();
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profil User - HealingKuy!.id</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        .profile-card {
            border: none;
            border-radius: 15px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        }
        .profile-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 3rem 0;
            border-radius: 15px 15px 0 0;
        }
        .avatar {
            width: 120px;
            height: 120px;
            background: white;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #667eea;
            font-size: 3rem;
            margin: 0 auto;
        }
        .stat-badge {
            font-size: 0.8rem;
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
            <div class="col-md-10">
                <div class="card profile-card">
                    <!-- Profile Header -->
                    <div class="profile-header text-center">
                        <div class="avatar mb-3">
                            <i class="fas fa-user"></i>
                        </div>
                        <h3><?php echo $user['name']; ?></h3>
                        <p class="mb-0">Member sejak <?php echo date('F Y', strtotime($user['created_at'])); ?></p>
                    </div>

                    <div class="card-body p-4">
                        <?php if($error): ?>
                        <div class="alert alert-danger"><?php echo $error; ?></div>
                        <?php endif; ?>
                        
                        <?php if($success): ?>
                        <div class="alert alert-success"><?php echo $success; ?></div>
                        <?php endif; ?>

                        <div class="row">
                            <!-- Form Edit Profil -->
                            <div class="col-md-8">
                                <h4 class="mb-4">Edit Profil</h4>
                                <form method="POST">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label for="name" class="form-label">Nama Lengkap</label>
                                                <input type="text" class="form-control" id="name" name="name" 
                                                       value="<?php echo $user['name']; ?>" required>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label for="email" class="form-label">Email</label>
                                                <input type="email" class="form-control" id="email" 
                                                       value="<?php echo $user['email']; ?>" disabled>
                                                <div class="form-text">Email tidak dapat diubah.</div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label for="phone" class="form-label">Nomor Telepon</label>
                                                <input type="text" class="form-control" id="phone" name="phone" 
                                                       value="<?php echo $user['phone'] ?? ''; ?>" 
                                                       placeholder="Contoh: 081234567890">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label for="role" class="form-label">Role</label>
                                                <input type="text" class="form-control" id="role" 
                                                       value="<?php echo $user['role'] == 'user' ? 'User' : 'Admin'; ?>" disabled>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="mb-4">
                                        <label for="address" class="form-label">Alamat Lengkap</label>
                                        <textarea class="form-control" id="address" name="address" rows="3" 
                                                  placeholder="Masukkan alamat lengkap Anda"><?php echo $user['address'] ?? ''; ?></textarea>
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label">Tanggal Bergabung</label>
                                        <input type="text" class="form-control" 
                                               value="<?php echo date('d F Y', strtotime($user['created_at'])); ?>" disabled>
                                    </div>

                                    <button type="submit" class="btn btn-primary btn-lg w-100 py-3">
                                        <i class="fas fa-save me-2"></i>Simpan Perubahan
                                    </button>
                                </form>
                            </div>

                            <!-- Statistik & Info -->
                            <div class="col-md-4">
                                <!-- Statistik Cepat -->
                                <div class="card mb-4">
                                    <div class="card-header bg-light">
                                        <h6 class="mb-0"><i class="fas fa-chart-bar me-2"></i>Statistik</h6>
                                    </div>
                                    <div class="card-body">
                                        <div class="d-flex justify-content-between align-items-center mb-3">
                                            <span>Total Reservasi</span>
                                            <span class="badge bg-primary stat-badge"><?php echo $stats['total']; ?></span>
                                        </div>
                                        <div class="d-flex justify-content-between align-items-center mb-3">
                                            <span>Selesai</span>
                                            <span class="badge bg-success stat-badge"><?php echo $stats['completed']; ?></span>
                                        </div>
                                        <div class="d-flex justify-content-between align-items-center">
                                            <span>Dibatalkan</span>
                                            <span class="badge bg-danger stat-badge"><?php echo $stats['cancelled']; ?></span>
                                        </div>
                                    </div>
                                </div>

                                <!-- Informasi Akun -->
                                <div class="card mb-4">
                                    <div class="card-header bg-light">
                                        <h6 class="mb-0"><i class="fas fa-info-circle me-2"></i>Informasi Akun</h6>
                                    </div>
                                    <div class="card-body">
                                        <div class="mb-2">
                                            <small class="text-muted">Status Akun</small>
                                            <div>
                                                <span class="badge bg-success">Aktif</span>
                                            </div>
                                        </div>
                                        <div class="mb-2">
                                            <small class="text-muted">Terakhir Login</small>
                                            <div>
                                                <small><?php echo date('d M Y H:i'); ?></small>
                                            </div>
                                        </div>
                                        <div>
                                            <small class="text-muted">ID Pengguna</small>
                                            <div>
                                                <small class="text-muted">#<?php echo $user['id']; ?></small>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Keamanan -->
                                <div class="card">
                                    <div class="card-header bg-light">
                                        <h6 class="mb-0"><i class="fas fa-shield-alt me-2"></i>Keamanan</h6>
                                    </div>
                                    <div class="card-body">
                                        <p class="small text-muted mb-3">
                                            Untuk mengubah password atau pengaturan keamanan lainnya, 
                                            silakan hubungi administrator.
                                        </p>
                                        <a href="../index.php#contact" class="btn btn-outline-primary w-100">
                                            <i class="fas fa-headset me-1"></i> Hubungi Admin
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <hr class="my-5">

                        <!-- Aktivitas Terbaru -->
                        <div class="row">
                            <div class="col-12">
                                <h5 class="mb-4">Aktivitas Terbaru</h5>
                                <?php
                                $recent_activity = $pdo->prepare("
                                    SELECT * FROM reservations 
                                    WHERE user_id = ? 
                                    ORDER BY created_at DESC 
                                    LIMIT 5
                                ");
                                $recent_activity->execute([$_SESSION['user_id']]);
                                $activities = $recent_activity->fetchAll();
                                ?>

                                <?php if(empty($activities)): ?>
                                    <div class="text-center py-4">
                                        <i class="fas fa-history fa-3x text-muted mb-3"></i>
                                        <p class="text-muted">Belum ada aktivitas reservasi</p>
                                    </div>
                                <?php else: ?>
                                    <div class="list-group">
                                        <?php foreach($activities as $activity): ?>
                                        <div class="list-group-item">
                                            <div class="d-flex justify-content-between align-items-center">
                                                <div>
                                                    <h6 class="mb-1"><?php echo $activity['booking_code']; ?></h6>
                                                    <small class="text-muted">
                                                        <?php echo date('d F Y', strtotime($activity['reservation_date'])); ?>
                                                    </small>
                                                </div>
                                                <span class="badge 
                                                    <?php 
                                                    switch($activity['status']) {
                                                        case 'confirmed': echo 'bg-success'; break;
                                                        case 'pending': echo 'bg-warning'; break;
                                                        case 'cancelled': echo 'bg-danger'; break;
                                                        case 'completed': echo 'bg-info'; break;
                                                        default: echo 'bg-secondary';
                                                    }
                                                    ?>
                                                ">
                                                    <?php echo ucfirst($activity['status']); ?>
                                                </span>
                                            </div>
                                        </div>
                                        <?php endforeach; ?>
                                    </div>
                                    <div class="text-center mt-3">
                                        <a href="dashboard.php" class="btn btn-outline-primary">
                                            Lihat Semua Aktivitas
                                        </a>
                                    </div>
                                <?php endif; ?>
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