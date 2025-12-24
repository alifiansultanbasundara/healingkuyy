<?php
// File: user/blog_detail.php (PERBAIKAN)
include '../includes/config.php';
include '../includes/auth.php';

if (!isLoggedIn() || isAdmin()) {
    redirect('../login.php');
}

if (!isset($_GET['id'])) {
    redirect('blog.php');
}

$post_id = sanitize($_GET['id']);

// Ambil detail artikel
$stmt = $pdo->prepare("
    SELECT b.*, u.name as author_name 
    FROM blog_posts b 
    JOIN users u ON b.author_id = u.id 
    WHERE b.id = ? AND b.status = 'published'
");
$stmt->execute([$post_id]);
$post = $stmt->fetch();

if (!$post) {
    $_SESSION['error'] = "Artikel tidak ditemukan.";
    redirect('blog.php');
}

// Ambil artikel terkait
$related_posts = $pdo->prepare("
    SELECT b.*, u.name as author_name 
    FROM blog_posts b 
    JOIN users u ON b.author_id = u.id 
    WHERE b.id != ? AND b.status = 'published' 
    ORDER BY b.created_at DESC 
    LIMIT 3
");
$related_posts->execute([$post_id]);
$related = $related_posts->fetchAll();

// Handle save article
if (isset($_POST['save_article'])) {
    // Simpan ke database atau session (untuk demo pakai session)
    $_SESSION['saved_articles'] = $_SESSION['saved_articles'] ?? [];
    if (!in_array($post_id, $_SESSION['saved_articles'])) {
        $_SESSION['saved_articles'][] = $post_id;
        $_SESSION['success'] = "Artikel berhasil disimpan!";
    } else {
        $_SESSION['info'] = "Artikel sudah disimpan sebelumnya.";
    }
    redirect("blog_detail.php?id=$post_id");
}

$is_saved = isset($_SESSION['saved_articles']) && in_array($post_id, $_SESSION['saved_articles']);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $post['title']; ?> - HealingKuy!.id</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="../assets/css/style.css" rel="stylesheet">
    <style>
        .blog-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 4rem 0;
            margin-bottom: 2rem;
        }
        .blog-content {
            font-size: 1.1rem;
            line-height: 1.8;
        }
        .blog-content img {
            max-width: 100%;
            height: auto;
            border-radius: 10px;
            margin: 2rem 0;
        }
        .related-post {
            transition: transform 0.3s;
        }
        .related-post:hover {
            transform: translateY(-5px);
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

    <?php if(isset($_SESSION['success'])): ?>
    <div class="container mt-3">
        <div class="alert alert-success"><?php echo $_SESSION['success']; unset($_SESSION['success']); ?></div>
    </div>
    <?php endif; ?>

    <?php if(isset($_SESSION['info'])): ?>
    <div class="container mt-3">
        <div class="alert alert-info"><?php echo $_SESSION['info']; unset($_SESSION['info']); ?></div>
    </div>
    <?php endif; ?>

    <!-- Blog Header -->
    <div class="blog-header">
        <div class="container">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="blog.php" class="text-white">Blog</a></li>
                    <li class="breadcrumb-item active text-white"><?php echo $post['title']; ?></li>
                </ol>
            </nav>
            <h1 class="display-5 fw-bold"><?php echo $post['title']; ?></h1>
            <div class="row align-items-center mt-4">
                <div class="col-md-6">
                    <div class="d-flex align-items-center">
                        <div class="bg-white text-primary rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 50px; height: 50px;">
                            <i class="fas fa-user"></i>
                        </div>
                        <div>
                            <p class="mb-0 fw-bold"><?php echo $post['author_name']; ?></p>
                            <small>
                                <i class="fas fa-calendar me-1"></i>
                                <?php echo date('d F Y', strtotime($post['created_at'])); ?>
                            </small>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 text-md-end mt-3 mt-md-0">
                    <div class="d-flex justify-content-md-end gap-2">
                        <button class="btn btn-light" onclick="shareArticle()">
                            <i class="fas fa-share-alt me-1"></i> Bagikan
                        </button>
                        <form method="POST" class="d-inline">
                            <button type="submit" name="save_article" class="btn btn-light">
                                <i class="fas fa-<?php echo $is_saved ? 'bookmark' : 'bookmark'; ?> me-1"></i> 
                                <?php echo $is_saved ? 'Disimpan' : 'Simpan'; ?>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="container">
        <div class="row">
            <!-- Main Content -->
            <div class="col-lg-8">
                <article class="blog-content">
                    <?php echo $post['content']; ?>
                    
                    <!-- Tags -->
                    <div class="mt-5 pt-4 border-top">
                        <h6>Tags:</h6>
                        <div class="d-flex flex-wrap gap-2">
                            <span class="badge bg-secondary">Wisata</span>
                            <span class="badge bg-secondary">Tips</span>
                            <span class="badge bg-secondary">Indonesia</span>
                            <span class="badge bg-secondary">Travel</span>
                        </div>
                    </div>

                    <!-- Share -->
                    <div class="mt-4">
                        <h6>Bagikan Artikel:</h6>
                        <div class="d-flex gap-2">
                            <button class="btn btn-primary btn-sm" onclick="shareSocial('facebook')">
                                <i class="fab fa-facebook-f me-1"></i> Facebook
                            </button>
                            <button class="btn btn-info btn-sm" onclick="shareSocial('twitter')">
                                <i class="fab fa-twitter me-1"></i> Twitter
                            </button>
                            <button class="btn btn-danger btn-sm" onclick="shareSocial('instagram')">
                                <i class="fab fa-instagram me-1"></i> Instagram
                            </button>
                            <button class="btn btn-success btn-sm" onclick="shareSocial('whatsapp')">
                                <i class="fab fa-whatsapp me-1"></i> WhatsApp
                            </button>
                        </div>
                    </div>
                </article>

                <!-- Related Posts -->
                <?php if (!empty($related)): ?>
                <div class="mt-5 pt-4 border-top">
                    <h4 class="mb-4">Artikel Terkait</h4>
                    <div class="row">
                        <?php foreach($related as $related_post): ?>
                        <div class="col-md-4 mb-3">
                            <div class="card related-post h-100">
                                <div class="card-body">
                                    <h6 class="card-title">
                                        <a href="blog_detail.php?id=<?php echo $related_post['id']; ?>" class="text-decoration-none">
                                            <?php echo $related_post['title']; ?>
                                        </a>
                                    </h6>
                                    <small class="text-muted">
                                        <i class="fas fa-calendar me-1"></i>
                                        <?php echo date('d M Y', strtotime($related_post['created_at'])); ?>
                                    </small>
                                </div>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>
                <?php endif; ?>
            </div>
            
            <!-- Sidebar -->
            <div class="col-lg-4">
                <!-- Author Info -->
                <div class="card mb-4">
                    <div class="card-body text-center">
                        <div class="bg-primary text-white rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 80px; height: 80px;">
                            <i class="fas fa-user fa-2x"></i>
                        </div>
                        <h5><?php echo $post['author_name']; ?></h5>
                        <p class="text-muted">Travel Writer & Adventure Enthusiast</p>
                        <p class="small">Penulis dengan pengalaman lebih dari 5 tahun dalam dunia travel dan wisata Indonesia.</p>
                    </div>
                </div>

                <!-- Newsletter -->
                <div class="card mb-4">
                    <div class="card-header bg-warning text-dark">
                        <h6 class="mb-0"><i class="fas fa-envelope me-2"></i>Newsletter</h6>
                    </div>
                    <div class="card-body">
                        <p class="small mb-3">Dapatkan update artikel terbaru langsung ke email Anda.</p>
                        <form method="POST" action="newsletter_subscribe.php">
                            <div class="input-group mb-2">
                                <input type="email" class="form-control form-control-sm" name="email" placeholder="Email Anda" required>
                                <button class="btn btn-warning btn-sm" type="submit">
                                    <i class="fas fa-paper-plane"></i>
                                </button>
                            </div>
                            <small class="text-muted">Kami tidak akan mengirim spam</small>
                        </form>
                    </div>
                </div>

                <!-- Quick Reservation -->
                <div class="card">
                    <div class="card-body text-center bg-light rounded">
                        <h6>Siap Berpetualang?</h6>
                        <p class="small text-muted mb-3">Rencanakan perjalanan Anda sekarang</p>
                        <a href="reservation.php" class="btn btn-success w-100 mb-2">
                            <i class="fas fa-calendar-plus me-1"></i> Buat Reservasi
                        </a>
                        <a href="dashboard.php" class="btn btn-outline-primary w-100">
                            <i class="fas fa-compass me-1"></i> Lihat Dashboard
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Back to Top -->
    <div class="container mt-4">
        <div class="text-center">
            <a href="blog.php" class="btn btn-primary">
                <i class="fas fa-arrow-left me-2"></i>Kembali ke Blog
            </a>
            <a href="#" class="btn btn-outline-primary" id="backToTop">
                <i class="fas fa-arrow-up me-2"></i>Ke Atas
            </a>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="../assets/js/script.js"></script>
    <script>
        // Back to top button
        document.getElementById('backToTop').addEventListener('click', function(e) {
            e.preventDefault();
            window.scrollTo({ top: 0, behavior: 'smooth' });
        });

        // Share article function
        function shareArticle() {
            if (navigator.share) {
                navigator.share({
                    title: '<?php echo $post['title']; ?>',
                    text: '<?php echo strip_tags($post['excerpt'] ?: $post['content']); ?>',
                    url: window.location.href
                }).then(() => {
                    console.log('Artikel berhasil dibagikan');
                }).catch(console.error);
            } else {
                // Fallback: copy to clipboard
                navigator.clipboard.writeText(window.location.href).then(() => {
                    alert('Link artikel berhasil disalin!');
                });
            }
        }

        // Social media sharing
        function shareSocial(platform) {
            const url = encodeURIComponent(window.location.href);
            const title = encodeURIComponent('<?php echo $post['title']; ?>');
            let shareUrl = '';
            
            switch(platform) {
                case 'facebook':
                    shareUrl = `https://www.facebook.com/sharer/sharer.php?u=${url}`;
                    break;
                case 'twitter':
                    shareUrl = `https://twitter.com/intent/tweet?url=${url}&text=${title}`;
                    break;
                case 'whatsapp':
                    shareUrl = `https://wa.me/?text=${title} ${url}`;
                    break;
                default:
                    return;
            }
            
            window.open(shareUrl, '_blank', 'width=600,height=400');
        }
    </script>
</body>
</html>