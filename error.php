<?php
// File: error.php
$error_codes = [
    400 => ['title' => 'Bad Request', 'message' => 'Permintaan tidak valid.'],
    401 => ['title' => 'Unauthorized', 'message' => 'Anda harus login untuk mengakses halaman ini.'],
    403 => ['title' => 'Forbidden', 'message' => 'Anda tidak memiliki akses ke halaman ini.'],
    404 => ['title' => 'Page Not Found', 'message' => 'Halaman yang Anda cari tidak ditemukan.'],
    500 => ['title' => 'Internal Server Error', 'message' => 'Terjadi kesalahan pada server.']
];

$error_code = isset($_GET['code']) ? intval($_GET['code']) : 404;
$error = $error_codes[$error_code] ?? $error_codes[404];
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Error <?php echo $error_code; ?> - HealingKuy!.id</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="assets/css/style.css" rel="stylesheet">
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container">
            <a class="navbar-brand fw-bold" href="index.php">
                <i class="fas fa-mountain"></i> HealingKuy!.id
            </a>
        </div>
    </nav>

    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-6 text-center">
                <div class="card">
                    <div class="card-body py-5">
                        <i class="fas fa-exclamation-triangle fa-5x text-warning mb-4"></i>
                        <h1 class="display-4 fw-bold text-danger">Error <?php echo $error_code; ?></h1>
                        <h3 class="mb-4"><?php echo $error['title']; ?></h3>
                        <p class="lead mb-4"><?php echo $error['message']; ?></p>
                        
                        <div class="d-flex gap-2 justify-content-center">
                            <a href="javascript:history.back()" class="btn btn-outline-primary">
                                <i class="fas fa-arrow-left me-2"></i>Kembali
                            </a>
                            <a href="index.php" class="btn btn-primary">
                                <i class="fas fa-home me-2"></i>Home
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="assets/js/script.js"></script>
</body>
</html>