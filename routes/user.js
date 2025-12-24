const express = require('express');
const router = express.Router();
const { getConnection, checkAuth, sanitize, logActivity, generateBookingCode, formatCurrency } = require('../config');

// Apply auth middleware to all user routes
router.use(checkAuth);

// User dashboard
router.get('/dashboard', async (req, res) => {
  try {
    const connection = await getConnection();
    const userId = req.session.user_id;

    // Get user reservations
    const [reservations] = await connection.execute(`
      SELECT r.*, s.name as service_name, s.location, h.name as hotel_name
      FROM reservations r
      JOIN services s ON r.service_id = s.id
      LEFT JOIN hotels h ON r.hotel_id = h.id
      WHERE r.user_id = ?
      ORDER BY r.created_at DESC
    `, [userId]);

    res.render('user/dashboard', {
      reservations: reservations,
      formatCurrency: formatCurrency
    });
  } catch (error) {
    console.error('Dashboard error:', error);
    res.status(500).render('error', { error: 'Failed to load dashboard' });
  }
});

// Reservation page
router.get('/reservation', async (req, res) => {
  try {
    const connection = await getConnection();

    // Get services
    const [services] = await connection.execute("SELECT * FROM services WHERE status = 'active'");

    // Get hotels
    const [hotels] = await connection.execute("SELECT * FROM hotels WHERE status = 'active'");

    res.render('user/reservation', {
      services: services,
      hotels: hotels
    });
  } catch (error) {
    console.error('Reservation page error:', error);
    res.status(500).render('error', { error: 'Failed to load reservation page' });
  }
});

// Create reservation
router.post('/reservation', async (req, res) => {
  try {
    const {
      service_id, hotel_id, reservation_date, guests,
      days, nights, special_requests
    } = req.body;

    const userId = req.session.user_id;
    const connection = await getConnection();

    // Validate inputs
    if (!service_id || !hotel_id || !reservation_date || !guests || !days || !nights) {
      req.session.error = "Semua field wajib diisi!";
      return res.redirect('/user/reservation');
    }

    // Get service info
    const [services] = await connection.execute('SELECT * FROM services WHERE id = ?', [service_id]);
    const service = services[0];

    // Get hotel info
    const [hotels] = await connection.execute('SELECT * FROM hotels WHERE id = ?', [hotel_id]);
    const hotel = hotels[0];

    if (!service || !hotel) {
      req.session.error = "Layanan atau hotel tidak ditemukan.";
      return res.redirect('/user/reservation');
    }

    // Check capacity
    if (parseInt(guests) > service.capacity) {
      req.session.error = `Jumlah tamu melebihi kapasitas. Kapasitas maksimal: ${service.capacity} orang.`;
      return res.redirect('/user/reservation');
    }

    // Calculate prices
    const serviceTotal = service.price * parseInt(guests);
    const hotelTotal = hotel.price_per_night * parseInt(nights);
    const totalPrice = serviceTotal + hotelTotal;
    const bookingCode = generateBookingCode();

    // Insert reservation
    await connection.execute(`
      INSERT INTO reservations (
        user_id, service_id, hotel_id, booking_code, reservation_date,
        guests, days, nights, service_total, hotel_total, total_price,
        special_requests, created_at
      ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW())
    `, [
      userId, service_id, hotel_id, bookingCode, reservation_date,
      guests, days, nights, serviceTotal, hotelTotal, totalPrice,
      special_requests || ''
    ]);

    await logActivity(userId, 'reservation', `New reservation created: ${bookingCode}`, req);

    req.session.success = `Reservasi berhasil! Kode Booking: <strong>${bookingCode}</strong>. Silakan tunggu konfirmasi dari admin.`;
    res.redirect('/user/reservation');
  } catch (error) {
    console.error('Reservation creation error:', error);
    req.session.error = "Terjadi kesalahan saat membuat reservasi.";
    res.redirect('/user/reservation');
  }
});

// Reservation detail
router.get('/reservation_detail', async (req, res) => {
  try {
    const reservationId = req.query.id;
    const userId = req.session.user_id;

    if (!reservationId) {
      return res.redirect('/user/dashboard');
    }

    const connection = await getConnection();
    const [reservations] = await connection.execute(`
      SELECT r.*, u.name as user_name, u.email, s.name as service_name,
             s.location, h.name as hotel_name, h.image as hotel_image
      FROM reservations r
      JOIN users u ON r.user_id = u.id
      JOIN services s ON r.service_id = s.id
      LEFT JOIN hotels h ON r.hotel_id = h.id
      WHERE r.id = ? AND r.user_id = ?
    `, [reservationId, userId]);

    if (reservations.length === 0) {
      return res.redirect('/user/dashboard');
    }

    res.render('user/reservation_detail', {
      reservation: reservations[0],
      formatCurrency: formatCurrency
    });
  } catch (error) {
    console.error('Reservation detail error:', error);
    res.status(500).render('error', { error: 'Failed to load reservation detail' });
  }
});

// Blog page
router.get('/blog', async (req, res) => {
  try {
    const connection = await getConnection();
    const [blogs] = await connection.execute(
      "SELECT * FROM blogs WHERE status = 'published' ORDER BY created_at DESC"
    );

    res.render('user/blog', { blogs: blogs });
  } catch (error) {
    console.error('Blog page error:', error);
    res.status(500).render('error', { error: 'Failed to load blog' });
  }
});

// Blog detail
router.get('/blog_detail', async (req, res) => {
  try {
    const blogId = req.query.id;
    if (!blogId) {
      return res.redirect('/user/blog');
    }

    const connection = await getConnection();
    const [blogs] = await connection.execute('SELECT * FROM blogs WHERE id = ? AND status = ?', [blogId, 'published']);

    if (blogs.length === 0) {
      return res.redirect('/user/blog');
    }

    res.render('user/blog_detail', { blog: blogs[0] });
  } catch (error) {
    console.error('Blog detail error:', error);
    res.status(500).render('error', { error: 'Failed to load blog detail' });
  }
});

// Cancellation request
router.post('/cancellation', async (req, res) => {
  try {
    const { reservation_id, reason } = req.body;
    const userId = req.session.user_id;

    const connection = await getConnection();

    // Check if reservation exists and belongs to user
    const [reservations] = await connection.execute(
      'SELECT * FROM reservations WHERE id = ? AND user_id = ?',
      [reservation_id, userId]
    );

    if (reservations.length === 0) {
      req.session.error = "Reservasi tidak ditemukan.";
      return res.redirect('/user/dashboard');
    }

    const reservation = reservations[0];

    // Check if already cancelled
    if (reservation.status === 'cancelled') {
      req.session.error = "Reservasi sudah dibatalkan.";
      return res.redirect('/user/dashboard');
    }

    // Insert cancellation request
    await connection.execute(`
      INSERT INTO cancellations (reservation_id, user_id, reason, status, created_at)
      VALUES (?, ?, ?, 'pending', NOW())
    `, [reservation_id, userId, reason]);

    await logActivity(userId, 'cancellation_request', `Cancellation requested for reservation ${reservation.booking_code}`, req);

    req.session.success = "Permintaan pembatalan telah dikirim. Tunggu konfirmasi dari admin.";
    res.redirect('/user/dashboard');
  } catch (error) {
    console.error('Cancellation error:', error);
    req.session.error = "Terjadi kesalahan saat memproses pembatalan.";
    res.redirect('/user/dashboard');
  }
});

// Profile page
router.get('/profile', async (req, res) => {
  try {
    const userId = req.session.user_id;
    const connection = await getConnection();
    const [users] = await connection.execute('SELECT * FROM users WHERE id = ?', [userId]);

    if (users.length === 0) {
      return res.redirect('/logout');
    }

    res.render('user/profile', { user: users[0] });
  } catch (error) {
    console.error('Profile error:', error);
    res.status(500).render('error', { error: 'Failed to load profile' });
  }
});

// Update profile
router.post('/profile', async (req, res) => {
  try {
    const { name, email, phone, address, current_password, new_password } = req.body;
    const userId = req.session.user_id;

    const connection = await getConnection();
    const [users] = await connection.execute('SELECT * FROM users WHERE id = ?', [userId]);
    const user = users[0];

    // Check current password if changing password
    if (new_password) {
      if (!current_password) {
        req.session.error = "Password saat ini wajib diisi untuk mengubah password.";
        return res.redirect('/user/profile');
      }

      const isValidCurrentPassword = await bcrypt.compare(current_password, user.password);
      if (!isValidCurrentPassword) {
        req.session.error = "Password saat ini salah.";
        return res.redirect('/user/profile');
      }

      if (new_password.length < 6) {
        req.session.error = "Password baru minimal 6 karakter.";
        return res.redirect('/user/profile');
      }
    }

    // Update user info
    let updateData = {
      name: sanitize(name),
      email: sanitize(email),
      phone: sanitize(phone || ''),
      address: sanitize(address || '')
    };

    let query = 'UPDATE users SET name = ?, email = ?, phone = ?, address = ?';
    let params = [updateData.name, updateData.email, updateData.phone, updateData.address];

    if (new_password) {
      const hashedPassword = await bcrypt.hash(new_password, 10);
      query += ', password = ?';
      params.push(hashedPassword);
    }

    query += ' WHERE id = ?';
    params.push(userId);

    await connection.execute(query, params);

    // Update session
    req.session.name = updateData.name;
    req.session.email = updateData.email;

    await logActivity(userId, 'profile_update', 'Profile updated', req);

    req.session.success = "Profil berhasil diperbarui.";
    res.redirect('/user/profile');
  } catch (error) {
    console.error('Profile update error:', error);
    req.session.error = "Terjadi kesalahan saat memperbarui profil.";
    res.redirect('/user/profile');
  }
});

// Check-in
router.post('/checkin', async (req, res) => {
  try {
    const { reservation_id } = req.body;
    const userId = req.session.user_id;

    const connection = await getConnection();

    // Check if reservation exists and belongs to user
    const [reservations] = await connection.execute(
      'SELECT * FROM reservations WHERE id = ? AND user_id = ? AND status = ?',
      [reservation_id, userId, 'confirmed']
    );

    if (reservations.length === 0) {
      req.session.error = "Reservasi tidak ditemukan atau belum dikonfirmasi.";
      return res.redirect('/user/dashboard');
    }

    // Update status to completed
    await connection.execute(
      'UPDATE reservations SET status = ? WHERE id = ?',
      ['completed', reservation_id]
    );

    await logActivity(userId, 'checkin', `Checked in for reservation ${reservations[0].booking_code}`, req);

    req.session.success = "Check-in berhasil! Selamat menikmati perjalanan.";
    res.redirect('/user/dashboard');
  } catch (error) {
    console.error('Check-in error:', error);
    req.session.error = "Terjadi kesalahan saat check-in.";
    res.redirect('/user/dashboard');
  }
});

module.exports = router;
