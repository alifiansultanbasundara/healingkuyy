<?php
// File: admin/manage_users.php
include '../includes/config.php';
include '../includes/auth.php';

if (!isLoggedIn() || !isAdmin()) {
    redirect('../login.php');
}

$error = '';
$success = '';

// Handle form actions
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['add_user'])) {
        // Tambah user baru
        $name = sanitize($_POST['name']);
        $email = sanitize($_POST['email']);
        $password = $_POST['password'];
        $role = sanitize($_POST['role']);
        $phone = sanitize($_POST['phone']);
        $address = sanitize($_POST['address']);

        // Validasi
        if (empty($name) || empty($email) || empty($password)) {
            $error = "Nama, email, dan password wajib diisi!";
        } else {
            // Cek apakah email sudah ada
            $stmt = $pdo->prepare("SELECT id FROM users WHERE email = ?");
            $stmt->execute([$email]);
            if ($stmt->rowCount() > 0) {
                $error = "Email sudah terdaftar!";
            } else {
                $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
                $stmt = $pdo->prepare("INSERT INTO users (name, email, password, role, phone, address) VALUES (?, ?, ?, ?, ?, ?)");
                if ($stmt->execute([$name, $email, $hashedPassword, $role, $phone, $address])) {
                    $success = "User berhasil ditambahkan!";
                    logActivity('add_user', 'Added new user: ' . $email);
                } else {
                    $error = "Terjadi kesalahan saat menambahkan user.";
                }
            }
        }
    } elseif (isset($_POST['edit_user'])) {
        // Edit user
        $id = sanitize($_POST['id']);
        $name = sanitize($_POST['name']);
        $email = sanitize($_POST['email']);
        $role = sanitize($_POST['role']);
        $phone = sanitize($_POST['phone']);
        $address = sanitize($_POST['address']);

        // Validasi
        if (empty($name) || empty($email)) {
            $error = "Nama dan email wajib diisi!";
        } else {
            // Cek apakah email sudah ada (kecuali untuk user ini)
            $stmt = $pdo->prepare("SELECT id FROM users WHERE email = ? AND id != ?");
            $stmt->execute([$email, $id]);
            if ($stmt->rowCount() > 0) {
                $error = "Email sudah terdaftar!";
            } else {
                $stmt = $pdo->prepare("UPDATE users SET name = ?, email = ?, role = ?, phone = ?, address = ? WHERE id = ?");
                if ($stmt->execute([$name, $email, $role, $phone, $address, $id])) {
                    $success = "User berhasil diperbarui!";
                    logActivity('edit_user', 'Updated user: ' . $email);
                } else {
                    $error = "Terjadi kesalahan saat memperbarui user.";
                }
            }
        }
    } elseif (isset($_POST['delete_user'])) {
        // Hapus user
        $id = sanitize($_POST['id']);
        // Cek apakah user memiliki reservasi
        $stmt = $pdo->prepare("SELECT COUNT(*) FROM reservations WHERE user_id = ?");
        $stmt->execute([$id]);
        $reservation_count = $stmt->fetchColumn();

        if ($reservation_count > 0) {
            $error = "User tidak dapat dihapus karena memiliki riwayat reservasi.";
        } else {
            $stmt = $pdo->prepare("DELETE FROM users WHERE id = ?");
            if ($stmt->execute([$id])) {
                $success = "User berhasil dihapus!";
                logActivity('delete_user', 'Deleted user ID: ' . $id);
            } else {
                $error = "Terjadi kesalahan saat menghapus user.";
            }
        }
    }
}

// Ambil semua user
$users = $pdo->query("SELECT * FROM users ORDER BY created_at DESC")->fetchAll();
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manajemen User - HealingKuy!.id</title>
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
        <h2 class="mb-4">Manajemen User</h2>

        <?php if($error): ?>
        <div class="alert alert-danger"><?php echo $error; ?></div>
        <?php endif; ?>
        
        <?php if($success): ?>
        <div class="alert alert-success"><?php echo $success; ?></div>
        <?php endif; ?>

        <!-- Form Tambah User -->
        <div class="card mb-4">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0"><i class="fas fa-plus-circle me-2"></i>Tambah User Baru</h5>
            </div>
            <div class="card-body">
                <form method="POST">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="name" class="form-label">Nama Lengkap</label>
                                <input type="text" class="form-control" id="name" name="name" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="email" class="form-label">Email</label>
                                <input type="email" class="form-control" id="email" name="email" required>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="password" class="form-label">Password</label>
                                <input type="password" class="form-control" id="password" name="password" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="role" class="form-label">Role</label>
                                <select class="form-control" id="role" name="role" required>
                                    <option value="user">User</option>
                                    <option value="admin">Admin</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="phone" class="form-label">Telepon</label>
                                <input type="text" class="form-control" id="phone" name="phone">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="address" class="form-label">Alamat</label>
                                <textarea class="form-control" id="address" name="address" rows="1"></textarea>
                            </div>
                        </div>
                    </div>
                    <button type="submit" name="add_user" class="btn btn-primary">
                        <i class="fas fa-save me-1"></i> Simpan User
                    </button>
                </form>
            </div>
        </div>

        <!-- Daftar User -->
        <div class="card">
            <div class="card-header bg-light">
                <h5 class="mb-0"><i class="fas fa-users me-2"></i>Daftar User</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Nama</th>
                                <th>Email</th>
                                <th>Role</th>
                                <th>Telepon</th>
                                <th>Tanggal Daftar</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach($users as $index => $user): ?>
                            <tr>
                                <td><?php echo $index + 1; ?></td>
                                <td><?php echo $user['name']; ?></td>
                                <td><?php echo $user['email']; ?></td>
                                <td>
                                    <span class="badge <?php echo $user['role'] == 'admin' ? 'bg-danger' : 'bg-primary'; ?>">
                                        <?php echo $user['role']; ?>
                                    </span>
                                </td>
                                <td><?php echo $user['phone'] ?: '-'; ?></td>
                                <td><?php echo date('d/m/Y', strtotime($user['created_at'])); ?></td>
                                <td>
                                    <div class="btn-group btn-group-sm">
                                        <!-- Edit Button -->
                                        <button type="button" class="btn btn-outline-primary" data-bs-toggle="modal" data-bs-target="#editUserModal<?php echo $user['id']; ?>">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <!-- Delete Button -->
                                        <button type="button" class="btn btn-outline-danger" data-bs-toggle="modal" data-bs-target="#deleteUserModal<?php echo $user['id']; ?>">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>

                                    <!-- Edit User Modal -->
                                    <div class="modal fade" id="editUserModal<?php echo $user['id']; ?>" tabindex="-1">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title">Edit User</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                </div>
                                                <form method="POST">
                                                    <div class="modal-body">
                                                        <input type="hidden" name="id" value="<?php echo $user['id']; ?>">
                                                        <div class="mb-3">
                                                            <label for="name<?php echo $user['id']; ?>" class="form-label">Nama</label>
                                                            <input type="text" class="form-control" id="name<?php echo $user['id']; ?>" name="name" value="<?php echo $user['name']; ?>" required>
                                                        </div>
                                                        <div class="mb-3">
                                                            <label for="email<?php echo $user['id']; ?>" class="form-label">Email</label>
                                                            <input type="email" class="form-control" id="email<?php echo $user['id']; ?>" name="email" value="<?php echo $user['email']; ?>" required>
                                                        </div>
                                                        <div class="mb-3">
                                                            <label for="role<?php echo $user['id']; ?>" class="form-label">Role</label>
                                                            <select class="form-control" id="role<?php echo $user['id']; ?>" name="role" required>
                                                                <option value="user" <?php echo $user['role'] == 'user' ? 'selected' : ''; ?>>User</option>
                                                                <option value="admin" <?php echo $user['role'] == 'admin' ? 'selected' : ''; ?>>Admin</option>
                                                            </select>
                                                        </div>
                                                        <div class="mb-3">
                                                            <label for="phone<?php echo $user['id']; ?>" class="form-label">Telepon</label>
                                                            <input type="text" class="form-control" id="phone<?php echo $user['id']; ?>" name="phone" value="<?php echo $user['phone'] ?? ''; ?>">
                                                        </div>
                                                        <div class="mb-3">
                                                            <label for="address<?php echo $user['id']; ?>" class="form-label">Alamat</label>
                                                            <textarea class="form-control" id="address<?php echo $user['id']; ?>" name="address" rows="2"><?php echo $user['address'] ?? ''; ?></textarea>
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                                        <button type="submit" name="edit_user" class="btn btn-primary">Simpan Perubahan</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Delete User Modal -->
                                    <div class="modal fade" id="deleteUserModal<?php echo $user['id']; ?>" tabindex="-1">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title">Hapus User</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <p>Apakah Anda yakin ingin menghapus user <strong><?php echo $user['name']; ?></strong>?</p>
                                                    <p class="text-danger">Note: User yang memiliki riwayat reservasi tidak dapat dihapus.</p>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                                    <form method="POST" style="display: inline;">
                                                        <input type="hidden" name="id" value="<?php echo $user['id']; ?>">
                                                        <button type="submit" name="delete_user" class="btn btn-danger">Hapus</button>
                                                    </form>
                                                </div>
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