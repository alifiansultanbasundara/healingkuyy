<?php
// File: admin/manage_cancellations.php
include '../includes/config.php';
include '../includes/auth.php';

if (!isLoggedIn() || !isAdmin()) {
    redirect('../login.php');
}

$error = '';
$success = '';

// Handle form actions
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['approve_cancellation'])) {
        $cancellation_id = sanitize($_POST['cancellation_id']);
        $admin_notes = sanitize($_POST['admin_notes']);
        $refund_amount = sanitize($_POST['refund_amount']);

        // Update status cancellation menjadi approved
        $stmt = $pdo->prepare("UPDATE cancellations SET status = 'approved', admin_notes = ?, refund_amount = ?, processed_at = NOW() WHERE id = ?");
        if ($stmt->execute([$admin_notes, $refund_amount, $cancellation_id])) {
            // Update status reservasi menjadi cancelled
            $stmt2 = $pdo->prepare("UPDATE reservations SET status = 'cancelled' WHERE id = (SELECT reservation_id FROM cancellations WHERE id = ?)");
            $stmt2->execute([$cancellation_id]);
            $success = "Pembatalan telah disetujui.";
            logActivity('approve_cancellation', 'Approved cancellation ID: ' . $cancellation_id);
        } else {
            $error = "Terjadi kesalahan saat menyetujui pembatalan.";
        }
    } elseif (isset($_POST['reject_cancellation'])) {
        $cancellation_id = sanitize($_POST['cancellation_id']);
        $admin_notes = sanitize($_POST['admin_notes']);

        // Update status cancellation menjadi rejected
        $stmt = $pdo->prepare("UPDATE cancellations SET status = 'rejected', admin_notes = ?, processed_at = NOW() WHERE id = ?");
        if ($stmt->execute([$admin_notes, $cancellation_id])) {
            $success = "Pembatalan telah ditolak.";
            logActivity('reject_cancellation', 'Rejected cancellation ID: ' . $cancellation_id);
        } else {
            $error = "Terjadi kesalahan saat menolak pembatalan.";
        }
    }
}

// Ambil semua pengajuan pembatalan
$cancellations = $pdo->query("
    SELECT c.*, r.booking_code, r.reservation_date, r.guests, r.total_price, 
           u.name as user_name, u.email, s.name as service_name
    FROM cancellations c
    JOIN reservations r ON c.reservation_id = r.id
    JOIN users u ON r.user_id = u.id
    JOIN services s ON r.service_id = s.id
    ORDER BY c.created_at DESC
")->fetchAll();
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manajemen Pembatalan - HealingKuy!.id</title>
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
        <h2 class="mb-4">Manajemen Pembatalan</h2>

        <?php if($error): ?>
        <div class="alert alert-danger"><?php echo $error; ?></div>
        <?php endif; ?>
        
        <?php if($success): ?>
        <div class="alert alert-success"><?php echo $success; ?></div>
        <?php endif; ?>

        <!-- Daftar Pengajuan Pembatalan -->
        <div class="card">
            <div class="card-header bg-light">
                <h5 class="mb-0"><i class="fas fa-times-circle me-2"></i>Pengajuan Pembatalan</h5>
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
                                <th>Alasan</th>
                                <th>Status</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach($cancellations as $index => $cancellation): ?>
                            <tr>
                                <td><?php echo $index + 1; ?></td>
                                <td><?php echo $cancellation['booking_code']; ?></td>
                                <td>
                                    <div>
                                        <strong><?php echo $cancellation['user_name']; ?></strong>
                                        <br>
                                        <small class="text-muted"><?php echo $cancellation['email']; ?></small>
                                    </div>
                                </td>
                                <td><?php echo $cancellation['service_name']; ?></td>
                                <td><?php echo date('d/m/Y', strtotime($cancellation['reservation_date'])); ?></td>
                                <td><?php echo $cancellation['reason']; ?></td>
                                <td>
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
                                        $status_text = [
                                            'approved' => 'Disetujui',
                                            'pending' => 'Menunggu',
                                            'rejected' => 'Ditolak'
                                        ];
                                        echo $status_text[$cancellation['status']] ?? ucfirst($cancellation['status']); 
                                        ?>
                                    </span>
                                </td>
                                <td>
                                    <?php if($cancellation['status'] == 'pending'): ?>
                                    <div class="btn-group btn-group-sm">
                                        <!-- Approve Button -->
                                        <button type="button" class="btn btn-outline-success" data-bs-toggle="modal" data-bs-target="#approveModal<?php echo $cancellation['id']; ?>">
                                            <i class="fas fa-check"></i>
                                        </button>
                                        <!-- Reject Button -->
                                        <button type="button" class="btn btn-outline-danger" data-bs-toggle="modal" data-bs-target="#rejectModal<?php echo $cancellation['id']; ?>">
                                            <i class="fas fa-times"></i>
                                        </button>
                                    </div>

                                    <!-- Approve Modal -->
                                    <div class="modal fade" id="approveModal<?php echo $cancellation['id']; ?>" tabindex="-1">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title">Setujui Pembatalan</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                </div>
                                                <form method="POST">
                                                    <div class="modal-body">
                                                        <input type="hidden" name="cancellation_id" value="<?php echo $cancellation['id']; ?>">
                                                        <p>Anda akan menyetujui pembatalan untuk:</p>
                                                        <ul>
                                                            <li>Kode Booking: <strong><?php echo $cancellation['booking_code']; ?></strong></li>
                                                            <li>User: <strong><?php echo $cancellation['user_name']; ?></strong></li>
                                                            <li>Layanan: <strong><?php echo $cancellation['service_name']; ?></strong></li>
                                                            <li>Total Biaya: <strong>Rp <?php echo number_format($cancellation['total_price'], 0, ',', '.'); ?></strong></li>
                                                        </ul>
                                                        <div class="mb-3">
                                                            <label for="refund_amount<?php echo $cancellation['id']; ?>" class="form-label">Jumlah Pengembalian (Rp)</label>
                                                            <input type="number" class="form-control" id="refund_amount<?php echo $cancellation['id']; ?>" name="refund_amount" value="<?php echo $cancellation['total_price'] * 0.9; ?>" required>
                                                            <div class="form-text">Biaya administrasi 10% telah dikurangi.</div>
                                                        </div>
                                                        <div class="mb-3">
                                                            <label for="admin_notes<?php echo $cancellation['id']; ?>" class="form-label">Catatan Admin</label>
                                                            <textarea class="form-control" id="admin_notes<?php echo $cancellation['id']; ?>" name="admin_notes" rows="3" placeholder="Berikan catatan untuk user..."></textarea>
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                                        <button type="submit" name="approve_cancellation" class="btn btn-success">Setujui Pembatalan</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Reject Modal -->
                                    <div class="modal fade" id="rejectModal<?php echo $cancellation['id']; ?>" tabindex="-1">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title">Tolak Pembatalan</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                </div>
                                                <form method="POST">
                                                    <div class="modal-body">
                                                        <input type="hidden" name="cancellation_id" value="<?php echo $cancellation['id']; ?>">
                                                        <p>Anda akan menolak pembatalan untuk:</p>
                                                        <ul>
                                                            <li>Kode Booking: <strong><?php echo $cancellation['booking_code']; ?></strong></li>
                                                            <li>User: <strong><?php echo $cancellation['user_name']; ?></strong></li>
                                                            <li>Layanan: <strong><?php echo $cancellation['service_name']; ?></strong></li>
                                                        </ul>
                                                        <div class="mb-3">
                                                            <label for="admin_notes<?php echo $cancellation['id']; ?>" class="form-label">Alasan Penolakan</label>
                                                            <textarea class="form-control" id="admin_notes<?php echo $cancellation['id']; ?>" name="admin_notes" rows="3" placeholder="Berikan alasan penolakan..." required></textarea>
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                                        <button type="submit" name="reject_cancellation" class="btn btn-danger">Tolak Pembatalan</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                    <?php else: ?>
                                        <span class="text-muted">Tidak ada aksi</span>
                                    <?php endif; ?>
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