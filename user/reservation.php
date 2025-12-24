 <?php
// File: user/reservation.php
include '../includes/config.php';
include '../includes/auth.php';

if (!isLoggedIn() || isAdmin()) {
    redirect('../login.php');
}

// Get services
$services = $pdo->query("SELECT * FROM services WHERE status = 'active'")->fetchAll();

// Get hotels
$hotels = $pdo->query("SELECT * FROM hotels WHERE status = 'active'")->fetchAll();

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $service_id = sanitize($_POST['service_id']);
    $hotel_id = sanitize($_POST['hotel_id']);
    $reservation_date = sanitize($_POST['reservation_date']);
    $guests = sanitize($_POST['guests']);
    $days = sanitize($_POST['days']);
    $nights = sanitize($_POST['nights']);
    $special_requests = sanitize($_POST['special_requests']);

    // Validasi
    if (empty($service_id) || empty($hotel_id) || empty($reservation_date) || empty($guests) || empty($days) || empty($nights)) {
        $error = "Semua field wajib diisi!";
    } else {
        // Cek ketersediaan layanan
        $stmt = $pdo->prepare("SELECT * FROM services WHERE id = ?");
        $stmt->execute([$service_id]);
        $service = $stmt->fetch();

        // Cek ketersediaan hotel
        $stmt = $pdo->prepare("SELECT * FROM hotels WHERE id = ?");
        $stmt->execute([$hotel_id]);
        $hotel = $stmt->fetch();

        if ($service && $hotel) {
            // Cek kapasitas
            if ($guests > $service['capacity']) {
                $error = "Jumlah tamu melebihi kapasitas. Kapasitas maksimal: " . $service['capacity'] . " orang.";
            } else {
                $service_total = $service['price'] * $guests;
                $hotel_total = $hotel['price_per_night'] * $nights;
                $total_price = $service_total + $hotel_total;
                $booking_code = generateBookingCode();

                $stmt = $pdo->prepare("
                    INSERT INTO reservations (user_id, service_id, hotel_id, booking_code, reservation_date, guests, days, nights, service_total, hotel_total, total_price, special_requests) 
                    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
                ");

                if ($stmt->execute([$_SESSION['user_id'], $service_id, $hotel_id, $booking_code, $reservation_date, $guests, $days, $nights, $service_total, $hotel_total, $total_price, $special_requests])) {
                    logActivity('reservation', 'New reservation created: ' . $booking_code);
                    $success = "Reservasi berhasil! Kode Booking: <strong>$booking_code</strong>. Silakan tunggu konfirmasi dari admin.";
                } else {
                    $error = "Terjadi kesalahan saat membuat reservasi.";
                }
            }
        } else {
            $error = "Layanan atau hotel tidak ditemukan.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reservasi - HealingKuy!.id</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        .card {
            border: none;
            border-radius: 15px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        }
        .btn-primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: none;
        }
        .service-card {
            transition: transform 0.3s;
            cursor: pointer;
        }
        .service-card:hover {
            transform: translateY(-5px);
        }
        .service-card.selected {
            border: 2px solid #667eea;
            background-color: #f8f9ff;
        }
        .hotel-card {
            transition: transform 0.3s;
            cursor: pointer;
        }
        .hotel-card:hover {
            transform: translateY(-5px);
        }
        .hotel-card.selected {
            border: 2px solid #28a745;
            background-color: #f8fff8;
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
        <div class="row">
            <div class="col-md-8 mx-auto">
                <div class="card">
                    <div class="card-header bg-primary text-white">
                        <h4 class="mb-0"><i class="fas fa-calendar-plus me-2"></i>Form Reservasi</h4>
                    </div>
                    <div class="card-body p-4">
                        <?php if($error): ?>
                        <div class="alert alert-danger"><?php echo $error; ?></div>
                        <?php endif; ?>
                        
                        <?php if($success): ?>
                        <div class="alert alert-success"><?php echo $success; ?></div>
                        <?php endif; ?>

                        <form method="POST" id="reservationForm">
                            <!-- Pilih Layanan -->
                            <div class="mb-4">
                                <h5 class="mb-3">1. Pilih Layanan</h5>
                                <div class="row">
                                    <?php foreach($services as $service): ?>
                                    <div class="col-md-6 mb-3">
                                        <div class="card service-card h-100" data-service-id="<?php echo $service['id']; ?>" data-price="<?php echo $service['price']; ?>" data-capacity="<?php echo $service['capacity']; ?>">
                                            <div class="card-body">
                                                <h6 class="card-title"><?php echo $service['name']; ?></h6>
                                                <p class="card-text text-muted small mb-2"><?php echo $service['location']; ?></p>
                                                <p class="card-text small"><?php echo substr($service['description'], 0, 100); ?>...</p>
                                                <div class="d-flex justify-content-between align-items-center">
                                                    <span class="text-primary fw-bold">Rp <?php echo number_format($service['price'], 0, ',', '.'); ?>/orang</span>
                                                    <small class="text-muted">Kapasitas: <?php echo $service['capacity']; ?></small>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <?php endforeach; ?>
                                </div>
                                <input type="hidden" name="service_id" id="service_id" required>
                            </div>

                            <!-- Pilih Hotel -->
                            <div class="mb-4">
                                <h5 class="mb-3">3. Pilih Hotel</h5>
                                <div class="row" id="hotel-selection">
                                    <div class="col-12">
                                        <p class="text-muted">Silakan pilih layanan terlebih dahulu untuk melihat pilihan hotel.</p>
                                    </div>
                                </div>
                                <input type="hidden" name="hotel_id" id="hotel_id" required>
                            </div>

                            <!-- Detail Reservasi -->
                            <div class="mb-4">
                                <h5 class="mb-3">2. Detail Reservasi</h5>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="reservation_date" class="form-label">Tanggal Reservasi</label>
                                            <input type="date" class="form-control" id="reservation_date" name="reservation_date" min="<?php echo date('Y-m-d'); ?>" required>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="guests" class="form-label">Jumlah Tamu</label>
                                            <input type="number" class="form-control" id="guests" name="guests" min="1" required>
                                            <div class="form-text" id="capacity-info">Kapasitas: -</div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="days" class="form-label">Jumlah Hari</label>
                                            <input type="number" class="form-control" id="days" name="days" min="1" value="1" required>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="nights" class="form-label">Jumlah Malam</label>
                                            <input type="number" class="form-control" id="nights" name="nights" min="1" value="1" required>
                                        </div>
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <label for="special_requests" class="form-label">Permintaan Khusus (opsional)</label>
                                    <textarea class="form-control" id="special_requests" name="special_requests" rows="3" placeholder="Contoh: Makanan vegetarian, akses kursi roda, dll."></textarea>
                                </div>
                            </div>

                            <!-- Ringkasan Biaya -->
                            <div class="mb-4">
                                <h5 class="mb-3">4. Ringkasan Biaya</h5>
                                <div class="card bg-light">
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <h6>Detail Layanan</h6>
                                                <p class="mb-1" id="service-summary">-</p>
                                                <small class="text-muted" id="location-summary">-</small>
                                                <h6 class="mt-3">Detail Hotel</h6>
                                                <p class="mb-1" id="hotel-summary">-</p>
                                                <small class="text-muted" id="hotel-description"></small>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="d-flex justify-content-between mb-2">
                                                    <span>Harga layanan per orang:</span>
                                                    <span id="price-per-person">Rp 0</span>
                                                </div>
                                                <div class="d-flex justify-content-between mb-2">
                                                    <span>Jumlah tamu:</span>
                                                    <span id="guest-count">0</span>
                                                </div>
                                                <div class="d-flex justify-content-between mb-2">
                                                    <span>Total hari:</span>
                                                    <span id="total-days">0</span>
                                                </div>
                                                <div class="d-flex justify-content-between mb-2">
                                                    <span>Total malam:</span>
                                                    <span id="total-nights">0</span>
                                                </div>
                                                <hr>
                                                <div class="d-flex justify-content-between mb-2">
                                                    <span>Subtotal layanan:</span>
                                                    <span id="subtotal-service">Rp 0</span>
                                                </div>
                                                <div class="d-flex justify-content-between mb-2">
                                                    <span>Subtotal hotel:</span>
                                                    <span id="subtotal-hotel">Rp 0</span>
                                                </div>
                                                <hr>
                                                <div class="d-flex justify-content-between fw-bold fs-5">
                                                    <span>Total Biaya:</span>
                                                    <span id="total-cost">Rp 0</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <button type="submit" class="btn btn-primary btn-lg w-100 py-3">
                                <i class="fas fa-calendar-check me-2"></i>Buat Reservasi
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        let selectedService = null;
        let selectedHotel = null;
        const serviceCards = document.querySelectorAll('.service-card');
        const serviceIdInput = document.getElementById('service_id');
        const hotelIdInput = document.getElementById('hotel_id');
        const guestsInput = document.getElementById('guests');
        const daysInput = document.getElementById('days');
        const nightsInput = document.getElementById('nights');
        const capacityInfo = document.getElementById('capacity-info');
        const serviceSummary = document.getElementById('service-summary');
        const locationSummary = document.getElementById('location-summary');
        const hotelSummary = document.getElementById('hotel-summary');
        const hotelDescription = document.getElementById('hotel-description');
        const pricePerPerson = document.getElementById('price-per-person');
        const guestCount = document.getElementById('guest-count');
        const totalDays = document.getElementById('total-days');
        const totalNights = document.getElementById('total-nights');
        const subtotalService = document.getElementById('subtotal-service');
        const subtotalHotel = document.getElementById('subtotal-hotel');
        const totalCost = document.getElementById('total-cost');

        // Data hotel dari PHP
        const hotels = <?php echo json_encode($hotels); ?>;

        // Pilih layanan
        serviceCards.forEach(card => {
            card.addEventListener('click', function() {
                serviceCards.forEach(c => c.classList.remove('selected'));
                this.classList.add('selected');

                selectedService = {
                    id: this.dataset.serviceId,
                    name: this.querySelector('.card-title').textContent,
                    location: this.querySelector('.text-muted').textContent,
                    price: parseInt(this.dataset.price),
                    capacity: parseInt(this.dataset.capacity)
                };

                serviceIdInput.value = selectedService.id;
                serviceSummary.textContent = selectedService.name;
                locationSummary.textContent = selectedService.location;
                capacityInfo.textContent = `Kapasitas: ${selectedService.capacity} orang`;

                // Reset hotel selection
                selectedHotel = null;
                hotelIdInput.value = '';
                hotelSummary.textContent = '-';
                hotelDescription.textContent = '';

                // Load hotels for this service
                loadHotelsForService(selectedService.id);

                updateSummary();
            });
        });

        // Load hotels for selected service
        function loadHotelsForService(serviceId) {
            const hotelSelection = document.getElementById('hotel-selection');
            const serviceHotels = hotels.filter(hotel => hotel.service_id == serviceId);

            if (serviceHotels.length === 0) {
                hotelSelection.innerHTML = '<div class="col-12"><p class="text-muted">Tidak ada hotel tersedia untuk layanan ini.</p></div>';
                return;
            }

            let html = '';
            serviceHotels.forEach(hotel => {
                const imageUrl = hotel.image ? `../assets/images/${hotel.image}` : '../assets/images/placeholder.php';
                html += `
                    <div class="col-md-6 mb-3">
                        <div class="card hotel-card h-100" data-hotel-id="${hotel.id}" data-price="${hotel.price_per_night}" data-description="${hotel.description}">
                            <img src="${imageUrl}" class="card-img-top" alt="${hotel.name}" style="height: 200px; object-fit: cover;">
                            <div class="card-body">
                                <h6 class="card-title">${hotel.name}</h6>
                                <p class="card-text small">${hotel.description}</p>
                                <div class="d-flex justify-content-between align-items-center">
                                    <span class="text-success fw-bold">Rp ${parseInt(hotel.price_per_night).toLocaleString('id-ID')}/malam</span>
                                </div>
                            </div>
                        </div>
                    </div>
                `;
            });

            hotelSelection.innerHTML = html;

            // Add event listeners to hotel cards
            document.querySelectorAll('.hotel-card').forEach(card => {
                card.addEventListener('click', function() {
                    document.querySelectorAll('.hotel-card').forEach(c => c.classList.remove('selected'));
                    this.classList.add('selected');

                    selectedHotel = {
                        id: this.dataset.hotelId,
                        name: this.querySelector('.card-title').textContent,
                        price: parseInt(this.dataset.price),
                        description: this.dataset.description
                    };

                    hotelIdInput.value = selectedHotel.id;
                    hotelSummary.textContent = selectedHotel.name;
                    hotelDescription.textContent = selectedHotel.description;

                    updateSummary();
                });
            });
        }

        // Update ringkasan
        function updateSummary() {
            if (!selectedService) return;

            const guests = guestsInput.value ? parseInt(guestsInput.value) : 0;
            const days = daysInput.value ? parseInt(daysInput.value) : 0;
            const nights = nightsInput.value ? parseInt(nightsInput.value) : 0;

            const servicePrice = selectedService.price;
            const hotelPrice = selectedHotel ? selectedHotel.price : 0;

            const subtotalServiceAmount = servicePrice * guests;
            const subtotalHotelAmount = hotelPrice * nights;
            const total = subtotalServiceAmount + subtotalHotelAmount;

            pricePerPerson.textContent = `Rp ${servicePrice.toLocaleString('id-ID')}`;
            guestCount.textContent = guests;
            totalDays.textContent = days;
            totalNights.textContent = nights;
            subtotalService.textContent = `Rp ${subtotalServiceAmount.toLocaleString('id-ID')}`;
            subtotalHotel.textContent = `Rp ${subtotalHotelAmount.toLocaleString('id-ID')}`;
            totalCost.textContent = `Rp ${total.toLocaleString('id-ID')}`;
        }

        // Event listeners
        guestsInput.addEventListener('input', updateSummary);
        daysInput.addEventListener('input', updateSummary);
        nightsInput.addEventListener('input', updateSummary);

        // Validasi form
        document.getElementById('reservationForm').addEventListener('submit', function(e) {
            if (!selectedService) {
                e.preventDefault();
                alert('Silakan pilih layanan terlebih dahulu');
                return;
            }

            if (!selectedHotel) {
                e.preventDefault();
                alert('Silakan pilih hotel terlebih dahulu');
                return;
            }

            const guests = parseInt(guestsInput.value);
            if (guests > selectedService.capacity) {
                e.preventDefault();
                alert(`Jumlah tamu melebihi kapasitas. Kapasitas maksimal: ${selectedService.capacity} orang`);
                return;
            }
        });
    </script>
</body>
</html>