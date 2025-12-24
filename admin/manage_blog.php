<?php
// File: admin/manage_blog.php
include '../includes/config.php';
include '../includes/auth.php';

if (!isLoggedIn() || !isAdmin()) {
    redirect('../login.php');
}

$error = '';
$success = '';

// Handle form actions
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['add_post'])) {
        // Tambah post baru
        $title = sanitize($_POST['title']);
        $content = $_POST['content'];
        $excerpt = sanitize($_POST['excerpt']);
        $status = sanitize($_POST['status']);

        // Validasi
        if (empty($title) || empty($content)) {
            $error = "Judul dan konten wajib diisi!";
        } else {
            $stmt = $pdo->prepare("INSERT INTO blog_posts (title, content, excerpt, author_id, status) VALUES (?, ?, ?, ?, ?)");
            if ($stmt->execute([$title, $content, $excerpt, $_SESSION['user_id'], $status])) {
                $success = "Artikel berhasil ditambahkan!";
                logActivity('add_blog', 'Added new blog post: ' . $title);
            } else {
                $error = "Terjadi kesalahan saat menambahkan artikel.";
            }
        }
    } elseif (isset($_POST['edit_post'])) {
        // Edit post
        $id = sanitize($_POST['id']);
        $title = sanitize($_POST['title']);
        $content = $_POST['content'];
        $excerpt = sanitize($_POST['excerpt']);
        $status = sanitize($_POST['status']);

        // Validasi
        if (empty($title) || empty($content)) {
            $error = "Judul dan konten wajib diisi!";
        } else {
            $stmt = $pdo->prepare("UPDATE blog_posts SET title = ?, content = ?, excerpt = ?, status = ? WHERE id = ?");
            if ($stmt->execute([$title, $content, $excerpt, $status, $id])) {
                $success = "Artikel berhasil diperbarui!";
                logActivity('edit_blog', 'Updated blog post: ' . $title);
            } else {
                $error = "Terjadi kesalahan saat memperbarui artikel.";
            }
        }
    } elseif (isset($_POST['delete_post'])) {
        // Hapus post
        $id = sanitize($_POST['id']);
        $stmt = $pdo->prepare("DELETE FROM blog_posts WHERE id = ?");
        if ($stmt->execute([$id])) {
            $success = "Artikel berhasil dihapus!";
            logActivity('delete_blog', 'Deleted blog post ID: ' . $id);
        } else {
            $error = "Terjadi kesalahan saat menghapus artikel.";
        }
    }
}

// Ambil semua post
$posts = $pdo->query("
    SELECT b.*, u.name as author_name 
    FROM blog_posts b 
    JOIN users u ON b.author_id = u.id 
    ORDER BY b.created_at DESC
")->fetchAll();
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manajemen Blog - HealingKuy!.id</title>
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
        <h2 class="mb-4">Manajemen Blog</h2>

        <?php if($error): ?>
        <div class="alert alert-danger"><?php echo $error; ?></div>
        <?php endif; ?>
        
        <?php if($success): ?>
        <div class="alert alert-success"><?php echo $success; ?></div>
        <?php endif; ?>

        <!-- Form Tambah Artikel -->
        <div class="card mb-4">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0"><i class="fas fa-plus-circle me-2"></i>Tambah Artikel Baru</h5>
            </div>
            <div class="card-body">
                <form method="POST">
                    <div class="mb-3">
                        <label for="title" class="form-label">Judul Artikel</label>
                        <input type="text" class="form-control" id="title" name="title" required>
                    </div>
                    <div class="mb-3">
                        <label for="excerpt" class="form-label">Kutipan (Excerpt)</label>
                        <textarea class="form-control" id="excerpt" name="excerpt" rows="2"></textarea>
                        <div class="form-text">Ringkasan singkat artikel yang akan ditampilkan di halaman blog.</div>
                    </div>
                    <div class="mb-3">
                        <label for="content" class="form-label">Konten</label>
                        <textarea class="form-control" id="content" name="content" rows="6" required></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="status" class="form-label">Status</label>
                        <select class="form-control" id="status" name="status" required>
                            <option value="published">Published</option>
                            <option value="draft">Draft</option>
                        </select>
                    </div>
                    <button type="submit" name="add_post" class="btn btn-primary">
                        <i class="fas fa-save me-1"></i> Simpan Artikel
                    </button>
                </form>
            </div>
        </div>

        <!-- Daftar Artikel -->
        <div class="card">
            <div class="card-header bg-light">
                <h5 class="mb-0"><i class="fas fa-newspaper me-2"></i>Daftar Artikel</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Judul</th>
                                <th>Penulis</th>
                                <th>Status</th>
                                <th>Tanggal</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach($posts as $index => $post): ?>
                            <tr>
                                <td><?php echo $index + 1; ?></td>
                                <td><?php echo $post['title']; ?></td>
                                <td><?php echo $post['author_name']; ?></td>
                                <td>
                                    <span class="badge <?php echo $post['status'] == 'published' ? 'bg-success' : 'bg-secondary'; ?>">
                                        <?php echo $post['status'] == 'published' ? 'Published' : 'Draft'; ?>
                                    </span>
                                </td>
                                <td><?php echo date('d/m/Y', strtotime($post['created_at'])); ?></td>
                                <td>
                                    <div class="btn-group btn-group-sm">
                                        <!-- Edit Button -->
                                        <button type="button" class="btn btn-outline-primary" data-bs-toggle="modal" data-bs-target="#editPostModal<?php echo $post['id']; ?>">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <!-- Delete Button -->
                                        <button type="button" class="btn btn-outline-danger" data-bs-toggle="modal" data-bs-target="#deletePostModal<?php echo $post['id']; ?>">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>

                                    <!-- Edit Post Modal -->
                                    <div class="modal fade" id="editPostModal<?php echo $post['id']; ?>" tabindex="-1">
                                        <div class="modal-dialog modal-lg">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title">Edit Artikel</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                </div>
                                                <form method="POST">
                                                    <div class="modal-body">
                                                        <input type="hidden" name="id" value="<?php echo $post['id']; ?>">
                                                        <div class="mb-3">
                                                            <label for="title<?php echo $post['id']; ?>" class="form-label">Judul Artikel</label>
                                                            <input type="text" class="form-control" id="title<?php echo $post['id']; ?>" name="title" value="<?php echo $post['title']; ?>" required>
                                                        </div>
                                                        <div class="mb-3">
                                                            <label for="excerpt<?php echo $post['id']; ?>" class="form-label">Kutipan (Excerpt)</label>
                                                            <textarea class="form-control" id="excerpt<?php echo $post['id']; ?>" name="excerpt" rows="2"><?php echo $post['excerpt']; ?></textarea>
                                                        </div>
                                                        <div class="mb-3">
                                                            <label for="content<?php echo $post['id']; ?>" class="form-label">Konten</label>
                                                            <textarea class="form-control" id="content<?php echo $post['id']; ?>" name="content" rows="6" required><?php echo $post['content']; ?></textarea>
                                                        </div>
                                                        <div class="mb-3">
                                                            <label for="status<?php echo $post['id']; ?>" class="form-label">Status</label>
                                                            <select class="form-control" id="status<?php echo $post['id']; ?>" name="status" required>
                                                                <option value="published" <?php echo $post['status'] == 'published' ? 'selected' : ''; ?>>Published</option>
                                                                <option value="draft" <?php echo $post['status'] == 'draft' ? 'selected' : ''; ?>>Draft</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                                        <button type="submit" name="edit_post" class="btn btn-primary">Simpan Perubahan</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Delete Post Modal -->
                                    <div class="modal fade" id="deletePostModal<?php echo $post['id']; ?>" tabindex="-1">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title">Hapus Artikel</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <p>Apakah Anda yakin ingin menghapus artikel <strong><?php echo $post['title']; ?></strong>?</p>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                                    <form method="POST" style="display: inline;">
                                                        <input type="hidden" name="id" value="<?php echo $post['id']; ?>">
                                                        <button type="submit" name="delete_post" class="btn btn-danger">Hapus</button>
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