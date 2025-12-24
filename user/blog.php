<?php
// File: user/blog.php (PERBAIKAN)
include '../includes/config.php';
include '../includes/auth.php';

if (!isLoggedIn() || isAdmin()) {
    redirect('../login.php');
}

// Handle search
$search_keyword = '';
$blog_posts = [];

if (isset($_GET['search']) && !empty($_GET['search'])) {
    $search_keyword = sanitize($_GET['search']);
    $stmt = $pdo->prepare("
        SELECT b.*, u.name as author_name 
        FROM blog_posts b 
        JOIN users u ON b.author_id = u.id 
        WHERE b.status = 'published' 
        AND (b.title LIKE ? OR b.content LIKE ? OR b.excerpt LIKE ?)
        ORDER BY b.created_at DESC
    ");
    $search_term = "%$search_keyword%";
    $stmt->execute([$search_term, $search_term, $search_term]);
    $blog_posts = $stmt->fetchAll();
} else {
    // Ambil semua artikel blog
    $stmt = $pdo->query("
        SELECT b.*, u.name as author_name 
        FROM blog_posts b 
        JOIN users u ON b.author_id = u.id 
        WHERE b.status = 'published' 
        ORDER BY b.created_at DESC
    ");
    $blog_posts = $stmt->fetchAll();
}

// Ambil artikel populer
$popular_posts = $pdo->query("
    SELECT b.*, u.name as author_name 
    FROM blog_posts b 
    JOIN users u ON b.author_id = u.id 
    WHERE b.status = 'published' 
    ORDER BY b.created_at DESC 
    LIMIT 3
")->fetchAll();
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Blog - HealingKuy!.id</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="../assets/css/style.css" rel="stylesheet">
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
        <div class="row">
            <!-- Main Content -->
            <div class="col-lg-8">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h2>Blog & Artikel</h2>
                    <div class="text-muted">
                        <i class="fas fa-newspaper me-1"></i> 
                        <?php echo count($blog_posts); ?> Artikel
                        <?php if($search_keyword): ?>
                            untuk "<strong><?php echo $search_keyword; ?></strong>"
                        <?php endif; ?>
                    </div>
                </div>

                <?php if(empty($blog_posts)): ?>
                    <div class="text-center py-5">
                        <i class="fas fa-newspaper fa-4x text-muted mb-3"></i>
                        <h5 class="text-muted">
                            <?php if($search_keyword): ?>
                                Tidak ditemukan artikel dengan kata kunci "<?php echo $search_keyword; ?>"
                            <?php else: ?>
                                Belum ada artikel blog
                            <?php endif; ?>
                        </h5>
                        <p class="text-muted">
                            <?php if($search_keyword): ?>
                                <a href="blog.php" class="btn btn-primary mt-2">Lihat Semua Artikel</a>
                            <?php else: ?>
                                Silakan kembali lagi nanti untuk membaca artikel terbaru.
                            <?php endif; ?>
                        </p>
                    </div>
                <?php else: ?>
                    <?php foreach($blog_posts as $post): ?>
                    <div class="card blog-card mb-4">
                        <div class="blog-image">
                            <i class="fas fa-newspaper fa-4x"></i>
                        </div>
                        <div class="card-body">
                            <h4 class="card-title"><?php echo $post['title']; ?></h4>
                            <div class="text-muted mb-3">
                                <small>
                                    <i class="fas fa-user me-1"></i> <?php echo $post['author_name']; ?>
                                    <i class="fas fa-calendar me-1 ms-3"></i> <?php echo date('d F Y', strtotime($post['created_at'])); ?>
                                </small>
                            </div>
                            <p class="card-text">
                                <?php 
                                $excerpt = $post['excerpt'] ?: strip_tags($post['content']);
                                echo strlen($excerpt) > 200 ? substr($excerpt, 0, 200) . '...' : $excerpt;
                                ?>
                            </p>
                            <a href="blog_detail.php?id=<?php echo $post['id']; ?>" class="btn btn-primary">
                                Baca Selengkapnya <i class="fas fa-arrow-right ms-1"></i>
                            </a>
                        </div>
                    </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
            
            <!-- Sidebar -->
            <div class="col-lg-4">
                <!-- Search -->
                <div class="card mb-4">
                    <div class="card-body">
                        <h5 class="card-title">Cari Artikel</h5>
                        <form method="GET" action="blog.php">
                            <div class="input-group">
                                <input type="text" class="form-control" name="search" 
                                       value="<?php echo $search_keyword; ?>" 
                                       placeholder="Kata kunci...">
                                <button class="btn btn-primary" type="submit">
                                    <i class="fas fa-search"></i>
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Popular Posts -->
                <div class="card mb-4">
                    <div class="card-header bg-primary text-white">
                        <h6 class="mb-0"><i class="fas fa-fire me-2"></i>Artikel Populer</h6>
                    </div>
                    <div class="card-body">
                        <?php foreach($popular_posts as $post): ?>
                        <div class="popular-post mb-3 p-3">
                            <h6 class="mb-1">
                                <a href="blog_detail.php?id=<?php echo $post['id']; ?>" class="text-decoration-none">
                                    <?php echo $post['title']; ?>
                                </a>
                            </h6>
                            <small class="text-muted">
                                <i class="fas fa-calendar me-1"></i>
                                <?php echo date('d M Y', strtotime($post['created_at'])); ?>
                            </small>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>

                <!-- Categories -->
                <div class="card mb-4">
                    <div class="card-header bg-success text-white">
                        <h6 class="mb-0"><i class="fas fa-tags me-2"></i>Kategori</h6>
                    </div>
                    <div class="card-body">
                        <div class="d-flex flex-wrap gap-2">
                            <span class="badge bg-light text-dark p-2">Tips Perjalanan</span>
                            <span class="badge bg-light text-dark p-2">Destinasi</span>
                            <span class="badge bg-light text-dark p-2">Akomodasi</span>
                            <span class="badge bg-light text-dark p-2">Kuliner</span>
                            <span class="badge bg-light text-dark p-2">Budaya</span>
                        </div>
                    </div>
                </div>

                <!-- Quick Actions -->
                <div class="card">
                    <div class="card-body text-center">
                        <h6>Butuh Bantuan?</h6>
                        <p class="text-muted small mb-3">Hubungi kami untuk pertanyaan tentang reservasi</p>
                        <div class="d-grid gap-2">
                            <a href="reservation.php" class="btn btn-success">
                                <i class="fas fa-calendar-plus me-1"></i> Buat Reservasi
                            </a>
                            <a href="../index.php#contact" class="btn btn-outline-primary">
                                <i class="fas fa-headset me-1"></i> Hubungi Kami
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="../assets/js/script.js"></script>
</body>
</html>