<?php
// File: admin/manage_services.php
include '../includes/config.php';
include '../includes/auth.php';

if (!isLoggedIn() || !isAdmin()) {
    redirect('../login.php');
}

$error = '';
$success = '';

// Handle form actions
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['add_service'])) {
        // Tambah layanan baru
        $name = sanitize($_POST['name']);
        $type = sanitize($_POST['type']);
        $location = sanitize($_POST['location']);
        $description = sanitize($_POST['description']);
        $price = sanitize($_POST['price']);
        $capacity = sanitize($_POST['capacity']);

        // Handle image upload
        $image = '';
        if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
            $allowed = ['jpg', 'jpeg', 'png', 'gif'];
            $filename = $_FILES['image']['name'];
            $ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
            if (in_array($ext, $allowed) && $_FILES['image']['size'] <= 2097152) { // 2MB max
                $newname = uniqid() . '.' . $ext;
                $destination = '../assets/images/' . $newname;
                if (move_uploaded_file($_FILES['image']['tmp_name'], $destination)) {
                    $image = $newname;
                } else {
                    $error = "Gagal mengupload gambar.";
                }
            } else {
                $error = "Format gambar tidak valid atau ukuran terlalu besar (max 2MB).";
            }
        }

        // Validasi
        if (empty($name) || empty($type) || empty($location) || empty($price) || empty($capacity)) {
            $error = "Semua field wajib diisi!";
        } elseif (!empty($error)) {
            // Error from image upload
        } else {
            $stmt = $pdo->prepare("INSERT INTO services (name, type, location, description, price, capacity, image) VALUES (?, ?, ?, ?, ?, ?, ?)");
            if ($stmt->execute([$name, $type, $location, $description, $price, $capacity, $image])) {
                $success = "Layanan berhasil ditambahkan!";
                logActivity('add_service', 'Added new service: ' . $name);
            } else {
                $error = "Terjadi kesalahan saat menambahkan layanan.";
            }
        }
    } elseif (isset($_POST['edit_service'])) {
        // Edit layanan
        $id = sanitize($_POST['id']);
        $name = sanitize($_POST['name']);
        $type = sanitize($_POST['type']);
        $location = sanitize($_POST['location']);
        $description = sanitize($_POST['description']);
        $price = sanitize($_POST['price']);
        $capacity = sanitize($_POST['capacity']);
        $status = sanitize($_POST['status']);

        // Handle image upload for edit
        $image_update = '';
        if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
            $allowed = ['jpg', 'jpeg', 'png', 'gif'];
            $filename = $_FILES['image']['name'];
            $ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
            if (in_array($ext, $allowed) && $_FILES['image']['size'] <= 2097152) { // 2MB max
                $newname = uniqid() . '.' . $ext;
                $destination = '../assets/images/' . $newname;
                if (move_uploaded_file($_FILES['image']['tmp_name'], $destination)) {
                    $image_update = ", image = '$newname'";
                } else {
                    $error = "Gagal mengupload gambar.";
                }
            } else {
                $error = "Format gambar tidak valid atau ukuran terlalu besar (max 2MB).";
            }
        }

        // Validasi
        if (empty($name) || empty($type) || empty($location) || empty($price) || empty($capacity)) {
            $error = "Semua field wajib diisi!";
        } elseif (!empty($error)) {
            // Error from image upload
        } else {
            $stmt = $pdo->prepare("UPDATE services SET name = ?, type = ?, location = ?, description = ?, price = ?, capacity = ?, status = ? $image_update WHERE id = ?");
            if ($stmt->execute([$name, $type, $location, $description, $price, $capacity, $status, $id])) {
                $success = "Layanan berhasil diperbarui!";
                logActivity('edit_service', 'Updated service: ' . $name);
            } else {
                $error = "Terjadi kesalahan saat memperbarui layanan.";
            }
        }
    } elseif (isset($_POST['delete_service'])) {
        // Hapus layanan
        $id = sanitize($_POST['id']);
        // Cek apakah layanan memiliki reservasi
        $stmt = $pdo->prepare("SELECT COUNT(*) FROM reservations WHERE service_id = ?");
        $stmt->execute([$id]);
        $reservation_count = $stmt->fetchColumn();

        if ($reservation_count > 0) {
            $error = "Layanan tidak dapat dihapus karena memiliki riwayat reservasi.";
        } else {
            $stmt = $pdo->prepare("DELETE FROM services WHERE id = ?");
            if ($stmt->execute([$id])) {
                $success = "Layanan berhasil dihapus!";
                logActivity('delete_service', 'Deleted service ID: ' . $id);
            } else {
                $error = "Terjadi kesalahan saat menghapus layanan.";
            }
        }
    } elseif (isset($_POST['add_hotel'])) {
        // Tambah hotel baru
        $hotel_name = sanitize($_POST['hotel_name']);
        $service_id = sanitize($_POST['service_id']);
        $price_per_night = sanitize($_POST['price_per_night']);
        $hotel_description = sanitize($_POST['hotel_description']);

        // Handle image upload
        $image = '';
        if (isset($_FILES['hotel_image']) && $_FILES['hotel_image']['error'] == 0) {
            $allowed = ['jpg', 'jpeg', 'png', 'gif'];
            $filename = $_FILES['hotel_image']['name'];
            $ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
            if (in_array($ext, $allowed) && $_FILES['hotel_image']['size'] <= 2097152) { // 2MB max
                $newname = uniqid() . '.' . $ext;
                $destination = '../assets/images/' . $newname;
                if (move_uploaded_file($_FILES['hotel_image']['tmp_name'], $destination)) {
                    $image = $newname;
                } else {
                    $error = "Gagal mengupload gambar hotel.";
                }
            } else {
                $error = "Format gambar tidak valid atau ukuran terlalu besar (max 2MB).";
            }
        }

        // Validasi
        if (empty($hotel_name) || empty($service_id) || empty($price_per_night)) {
            $error = "Semua field wajib diisi!";
        } elseif (!empty($error)) {
            // Error from image upload
        } else {
            $stmt = $pdo->prepare("INSERT INTO hotels (service_id, name, description, price_per_night, image) VALUES (?, ?, ?, ?, ?)");
            if ($stmt->execute([$service_id, $hotel_name, $hotel_description, $price_per_night, $image])) {
                $success = "Hotel berhasil ditambahkan!";
                logActivity('add_hotel', 'Added new hotel: ' . $hotel_name);
            } else {
                $error = "Terjadi kesalahan saat menambahkan hotel.";
            }
        }
    } elseif (isset($_POST['edit_hotel'])) {
        // Edit hotel
        $id = sanitize($_POST['id']);
        $hotel_name = sanitize($_POST['hotel_name']);
        $service_id = sanitize($_POST['service_id']);
        $price_per_night = sanitize($_POST['price_per_night']);
        $status = sanitize($_POST['status']);
        $hotel_description = sanitize($_POST['hotel_description']);

        // Handle image upload for edit
        $image_update = '';
        if (isset($_FILES['hotel_image']) && $_FILES['hotel_image']['error'] == 0) {
            $allowed = ['jpg', 'jpeg', 'png', 'gif'];
            $filename = $_FILES['hotel_image']['name'];
            $ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
            if (in_array($ext, $allowed) && $_FILES['hotel_image']['size'] <= 2097152) { // 2MB max
                $newname = uniqid() . '.' . $ext;
                $destination = '../assets/images/' . $newname;
                if (move_uploaded_file($_FILES['hotel_image']['tmp_name'], $destination)) {
                    $image_update = ", image = '$newname'";
                } else {
                    $error = "Gagal mengupload gambar hotel.";
                }
            } else {
                $error = "Format gambar tidak valid atau ukuran terlalu besar (max 2MB).";
            }
        }

        // Validasi
        if (empty($hotel_name) || empty($service_id) || empty($price_per_night)) {
            $error = "Semua field wajib diisi!";
        } elseif (!empty($error)) {
            // Error from image upload
        } else {
            $stmt = $pdo->prepare("UPDATE hotels SET service_id = ?, name = ?, description = ?, price_per_night = ?, status = ? $image_update WHERE id = ?");
            if ($stmt->execute([$service_id, $hotel_name, $hotel_description, $price_per_night, $status, $id])) {
                $success = "Hotel berhasil diperbarui!";
                logActivity('edit_hotel', 'Updated hotel: ' . $hotel_name);
            } else {
                $error = "Terjadi kesalahan saat memperbarui hotel.";
            }
        }
    } elseif (isset($_POST['delete_hotel'])) {
        // Hapus hotel
        $id = sanitize($_POST['id']);
        // Cek apakah hotel memiliki reservasi
        $stmt = $pdo->prepare("SELECT COUNT(*) FROM reservations WHERE hotel_id = ?");
        $stmt->execute([$id]);
        $reservation_count = $stmt->fetchColumn();

        if ($reservation_count > 0) {
            $error = "Hotel tidak dapat dihapus karena memiliki riwayat reservasi.";
        } else {
            $stmt = $pdo->prepare("DELETE FROM hotels WHERE id = ?");
            if ($stmt->execute([$id])) {
                $success = "Hotel berhasil dihapus!";
                logActivity('delete_hotel', 'Deleted hotel ID: ' . $id);
            } else {
                $error = "Terjadi kesalahan saat menghapus hotel.";
            }
        }
    }
}

// Ambil semua layanan
$services = $pdo->query("SELECT * FROM services ORDER BY created_at DESC")->fetchAll();

// Ambil semua hotel
$hotels = $pdo->query("
    SELECT h.*, s.name as service_name 
    FROM hotels h 
    JOIN services s ON h.service_id = s.id 
    ORDER BY h.created_at DESC
")->fetchAll();
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manajemen Layanan - HealingKuy!.id</title>
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
        <h2 class="mb-4">Manajemen Layanan</h2>

        <?php if($error): ?>
        <div class="alert alert-danger"><?php echo $error; ?></div>
        <?php endif; ?>
        
        <?php if($success): ?>
        <div class="alert alert-success"><?php echo $success; ?></div>
        <?php endif; ?>

        <!-- Form Tambah Layanan -->
        <div class="card mb-4">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0"><i class="fas fa-plus-circle me-2"></i>Tambah Layanan Baru</h5>
            </div>
            <div class="card-body">
                <form method="POST" enctype="multipart/form-data">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="name" class="form-label">Nama Layanan</label>
                                <input type="text" class="form-control" id="name" name="name" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="type" class="form-label">Jenis</label>
                                <select class="form-control" id="type" name="type" required>
                                    <option value="alam">Wisata Alam</option>
                                    <option value="buatan">Wisata Buatan</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="location" class="form-label">Lokasi</label>
                                <input type="text" class="form-control" id="location" name="location" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="capacity" class="form-label">Kapasitas Maksimal</label>
                                <input type="number" class="form-control" id="capacity" name="capacity" required>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="price" class="form-label">Harga per Orang (Rp)</label>
                                <input type="number" class="form-control" id="price" name="price" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="image" class="form-label">Gambar Layanan</label>
                                <input type="file" class="form-control" id="image" name="image" accept="image/*">
                                <small class="text-muted">Format: JPG, PNG, GIF. Maksimal 2MB.</small>
                            </div>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="description" class="form-label">Deskripsi</label>
                        <textarea class="form-control" id="description" name="description" rows="3"></textarea>
                    </div>
                    <button type="submit" name="add_service" class="btn btn-primary">
                        <i class="fas fa-save me-1"></i> Simpan Layanan
                    </button>
                </form>
            </div>
        </div>

        <!-- Daftar Layanan -->
        <div class="card">
            <div class="card-header bg-light">
                <h5 class="mb-0"><i class="fas fa-concierge-bell me-2"></i>Daftar Layanan</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Nama</th>
                                <th>Jenis</th>
                                <th>Lokasi</th>
                                <th>Harga</th>
                                <th>Kapasitas</th>
                                <th>Status</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach($services as $index => $service): ?>
                            <tr>
                                <td><?php echo $index + 1; ?></td>
                                <td><?php echo $service['name']; ?></td>
                                <td>
                                    <span class="badge <?php echo $service['type'] == 'alam' ? 'bg-success' : 'bg-info'; ?>">
                                        <?php echo $service['type'] == 'alam' ? 'Alam' : 'Buatan'; ?>
                                    </span>
                                </td>
                                <td><?php echo $service['location']; ?></td>
                                <td>Rp <?php echo number_format($service['price'], 0, ',', '.'); ?></td>
                                <td><?php echo $service['capacity']; ?> orang</td>
                                <td>
                                    <span class="badge <?php echo $service['status'] == 'active' ? 'bg-success' : 'bg-secondary'; ?>">
                                        <?php echo $service['status'] == 'active' ? 'Aktif' : 'Nonaktif'; ?>
                                    </span>
                                </td>
                                <td>
                                    <div class="btn-group btn-group-sm">
                                        <!-- Edit Button -->
                                        <button type="button" class="btn btn-outline-primary" data-bs-toggle="modal" data-bs-target="#editServiceModal<?php echo $service['id']; ?>">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <!-- Delete Button -->
                                        <button type="button" class="btn btn-outline-danger" data-bs-toggle="modal" data-bs-target="#deleteServiceModal<?php echo $service['id']; ?>">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>

                                    <!-- Edit Service Modal -->
                                    <div class="modal fade" id="editServiceModal<?php echo $service['id']; ?>" tabindex="-1">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title">Edit Layanan</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                </div>
                                                <form method="POST" enctype="multipart/form-data">
                                                    <div class="modal-body">
                                                        <input type="hidden" name="id" value="<?php echo $service['id']; ?>">
                                                        <div class="mb-3">
                                                            <label for="name<?php echo $service['id']; ?>" class="form-label">Nama Layanan</label>
                                                            <input type="text" class="form-control" id="name<?php echo $service['id']; ?>" name="name" value="<?php echo $service['name']; ?>" required>
                                                        </div>
                                                        <div class="mb-3">
                                                            <label for="type<?php echo $service['id']; ?>" class="form-label">Jenis</label>
                                                            <select class="form-control" id="type<?php echo $service['id']; ?>" name="type" required>
                                                                <option value="alam" <?php echo $service['type'] == 'alam' ? 'selected' : ''; ?>>Wisata Alam</option>
                                                                <option value="buatan" <?php echo $service['type'] == 'buatan' ? 'selected' : ''; ?>>Wisata Buatan</option>
                                                            </select>
                                                        </div>
                                                        <div class="mb-3">
                                                            <label for="location<?php echo $service['id']; ?>" class="form-label">Lokasi</label>
                                                            <input type="text" class="form-control" id="location<?php echo $service['id']; ?>" name="location" value="<?php echo $service['location']; ?>" required>
                                                        </div>
                                                        <div class="mb-3">
                                                            <label for="price<?php echo $service['id']; ?>" class="form-label">Harga per Orang (Rp)</label>
                                                            <input type="number" class="form-control" id="price<?php echo $service['id']; ?>" name="price" value="<?php echo $service['price']; ?>" required>
                                                        </div>
                                                        <div class="mb-3">
                                                            <label for="capacity<?php echo $service['id']; ?>" class="form-label">Kapasitas Maksimal</label>
                                                            <input type="number" class="form-control" id="capacity<?php echo $service['id']; ?>" name="capacity" value="<?php echo $service['capacity']; ?>" required>
                                                        </div>
                                                        <div class="mb-3">
                                                            <label for="status<?php echo $service['id']; ?>" class="form-label">Status</label>
                                                            <select class="form-control" id="status<?php echo $service['id']; ?>" name="status" required>
                                                                <option value="active" <?php echo $service['status'] == 'active' ? 'selected' : ''; ?>>Aktif</option>
                                                                <option value="inactive" <?php echo $service['status'] == 'inactive' ? 'selected' : ''; ?>>Nonaktif</option>
                                                            </select>
                                                        </div>
                                                        <div class="mb-3">
                                                            <label for="image<?php echo $service['id']; ?>" class="form-label">Gambar Layanan (Opsional)</label>
                                                            <input type="file" class="form-control" id="image<?php echo $service['id']; ?>" name="image" accept="image/*">
                                                            <small class="text-muted">Format: JPG, PNG, GIF. Maksimal 2MB. Biarkan kosong jika tidak ingin mengubah gambar.</small>
                                                        </div>
                                                        <div class="mb-3">
                                                            <label for="description<?php echo $service['id']; ?>" class="form-label">Deskripsi</label>
                                                            <textarea class="form-control" id="description<?php echo $service['id']; ?>" name="description" rows="3"><?php echo $service['description']; ?></textarea>
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                                        <button type="submit" name="edit_service" class="btn btn-primary">Simpan Perubahan</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Delete Service Modal -->
                                    <div class="modal fade" id="deleteServiceModal<?php echo $service['id']; ?>" tabindex="-1">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title">Hapus Layanan</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <p>Apakah Anda yakin ingin menghapus layanan <strong><?php echo $service['name']; ?></strong>?</p>
                                                    <p class="text-danger">Note: Layanan yang memiliki riwayat reservasi tidak dapat dihapus.</p>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                                    <form method="POST" style="display: inline;">
                                                        <input type="hidden" name="id" value="<?php echo $service['id']; ?>">
                                                        <button type="submit" name="delete_service" class="btn btn-danger">Hapus</button>
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

        <!-- Form Tambah Hotel -->
        <div class="card mb-4">
            <div class="card-header bg-success text-white">
                <h5 class="mb-0"><i class="fas fa-hotel me-2"></i>Tambah Hotel Baru</h5>
            </div>
            <div class="card-body">
                <form method="POST" enctype="multipart/form-data">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="hotel_name" class="form-label">Nama Hotel</label>
                                <input type="text" class="form-control" id="hotel_name" name="hotel_name" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="service_id" class="form-label">Layanan Terkait</label>
                                <select class="form-control" id="service_id" name="service_id" required>
                                    <option value="">Pilih Layanan</option>
                                    <?php foreach($services as $service): ?>
                                    <option value="<?php echo $service['id']; ?>"><?php echo $service['name']; ?> - <?php echo $service['location']; ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="price_per_night" class="form-label">Harga per Malam (Rp)</label>
                                <input type="number" class="form-control" id="price_per_night" name="price_per_night" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="hotel_image" class="form-label">Gambar Hotel</label>
                                <input type="file" class="form-control" id="hotel_image" name="hotel_image" accept="image/*">
                                <small class="text-muted">Format: JPG, PNG, GIF. Maksimal 2MB.</small>
                            </div>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="hotel_description" class="form-label">Deskripsi Hotel</label>
                        <textarea class="form-control" id="hotel_description" name="hotel_description" rows="3"></textarea>
                    </div>
                    <button type="submit" name="add_hotel" class="btn btn-success">
                        <i class="fas fa-save me-1"></i> Simpan Hotel
                    </button>
                </form>
            </div>
        </div>

        <!-- Daftar Hotel -->
        <div class="card">
            <div class="card-header bg-light">
                <h5 class="mb-0"><i class="fas fa-hotel me-2"></i>Daftar Hotel</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Nama Hotel</th>
                                <th>Layanan</th>
                                <th>Harga/Malam</th>
                                <th>Status</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach($hotels as $index => $hotel): ?>
                            <tr>
                                <td><?php echo $index + 1; ?></td>
                                <td><?php echo $hotel['name']; ?></td>
                                <td><?php echo $hotel['service_name']; ?></td>
                                <td>Rp <?php echo number_format($hotel['price_per_night'], 0, ',', '.'); ?></td>
                                <td>
                                    <span class="badge <?php echo $hotel['status'] == 'active' ? 'bg-success' : 'bg-secondary'; ?>">
                                        <?php echo $hotel['status'] == 'active' ? 'Aktif' : 'Nonaktif'; ?>
                                    </span>
                                </td>
                                <td>
                                    <div class="btn-group btn-group-sm">
                                        <button type="button" class="btn btn-outline-primary" data-bs-toggle="modal" data-bs-target="#editHotelModal<?php echo $hotel['id']; ?>">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <button type="button" class="btn btn-outline-danger" data-bs-toggle="modal" data-bs-target="#deleteHotelModal<?php echo $hotel['id']; ?>">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>

                                    <!-- Edit Hotel Modal -->
                                    <div class="modal fade" id="editHotelModal<?php echo $hotel['id']; ?>" tabindex="-1">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title">Edit Hotel</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                </div>
                                                <form method="POST" enctype="multipart/form-data">
                                                    <div class="modal-body">
                                                        <input type="hidden" name="id" value="<?php echo $hotel['id']; ?>">
                                                        <div class="mb-3">
                                                            <label for="hotel_name<?php echo $hotel['id']; ?>" class="form-label">Nama Hotel</label>
                                                            <input type="text" class="form-control" id="hotel_name<?php echo $hotel['id']; ?>" name="hotel_name" value="<?php echo $hotel['name']; ?>" required>
                                                        </div>
                                                        <div class="mb-3">
                                                            <label for="service_id<?php echo $hotel['id']; ?>" class="form-label">Layanan Terkait</label>
                                                            <select class="form-control" id="service_id<?php echo $hotel['id']; ?>" name="service_id" required>
                                                                <?php foreach($services as $service): ?>
                                                                <option value="<?php echo $service['id']; ?>" <?php echo $service['id'] == $hotel['service_id'] ? 'selected' : ''; ?>><?php echo $service['name']; ?> - <?php echo $service['location']; ?></option>
                                                                <?php endforeach; ?>
                                                            </select>
                                                        </div>
                                                        <div class="mb-3">
                                                            <label for="price_per_night<?php echo $hotel['id']; ?>" class="form-label">Harga per Malam (Rp)</label>
                                                            <input type="number" class="form-control" id="price_per_night<?php echo $hotel['id']; ?>" name="price_per_night" value="<?php echo $hotel['price_per_night']; ?>" required>
                                                        </div>
                                                        <div class="mb-3">
                                                            <label for="status<?php echo $hotel['id']; ?>" class="form-label">Status</label>
                                                            <select class="form-control" id="status<?php echo $hotel['id']; ?>" name="status" required>
                                                                <option value="active" <?php echo $hotel['status'] == 'active' ? 'selected' : ''; ?>>Aktif</option>
                                                                <option value="inactive" <?php echo $hotel['status'] == 'inactive' ? 'selected' : ''; ?>>Nonaktif</option>
                                                            </select>
                                                        </div>
                                                        <div class="mb-3">
                                                            <label for="hotel_image<?php echo $hotel['id']; ?>" class="form-label">Gambar Hotel (Opsional)</label>
                                                            <input type="file" class="form-control" id="hotel_image<?php echo $hotel['id']; ?>" name="hotel_image" accept="image/*">
                                                            <small class="text-muted">Format: JPG, PNG, GIF. Maksimal 2MB. Biarkan kosong jika tidak ingin mengubah gambar.</small>
                                                        </div>
                                                        <div class="mb-3">
                                                            <label for="hotel_description<?php echo $hotel['id']; ?>" class="form-label">Deskripsi Hotel</label>
                                                            <textarea class="form-control" id="hotel_description<?php echo $hotel['id']; ?>" name="hotel_description" rows="3"><?php echo $hotel['description']; ?></textarea>
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                                        <button type="submit" name="edit_hotel" class="btn btn-primary">Simpan Perubahan</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Delete Hotel Modal -->
                                    <div class="modal fade" id="deleteHotelModal<?php echo $hotel['id']; ?>" tabindex="-1">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title">Hapus Hotel</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <p>Apakah Anda yakin ingin menghapus hotel <strong><?php echo $hotel['name']; ?></strong>?</p>
                                                    <p class="text-danger">Note: Hotel yang memiliki riwayat reservasi tidak dapat dihapus.</p>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                                    <form method="POST" style="display: inline;">
                                                        <input type="hidden" name="id" value="<?php echo $hotel['id']; ?>">
                                                        <button type="submit" name="delete_hotel" class="btn btn-danger">Hapus</button>
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
