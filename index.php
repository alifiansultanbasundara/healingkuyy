<?php
// File: index.php
include 'includes/config.php';

// Fetch service images for slideshow
$images = [];
$stmt = $pdo->query("SELECT image FROM services WHERE status = 'active' AND image != ''");
while($service = $stmt->fetch()) {
    $images[] = 'assets/images/' . $service['image'];
}
if (empty($images)) {
    $images[] = 'https://images.unsplash.com/photo-1469474968028-56623f02e42e'; // fallback
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>HealingKuy! - Reservasi Wisata Indonesia</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="assets/css/style.css" rel="stylesheet">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/three.js/r128/three.min.js"></script>
    <style>
        .hero-section {
            background: linear-gradient(rgba(0,0,0,0.6), rgba(0,0,0,0.6)), url('<?php echo $images[0]; ?>');
            background-size: cover;
            background-position: center;
            color: white;
            padding: 120px 0;
            transition: background-image 1s ease-in-out;
        }
        .feature-icon {
            font-size: 3rem;
            color: #1abc9c;
            margin-bottom: 1rem;
        }
        .service-card {
            transition: transform 0.3s;
            border: none;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        }
        .service-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 15px rgba(0,0,0,0.2);
        }
        .navbar-brand {
            font-weight: 700;
        }
        .step-number {
            width: 60px;
            height: 60px;
            background: #0d6efd;
            color: white;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            font-weight: bold;
            margin: 0 auto 1rem;
        }

        /* CSS untuk Background Alam */
        .sky-gradient {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(to bottom, #87CEEB 0%, #98FB98 100%);
            animation: skyMove 20s ease-in-out infinite;
        }

        @keyframes skyMove {
            0%, 100% { background: linear-gradient(to bottom, #87CEEB 0%, #98FB98 100%); }
            50% { background: linear-gradient(to bottom, #B0E0E6 0%, #90EE90 100%); }
        }

        .cloud {
            position: absolute;
            background: rgba(255, 255, 255, 0.8);
            border-radius: 50%;
            animation: cloudMove 15s linear infinite;
        }

        .cloud1 {
            width: 100px;
            height: 50px;
            top: 20%;
            left: -100px;
        }

        .cloud2 {
            width: 80px;
            height: 40px;
            top: 40%;
            left: -80px;
            animation-delay: 5s;
        }

        .cloud3 {
            width: 120px;
            height: 60px;
            top: 60%;
            left: -120px;
            animation-delay: 10s;
        }

        @keyframes cloudMove {
            from { left: -120px; }
            to { left: 100vw; }
        }

        .sun-glow {
            position: absolute;
            top: 10%;
            right: 10%;
            width: 100px;
            height: 100px;
            background: radial-gradient(circle, #FFD700 0%, transparent 70%);
            border-radius: 50%;
            animation: sunPulse 4s ease-in-out infinite;
        }

        @keyframes sunPulse {
            0%, 100% { opacity: 0.8; transform: scale(1); }
            50% { opacity: 1; transform: scale(1.1); }
        }

        /* CSS Baru untuk Warna Layanan Kami - Tema Alam */
        #services .btn-primary {
            background: linear-gradient(90deg, #2ecc71, #1abc9c);
            border: none;
            color: white;
            transition: all 0.3s ease;
        }

        #services .btn-primary:hover {
            background: linear-gradient(90deg, #27ae60, #16a085);
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0,0,0,0.2);
        }

        #services .text-primary {
            color: #27ae60 !important; /* Hijau daun untuk harga */
        }

        #services .feature-icon {
            color: #1abc9c; /* Teal untuk ikon fitur */
        }

        #services .step-number {
            background: linear-gradient(90deg, #3498db, #2980b9); /* Biru langit ke biru danau */
            color: white;
        }
    </style>
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark sticky-top">
        <div class="container">
            <a class="navbar-brand fw-bold" href="index.php">
                <i class="fas fa-mountain"></i> HealingKuy!
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item"><a class="nav-link" href="#home">Home</a></li>
                    <li class="nav-item"><a class="nav-link" href="#services">Layanan</a></li>
                    <li class="nav-item"><a class="nav-link" href="#about">Tentang</a></li>
                    <li class="nav-item"><a class="nav-link" href="user/blog.php">Blog</a></li>
                    <li class="nav-item"><a class="nav-link" href="#contact">Kontak</a></li>
                    <?php if(isLoggedIn()): ?>
                        <?php if(isAdmin()): ?>
                            <li class="nav-item"><a class="nav-link" href="admin/dashboard.php"><i class="fas fa-cog"></i> Admin</a></li>
                        <?php else: ?>
                            <li class="nav-item"><a class="nav-link" href="user/dashboard.php"><i class="fas fa-user"></i> Dashboard</a></li>
                        <?php endif; ?>
                        <li class="nav-item"><a class="nav-link" href="logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
                    <?php else: ?>
                        <li class="nav-item"><a class="nav-link" href="login.php"><i class="fas fa-sign-in-alt"></i> Login</a></li>
                        <li class="nav-item"><a class="nav-link" href="register.php"><i class="fas fa-user-plus"></i> Register</a></li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section id="home" class="hero-section text-center">
        <div class="container">
            <h1 class="display-3 fw-bold mb-4">HealingKuy!</h1>
            <p class="lead mb-4">Temukan Keindahan Wisata Alam & Buatan Terbaik di Indonesia</p>
            <div class="row justify-content-center">
                <div class="col-md-8">
                    <p class="mb-4">Platform reservasi terpercaya untuk berbagai destinasi wisata di seluruh Indonesia. Dapatkan pengalaman berwisata yang tak terlupakan dengan kemudahan pemesanan online.</p>
                </div>
            </div>
            <a href="user/reservation.php" class="btn btn-primary btn-lg me-2" style="background: linear-gradient(90deg, #2ecc71, #1abc9c); border: none;">
                <i class="fas fa-calendar-check"></i> Reservasi Sekarang
            </a>
            <a href="#services" class="btn btn-outline-light btn-lg">
                <i class="fas fa-compass"></i> Jelajahi Layanan
            </a>
        </div>
    </section>

    <!-- Features Section -->
    <section class="py-5 bg-light">
        <div class="container">
            <div class="row text-center">
                <div class="col-md-4 mb-4">
                    <div class="feature-icon">
                        <i class="fas fa-shield-alt"></i>
                    </div>
                    <h4>Aman & Terpercaya</h4>
                    <p class="text-muted">Reservasi dengan jaminan keamanan dan kenyamanan data pribadi Anda</p>
                </div>
                <div class="col-md-4 mb-4">
                    <div class="feature-icon">
                        <i class="fas fa-bolt"></i>
                    </div>
                    <h4>Proses Cepat</h4>
                    <p class="text-muted">Reservasi instan dengan konfirmasi langsung dan real-time</p>
                </div>
                <div class="col-md-4 mb-4">
                    <div class="feature-icon">
                        <i class="fas fa-headset"></i>
                    </div>
                    <h4>Support 24/7</h4>
                    <p class="text-muted">Tim customer service siap membantu kapan saja Anda membutuhkan</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Services Section -->
    <section id="services" class="py-5 position-relative">
        <!-- Background Alam Animasi -->
        <div id="background-layanan-alam" class="position-absolute top-0 start-0 w-100 h-100" style="z-index: -1; overflow: hidden;">
            <!-- Langit Gradasi Bergerak -->
            <div class="sky-gradient"></div>
            <!-- Awan Bergerak -->
            <div class="cloud cloud1"></div>
            <div class="cloud cloud2"></div>
            <div class="cloud cloud3"></div>
            <!-- Efek Cahaya Matahari -->
            <div class="sun-glow"></div>
            <!-- Partikel Daun/Embun -->
            <canvas id="particles-canvas" class="position-absolute top-0 start-0 w-100 h-100"></canvas>
            <!-- Gunung Low-Poly 3D -->
            <div id="background-layanan-3d" class="position-absolute top-0 start-0 w-100 h-100"></div>
        </div>
        <div class="container position-relative" style="z-index: 1;">
            <h2 class="text-center mb-5">Layanan Populer Kami</h2>
            <div class="row">
                <?php
                $stmt = $pdo->query("SELECT * FROM services WHERE status = 'active' LIMIT 6");
                while($service = $stmt->fetch()):
                ?>
                <div class="col-md-4 mb-4">
                    <div class="card service-card h-100">
                        <?php if(!empty($service['image'])): ?>
                            <img src="assets/images/<?php echo $service['image']; ?>" class="card-img-top" alt="<?php echo $service['name']; ?>" style="height: 200px; object-fit: cover;">
                        <?php else: ?>
                            <div class="card-img-top bg-secondary text-white d-flex align-items-center justify-content-center" style="height: 200px;">
                                <i class="fas fa-mountain fa-3x"></i>
                            </div>
                        <?php endif; ?>
                        <div class="card-body">
                            <h5 class="card-title"><?php echo $service['name']; ?></h5>
                            <p class="card-text text-muted"><?php echo substr($service['description'], 0, 100); ?>...</p>
                            <div class="d-flex justify-content-between align-items-center">
                                <span class="text-primary fw-bold">Rp <?php echo number_format($service['price'], 0, ',', '.'); ?>/orang</span>
                                <small class="text-muted">Kapasitas: <?php echo $service['capacity']; ?> orang</small>
                            </div>
                            <div class="mt-3">
                                <a href="user/reservation.php?service=<?php echo $service['id']; ?>" class="btn btn-primary w-100">
                                    <i class="fas fa-calendar-plus"></i> Pesan Sekarang
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                <?php endwhile; ?>
            </div>
            <div class="text-center mt-4">
                <a href="user/reservation.php" class="btn btn-outline-primary">Lihat Semua Layanan</a>
            </div>
        </div>
    </section>

    <!-- How to Reserve -->
    <section class="py-5 bg-light">
        <div class="container">
            <h2 class="text-center mb-5">Cara Reservasi Mudah</h2>
            <div class="row">
                <div class="col-md-3 text-center mb-4">
                    <div class="step-number">1</div>
                    <h5>Pilih Layanan</h5>
                    <p class="text-muted">Pilih destinasi wisata yang ingin dikunjungi dari berbagai pilihan</p>
                </div>
                <div class="col-md-3 text-center mb-4">
                    <div class="step-number">2</div>
                    <h5>Tentukan Jadwal</h5>
                    <p class="text-muted">Pilih tanggal kunjungan dan jumlah peserta yang akan ikut</p>
                </div>
                <div class="col-md-3 text-center mb-4">
                    <div class="step-number">3</div>
                    <h5>Pembayaran</h5>
                    <p class="text-muted">Lakukan pembayaran dengan metode yang tersedia secara aman</p>
                </div>
                <div class="col-md-3 text-center mb-4">
                    <div class="step-number">4</div>
                    <h5>Konfirmasi</h5>
                    <p class="text-muted">Dapatkan konfirmasi dan kode booking untuk check-in</p>
                </div>
            </div>
        </div>
    </section>

    <!-- About Section -->
    <section id="about" class="py-5">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-md-6">
                    <h2>Tentang HealingKuy!</h2>
                    <p class="lead">Platform reservasi wisata terdepan di Indonesia</p>
                    <p>HealingKuy! hadir untuk memudahkan Anda dalam merencanakan dan memesan perjalanan wisata ke berbagai destinasi terbaik di Indonesia. Dengan teknologi terkini dan layanan terpercaya, kami berkomitmen memberikan pengalaman berwisata yang tak terlupakan.</p>
                    <ul class="list-unstyled">
                        <li><i class="fas fa-check text-primary me-2"></i> 100+ Destinasi Wisata</li>
                        <li><i class="fas fa-check text-primary me-2"></i> Pembayaran Aman</li>
                        <li><i class="fas fa-check text-primary me-2"></i> Support 24/7</li>
                        <li><i class="fas fa-check text-primary me-2"></i> Harga Terjangkau</li>
                    </ul>
                </div>
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-body text-center p-5">
                            <i class="fas fa-users fa-4x text-primary mb-3"></i>
                            <h4>Bergabung dengan Komunitas</h4>
                            <p class="text-muted">Sudah ribuan traveler bergabung dan merasakan kemudahan berwisata bersama kami</p>
                            <div class="row text-center mt-4">
                                <div class="col-4">
                                    <h3 class="text-primary">10K+</h3>
                                    <small>Pengguna</small>
                                </div>
                                <div class="col-4">
                                    <h3 class="text-primary">50+</h3>
                                    <small>Destinasi</small>
                                </div>
                                <div class="col-4">
                                    <h3 class="text-primary">98%</h3>
                                    <small>Kepuasan</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Contact Section -->
    <section id="contact" class="py-5 bg-light position-relative">
        <!-- Background Alam Animasi untuk Contact -->
        <div id="background-contact-alam" class="position-absolute top-0 start-0 w-100 h-100" style="z-index: -1; overflow: hidden;">
            <!-- Langit Gradasi Bergerak -->
            <div class="sky-gradient"></div>
            <!-- Awan Bergerak -->
            <div class="cloud cloud1"></div>
            <div class="cloud cloud2"></div>
            <div class="cloud cloud3"></div>
            <!-- Efek Cahaya Matahari -->
            <div class="sun-glow"></div>
            <!-- Partikel Daun/Embun -->
            <canvas id="particles-canvas-contact" class="position-absolute top-0 start-0 w-100 h-100"></canvas>
        </div>
        <div class="container position-relative" style="z-index: 1;">
            <h2 class="text-center mb-5">Kontak Kami</h2>
            <div class="row">
                <div class="col-md-6">
                    <div class="card h-100">
                        <div class="card-body">
                            <h5 class="card-title">Informasi Kontak</h5>
                            <div class="mb-3">
                                <i class="fas fa-map-marker-alt text-primary me-2"></i>
                                <strong>Alamat:</strong> Jl. Wisata No. 123, Jakarta 12345
                            </div>
                            <div class="mb-3">
                                <i class="fas fa-phone text-primary me-2"></i>
                                <strong>Telepon:</strong> (021) 1234-5678
                            </div>
                            <div class="mb-3">
                                <i class="fas fa-envelope text-primary me-2"></i>
                                <strong>Email:</strong> info@healingkuy
                            </div>
                            <div class="mb-3">
                                <i class="fas fa-clock text-primary me-2"></i>
                                <strong>Jam Operasional:</strong> Senin - Minggu, 08:00 - 17:00 WIB
                            </div>
                            <div class="mt-4">
                                <h6>Follow Kami:</h6>
                                <div class="social-links">
                                    <a href="#" class="text-primary me-3"><i class="fab fa-facebook fa-2x"></i></a>
                                    <a href="#" class="text-primary me-3"><i class="fab fa-twitter fa-2x"></i></a>
                                    <a href="#" class="text-primary me-3"><i class="fab fa-instagram fa-2x"></i></a>
                                    <a href="#" class="text-primary"><i class="fab fa-youtube fa-2x"></i></a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">Kirim Pesan</h5>
                            <form>
                                <div class="mb-3">
                                    <label for="name" class="form-label">Nama Lengkap</label>
                                    <input type="text" class="form-control" id="name" required>
                                </div>
                                <div class="mb-3">
                                    <label for="email" class="form-label">Email</label>
                                    <input type="email" class="form-control" id="email" required>
                                </div>
                                <div class="mb-3">
                                    <label for="subject" class="form-label">Subjek</label>
                                    <input type="text" class="form-control" id="subject" required>
                                </div>
                                <div class="mb-3">
                                    <label for="message" class="form-label">Pesan</label>
                                    <textarea class="form-control" id="message" rows="4" required></textarea>
                                </div>
                                <button type="submit" class="btn btn-primary" style="background: linear-gradient(90deg, #2ecc71, #1abc9c); border: none;">Kirim Pesan</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-dark text-white py-4">
        <div class="container">
            <div class="row">
                <div class="col-md-4">
                    <h5><i class="fas fa-mountain"></i> HealingKuy!</h5>
                    <p>Platform reservasi wisata terpercaya di Indonesia. Nikmati kemudahan pemesanan dan pengalaman berwisata yang tak terlupakan.</p>
                </div>
                <div class="col-md-4">
                    <h5>Quick Links</h5>
                    <ul class="list-unstyled">
                        <li><a href="#home" class="text-white text-decoration-none">Home</a></li>
                        <li><a href="#services" class="text-white text-decoration-none">Layanan</a></li>
                        <li><a href="#about" class="text-white text-decoration-none">Tentang Kami</a></li>
                        <li><a href="user/blog.php" class="text-white text-decoration-none">Blog</a></li>
                    </ul>
                </div>
                <div class="col-md-4">
                    <h5>Kontak</h5>
                    <p><i class="fas fa-map-marker-alt me-2"></i> Jakarta, Indonesia</p>
                    <p><i class="fas fa-phone me-2"></i> (021) 1234-5678</p>
                    <p><i class="fas fa-envelope me-2"></i> info@healingkuy</p>
                </div>
            </div>
            <hr class="my-4">
            <div class="text-center">
                <p>&copy; 2024 HealingKuy! - All rights reserved</p>
            </div>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Smooth scroll untuk navigasi
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                document.querySelector(this.getAttribute('href')).scrollIntoView({
                    behavior: 'smooth'
                });
            });
        });

        // Background image rotation
        var images = <?php echo json_encode($images); ?>;
        var currentIndex = 0;
        var heroSection = document.querySelector('.hero-section');

        function changeBackground() {
            currentIndex = (currentIndex + 1) % images.length;
            heroSection.style.backgroundImage = 'linear-gradient(rgba(0,0,0,0.6), rgba(0,0,0,0.6)), url("' + images[currentIndex] + '")';
        }

        // Start rotating every 1.5 seconds
        setInterval(changeBackground, 1500);

        // Partikel Daun/Embun untuk Background Alam
        const canvas = document.getElementById('particles-canvas');
        const ctx = canvas.getContext('2d');
        let particles = [];

        function resizeCanvas() {
            canvas.width = canvas.offsetWidth;
            canvas.height = canvas.offsetHeight;
        }

        function createParticle() {
            return {
                x: Math.random() * canvas.width,
                y: Math.random() * canvas.height,
                vx: (Math.random() - 0.5) * 0.5,
                vy: (Math.random() - 0.5) * 0.5,
                size: Math.random() * 3 + 1,
                opacity: Math.random() * 0.5 + 0.2,
                type: Math.random() > 0.5 ? 'leaf' : 'dew'
            };
        }

        function updateParticles() {
            particles.forEach(p => {
                p.x += p.vx;
                p.y += p.vy;

                if (p.x < 0 || p.x > canvas.width) p.vx *= -1;
                if (p.y < 0 || p.y > canvas.height) p.vy *= -1;
            });
        }

        function drawParticles() {
            ctx.clearRect(0, 0, canvas.width, canvas.height);
            particles.forEach(p => {
                ctx.save();
                ctx.globalAlpha = p.opacity;
                ctx.fillStyle = p.type === 'leaf' ? '#228B22' : '#87CEEB';

                if (p.type === 'leaf') {
                    // Gambar daun sederhana
                    ctx.beginPath();
                    ctx.ellipse(p.x, p.y, p.size, p.size * 1.5, 0, 0, Math.PI * 2);
                    ctx.fill();
                } else {
                    // Gambar embun
                    ctx.beginPath();
                    ctx.arc(p.x, p.y, p.size, 0, Math.PI * 2);
                    ctx.fill();
                }
                ctx.restore();
            });
        }

        function animateParticles() {
            updateParticles();
            drawParticles();
            requestAnimationFrame(animateParticles);
        }

        // Inisialisasi
        resizeCanvas();
        window.addEventListener('resize', resizeCanvas);

        for (let i = 0; i < 50; i++) {
            particles.push(createParticle());
        }

        animateParticles();

        // Partikel Daun/Embun untuk Background Contact
        const canvasContact = document.getElementById('particles-canvas-contact');
        const ctxContact = canvasContact.getContext('2d');
        let particlesContact = [];

        function resizeCanvasContact() {
            canvasContact.width = canvasContact.offsetWidth;
            canvasContact.height = canvasContact.offsetHeight;
        }

        function createParticleContact() {
            return {
                x: Math.random() * canvasContact.width,
                y: Math.random() * canvasContact.height,
                vx: (Math.random() - 0.5) * 0.5,
                vy: (Math.random() - 0.5) * 0.5,
                size: Math.random() * 3 + 1,
                opacity: Math.random() * 0.5 + 0.2,
                type: Math.random() > 0.5 ? 'leaf' : 'dew'
            };
        }

        function updateParticlesContact() {
            particlesContact.forEach(p => {
                p.x += p.vx;
                p.y += p.vy;

                if (p.x < 0 || p.x > canvasContact.width) p.vx *= -1;
                if (p.y < 0 || p.y > canvasContact.height) p.vy *= -1;
            });
        }

        function drawParticlesContact() {
            ctxContact.clearRect(0, 0, canvasContact.width, canvasContact.height);
            particlesContact.forEach(p => {
                ctxContact.save();
                ctxContact.globalAlpha = p.opacity;
                ctxContact.fillStyle = p.type === 'leaf' ? '#228B22' : '#87CEEB';

                if (p.type === 'leaf') {
                    // Gambar daun sederhana
                    ctxContact.beginPath();
                    ctxContact.ellipse(p.x, p.y, p.size, p.size * 1.5, 0, 0, Math.PI * 2);
                    ctxContact.fill();
                } else {
                    // Gambar embun
                    ctxContact.beginPath();
                    ctxContact.arc(p.x, p.y, p.size, 0, Math.PI * 2);
                    ctxContact.fill();
                }
                ctxContact.restore();
            });
        }

        function animateParticlesContact() {
            updateParticlesContact();
            drawParticlesContact();
            requestAnimationFrame(animateParticlesContact);
        }

        // Inisialisasi Contact
        resizeCanvasContact();
        window.addEventListener('resize', resizeCanvasContact);

        for (let i = 0; i < 50; i++) {
            particlesContact.push(createParticleContact());
        }

        animateParticlesContact();
    </script>
    <script src="assets/js/script.js"></script>
</body>
</html>
