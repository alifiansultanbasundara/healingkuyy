<?php
// File: admin/manage_reservations.php
include '../includes/config.php';
include '../includes/auth.php';

if (!isLoggedIn() || !isAdmin()) {
    redirect('../login.php');
}

$error = '';
$success = '';

// Handle form actions
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['update_reservation'])) {
        $reservation_id = sanitize($_POST['reservation_id']);
        $status = sanitize($_POST['status']);

        $stmt = $pdo->prepare("UPDATE reservations SET status = ? WHERE id = ?");
        if ($stmt->execute([$status, $reservation_id])) {
            $success = "Status reservasi berhasil diperbarui!";
            logActivity('update_reservation', 'Updated reservation ID: ' . $reservation_id . ' to ' . $status);
        } else {
            $error = "Terjadi kesalahan saat memperbarui reservasi.";
        }
    }
}

// Ambil semua reservasi
$reservations = $pdo->query("
    SELECT r.*, u.name as user_name, u.email, s.name as service_name, s.location
    FROM reservations r
    JOIN users u ON r.user_id = u.id
    JOIN services s ON r.service_id = s.id
    ORDER BY r.created_at DESC
")->fetchAll();
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manajemen Reservasi - HealingKuy!.id</title>
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
        <h2 class="mb-4">Manajemen Reservasi</h2>

        <?php if($error): ?>
        <div class="alert alert-danger"><?php echo $error; ?></div>
        <?php endif; ?>
        
        <?php if($success): ?>
        <div class="alert alert-success"><?php echo $success; ?></div>
        <?php endif; ?>

        <!-- Daftar Reservasi -->
        <div class="card">
            <div class="card-header bg-light">
                <h5 class="mb-0"><i class="fas fa-calendar-alt me-2"></i>Daftar Reservasi</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Kode Booking</th>
                                <th>User</th>
                                <th>Layanan</th>
                                <th>Tanggal</th>
                                <th>Jumlah</th>
                                <th>Total</th>
                                <th>Status</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach($reservations as $index => $reservation): ?>
                            <tr>
                                <td><?php echo $index + 1; ?></td>
                                <td>
                                    <strong><?php echo $reservation['booking_code']; ?></strong>
                                </td>
                                <td>
                                    <div>
                                        <strong><?php echo $reservation['user_name']; ?></strong>
                                        <br>
                                        <small class="text-muted"><?php echo $reservation['email']; ?></small>
                                    </div>
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
                                        <!-- Edit Button -->
                                        <button type="button" class="btn btn-outline-primary" data-bs-toggle="modal" data-bs-target="#editReservationModal<?php echo $reservation['id']; ?>">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                    </div>

                                    <!-- Edit Reservation Modal -->
                                    <div class="modal fade" id="editReservationModal<?php echo $reservation['id']; ?>" tabindex="-1">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title">Edit Reservasi</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                </div>
                                                <form method="POST">
                                                    <div class="modal-body">
                                                        <input type="hidden" name="reservation_id" value="<?php echo $reservation['id']; ?>">
                                                        <div class="mb-3">
                                                            <label class="form-label">Kode Booking</label>
                                                            <input type="text" class="form-control" value="<?php echo $reservation['booking_code']; ?>" disabled>
                                                        </div>
                                                        <div class="mb-3">
                                                            <label class="form-label">User</label>
                                                            <input type="text" class="form-control" value="<?php echo $reservation['user_name']; ?>" disabled>
                                                        </div>
                                                        <div class="mb-3">
                                                            <label class="form-label">Layanan</label>
                                                            <input type="text" class="form-control" value="<?php echo $reservation['service_name']; ?>" disabled>
                                                        </div>
                                                        <div class="mb-3">
                                                            <label for="status<?php echo $reservation['id']; ?>" class="form-label">Status</label>
                                                            <select class="form-control" id="status<?php echo $reservation['id']; ?>" name="status" required>
                                                                <option value="pending" <?php echo $reservation['status'] == 'pending' ? 'selected' : ''; ?>>Menunggu</option>
                                                                <option value="confirmed" <?php echo $reservation['status'] == 'confirmed' ? 'selected' : ''; ?>>Dikonfirmasi</option>
                                                                <option value="cancelled" <?php echo $reservation['status'] == 'cancelled' ? 'selected' : ''; ?>>Dibatalkan</option>
                                                                <option value="completed" <?php echo $reservation['status'] == 'completed' ? 'selected' : ''; ?>>Selesai</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                                        <button type="submit" name="update_reservation" class="btn btn-primary">Simpan Perubahan</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
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

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>