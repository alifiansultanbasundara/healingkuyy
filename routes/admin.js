const express = require('express');
const router = express.Router();
const { getConnection, checkAdmin, sanitize, logActivity, formatCurrency } = require('../config');

// Apply admin middleware to all admin routes
router.use(checkAdmin);

// Dashboard
router.get('/dashboard', async (req, res) => {
  try {
    const connection = await getConnection();
    const today = new Date().toISOString().split('T')[0];

    // Get statistics
    const [reservationsToday] = await connection.execute(
      "SELECT COUNT(*) as count FROM reservations WHERE DATE(created_at) = ?",
      [today]
    );

    const [pendingCancellations] = await connection.execute(
      "SELECT COUNT(*) as count FROM cancellations WHERE status = 'pending'"
    );

    const [totalRevenue] = await connection.execute(
      "SELECT SUM(total_price) as revenue FROM reservations WHERE status = 'confirmed' AND DATE(created_at) = ?",
      [today]
    );

    const [totalUsers] = await connection.execute(
      "SELECT COUNT(*) as count FROM users WHERE role = 'user'"
    );

    // Recent transactions
    const [recentTransactions] = await connection.execute(`
      SELECT r.*, u.name as user_name, s.name as service_name
      FROM reservations r
      JOIN users u ON r.user_id = u.id
      JOIN services s ON r.service_id = s.id
      ORDER BY r.created_at DESC
      LIMIT 10
    `);

    // Monthly revenue data
    const [monthlyRevenue] = await connection.execute(`
      SELECT
        MONTH(created_at) as month,
        SUM(total_price) as revenue
      FROM reservations
      WHERE status = 'confirmed'
      AND YEAR(created_at) = YEAR(CURDATE())
      GROUP BY MONTH(created_at)
      ORDER BY month
    `);

    // Process monthly revenue data
    const monthlyData = Array(12).fill(0);
    if (monthlyRevenue) {
      monthlyRevenue.forEach(data => {
        monthlyData[data.month - 1] = data.revenue || 0;
      });
    }

    res.render('admin/dashboard', {
      reservations_today: reservationsToday[0].count,
      pending_cancellations: pendingCancellations[0].count,
      total_revenue: totalRevenue[0].revenue || 0,
      total_users: totalUsers[0].count,
      recent_transactions: recentTransactions,
      monthly_revenue: monthlyRevenue,
      monthly_data: JSON.stringify(monthlyData),
      formatCurrency: formatCurrency
    });
  } catch (error) {
    console.error('Admin dashboard error:', error);
    res.status(500).render('error', { error: 'Failed to load admin dashboard' });
  }
});

// Manage users
router.get('/manage_users', async (req, res) => {
  try {
    const connection = await getConnection();
    const [users] = await connection.execute("SELECT * FROM users ORDER BY created_at DESC");

    res.render('admin/manage_users', { users: users });
  } catch (error) {
    console.error('Manage users error:', error);
    res.status(500).render('error', { error: 'Failed to load users' });
  }
});

// Add user
router.post('/manage_users', async (req, res) => {
  try {
    const { name, email, password, role, phone, address } = req.body;
    const connection = await getConnection();

    // Validate
    if (!name || !email || !password) {
      req.session.error = "Nama, email, dan password wajib diisi!";
      return res.redirect('/admin/manage_users');
    }

    // Check if email exists
    const [existingUsers] = await connection.execute('SELECT id FROM users WHERE email = ?', [email]);
    if (existingUsers.length > 0) {
      req.session.error = "Email sudah terdaftar!";
      return res.redirect('/admin/manage_users');
    }

    const hashedPassword = await bcrypt.hash(password, 10);

    await connection.execute(
      'INSERT INTO users (name, email, password, role, phone, address) VALUES (?, ?, ?, ?, ?, ?)',
      [sanitize(name), sanitize(email), hashedPassword, sanitize(role), sanitize(phone || ''), sanitize(address || '')]
    );

    await logActivity(req.session.user_id, 'add_user', `Added new user: ${email}`, req);

    req.session.success = "User berhasil ditambahkan!";
    res.redirect('/admin/manage_users');
  } catch (error) {
    console.error('Add user error:', error);
    req.session.error = "Terjadi kesalahan saat menambahkan user.";
    res.redirect('/admin/manage_users');
  }
});

// Edit user
router.post('/edit_user', async (req, res) => {
  try {
    const { id, name, email, role, phone, address } = req.body;
    const connection = await getConnection();

    // Validate
    if (!name || !email) {
      req.session.error = "Nama dan email wajib diisi!";
      return res.redirect('/admin/manage_users');
    }

    // Check if email exists (excluding current user)
    const [existingUsers] = await connection.execute('SELECT id FROM users WHERE email = ? AND id != ?', [email, id]);
    if (existingUsers.length > 0) {
      req.session.error = "Email sudah terdaftar!";
      return res.redirect('/admin/manage_users');
    }

    await connection.execute(
      'UPDATE users SET name = ?, email = ?, role = ?, phone = ?, address = ? WHERE id = ?',
      [sanitize(name), sanitize(email), sanitize(role), sanitize(phone || ''), sanitize(address || ''), id]
    );

    await logActivity(req.session.user_id, 'edit_user', `Updated user: ${email}`, req);

    req.session.success = "User berhasil diperbarui!";
    res.redirect('/admin/manage_users');
  } catch (error) {
    console.error('Edit user error:', error);
    req.session.error = "Terjadi kesalahan saat memperbarui user.";
    res.redirect('/admin/manage_users');
  }
});

// Delete user
router.post('/delete_user', async (req, res) => {
  try {
    const { id } = req.body;
    const connection = await getConnection();

    // Check if user has reservations
    const [reservations] = await connection.execute('SELECT COUNT(*) as count FROM reservations WHERE user_id = ?', [id]);
    if (reservations[0].count > 0) {
      req.session.error = "User tidak dapat dihapus karena memiliki riwayat reservasi.";
      return res.redirect('/admin/manage_users');
    }

    await connection.execute('DELETE FROM users WHERE id = ?', [id]);

    await logActivity(req.session.user_id, 'delete_user', `Deleted user ID: ${id}`, req);

    req.session.success = "User berhasil dihapus!";
    res.redirect('/admin/manage_users');
  } catch (error) {
    console.error('Delete user error:', error);
    req.session.error = "Terjadi kesalahan saat menghapus user.";
    res.redirect('/admin/manage_users');
  }
});

// Manage services
router.get('/manage_services', async (req, res) => {
  try {
    const connection = await getConnection();
    const [services] = await connection.execute("SELECT * FROM services ORDER BY created_at DESC");

    res.render('admin/manage_services', { services: services });
  } catch (error) {
    console.error('Manage services error:', error);
    res.status(500).render('error', { error: 'Failed to load services' });
  }
});

// Add service
router.post('/manage_services', async (req, res) => {
  try {
    const { name, description, location, price, capacity, status } = req.body;
    const connection = await getConnection();

    await connection.execute(
      'INSERT INTO services (name, description, location, price, capacity, status) VALUES (?, ?, ?, ?, ?, ?)',
      [sanitize(name), sanitize(description), sanitize(location), price, capacity, status]
    );

    await logActivity(req.session.user_id, 'add_service', `Added new service: ${name}`, req);

    req.session.success = "Layanan berhasil ditambahkan!";
    res.redirect('/admin/manage_services');
  } catch (error) {
    console.error('Add service error:', error);
    req.session.error = "Terjadi kesalahan saat menambahkan layanan.";
    res.redirect('/admin/manage_services');
  }
});

// Edit service
router.post('/edit_service', async (req, res) => {
  try {
    const { id, name, description, location, price, capacity, status } = req.body;
    const connection = await getConnection();

    await connection.execute(
      'UPDATE services SET name = ?, description = ?, location = ?, price = ?, capacity = ?, status = ? WHERE id = ?',
      [sanitize(name), sanitize(description), sanitize(location), price, capacity, status, id]
    );

    await logActivity(req.session.user_id, 'edit_service', `Updated service: ${name}`, req);

    req.session.success = "Layanan berhasil diperbarui!";
    res.redirect('/admin/manage_services');
  } catch (error) {
    console.error('Edit service error:', error);
    req.session.error = "Terjadi kesalahan saat memperbarui layanan.";
    res.redirect('/admin/manage_services');
  }
});

// Delete service
router.post('/delete_service', async (req, res) => {
  try {
    const { id } = req.body;
    const connection = await getConnection();

    await connection.execute('DELETE FROM services WHERE id = ?', [id]);

    await logActivity(req.session.user_id, 'delete_service', `Deleted service ID: ${id}`, req);

    req.session.success = "Layanan berhasil dihapus!";
    res.redirect('/admin/manage_services');
  } catch (error) {
    console.error('Delete service error:', error);
    req.session.error = "Terjadi kesalahan saat menghapus layanan.";
    res.redirect('/admin/manage_services');
  }
});

// Manage reservations
router.get('/manage_reservations', async (req, res) => {
  try {
    const connection = await getConnection();
    const [reservations] = await connection.execute(`
      SELECT r.*, u.name as user_name, u.email, s.name as service_name, s.location
      FROM reservations r
      JOIN users u ON r.user_id = u.id
      JOIN services s ON r.service_id = s.id
      ORDER BY r.created_at DESC
    `);

    res.render('admin/manage_reservations', { reservations: reservations });
  } catch (error) {
    console.error('Manage reservations error:', error);
    res.status(500).render('error', { error: 'Failed to load reservations' });
  }
});

// Update reservation status
router.post('/update_reservation', async (req, res) => {
  try {
    const { reservation_id, status } = req.body;
    const connection = await getConnection();

    await connection.execute('UPDATE reservations SET status = ? WHERE id = ?', [status, reservation_id]);

    await logActivity(req.session.user_id, 'update_reservation', `Updated reservation ID: ${reservation_id} to ${status}`, req);

    req.session.success = "Status reservasi berhasil diperbarui!";
    res.redirect('/admin/manage_reservations');
  } catch (error) {
    console.error('Update reservation error:', error);
    req.session.error = "Terjadi kesalahan saat memperbarui reservasi.";
    res.redirect('/admin/manage_reservations');
  }
});

// Reservation detail
router.get('/reservation_detail', async (req, res) => {
  try {
    const reservationId = req.query.id;
    if (!reservationId) {
      return res.redirect('/admin/manage_reservations');
    }

    const connection = await getConnection();
    const [reservations] = await connection.execute(`
      SELECT r.*, u.name as user_name, u.email, s.name as service_name,
             s.location, h.name as hotel_name, h.image as hotel_image
      FROM reservations r
      JOIN users u ON r.user_id = u.id
      JOIN services s ON r.service_id = s.id
      LEFT JOIN hotels h ON r.hotel_id = h.id
      WHERE r.id = ?
    `, [reservationId]);

    if (reservations.length === 0) {
      return res.redirect('/admin/manage_reservations');
    }

    res.render('admin/reservation_detail', {
      reservation: reservations[0],
      formatCurrency: formatCurrency
    });
  } catch (error) {
    console.error('Reservation detail error:', error);
    res.status(500).render('error', { error: 'Failed to load reservation detail' });
  }
});

// Manage cancellations
router.get('/manage_cancellations', async (req, res) => {
  try {
    const connection = await getConnection();
    const [cancellations] = await connection.execute(`
      SELECT c.*, r.booking_code, u.name as user_name, s.name as service_name
      FROM cancellations c
      JOIN reservations r ON c.reservation_id = r.id
      JOIN users u ON c.user_id = u.id
      JOIN services s ON r.service_id = s.id
      ORDER BY c.created_at DESC
    `);

    res.render('admin/manage_cancellations', { cancellations: cancellations });
  } catch (error) {
    console.error('Manage cancellations error:', error);
    res.status(500).render('error', { error: 'Failed to load cancellations' });
  }
});

// Update cancellation status
router.post('/update_cancellation', async (req, res) => {
  try {
    const { cancellation_id, status } = req.body;
    const connection = await getConnection();

    await connection.execute('UPDATE cancellations SET status = ? WHERE id = ?', [status, cancellation_id]);

    await logActivity(req.session.user_id, 'update_cancellation', `Updated cancellation ID: ${cancellation_id} to ${status}`, req);

    req.session.success = "Status pembatalan berhasil diperbarui!";
    res.redirect('/admin/manage_cancellations');
  } catch (error) {
    console.error('Update cancellation error:', error);
    req.session.error = "Terjadi kesalahan saat memperbarui pembatalan.";
    res.redirect('/admin/manage_cancellations');
  }
});

// Reports
router.get('/reports', async (req, res) => {
  try {
    const connection = await getConnection();

    // Statistics
    const [totalReservations] = await connection.execute("SELECT COUNT(*) as count FROM reservations");
    const [totalRevenue] = await connection.execute("SELECT SUM(total_price) as revenue FROM reservations WHERE status = 'confirmed'");
    const [totalUsers] = await connection.execute("SELECT COUNT(*) as count FROM users WHERE role = 'user'");
    const [totalServices] = await connection.execute("SELECT COUNT(*) as count FROM services WHERE status = 'active'");
    const [totalHotels] = await connection.execute("SELECT COUNT(*) as count FROM hotels WHERE status = 'active'");

    // Revenue breakdown
    const [serviceRevenue] = await connection.execute("SELECT SUM(service_total) as revenue FROM reservations WHERE status = 'confirmed'");
    const [hotelRevenue] = await connection.execute("SELECT SUM(hotel_total) as revenue FROM reservations WHERE status = 'confirmed'");

    // Monthly revenue
    const [monthlyRevenue] = await connection.execute(`
      SELECT MONTH(created_at) as month, SUM(total_price) as revenue
      FROM reservations
      WHERE status = 'confirmed' AND YEAR(created_at) = YEAR(CURDATE())
      GROUP BY MONTH(created_at)
      ORDER BY month
    `);

    // Status distribution
    const [statusDistribution] = await connection.execute(`
      SELECT status, COUNT(*) as count FROM reservations GROUP BY status
    `);

    // Top services
    const [topServices] = await connection.execute(`
      SELECT s.name, COUNT(r.id) as reservation_count
      FROM services s LEFT JOIN reservations r ON s.id = r.service_id
      GROUP BY s.id, s.name
      ORDER BY reservation_count DESC LIMIT 5
    `);

    // Revenue by service
    const [revenueByService] = await connection.execute(`
      SELECT s.name, SUM(r.total_price) as revenue
      FROM services s LEFT JOIN reservations r ON s.id = r.service_id AND r.status = 'confirmed'
      GROUP BY s.id, s.name
      ORDER BY revenue DESC LIMIT 5
    `);

    // Top hotels
    const [topHotels] = await connection.execute(`
      SELECT h.name, SUM(r.hotel_total) as revenue
      FROM hotels h LEFT JOIN reservations r ON h.id = r.hotel_id AND r.status = 'confirmed'
      GROUP BY h.id, h.name
      ORDER BY revenue DESC LIMIT 5
    `);

    res.render('admin/reports', {
      total_reservations: totalReservations[0].count,
      total_revenue: totalRevenue[0].revenue || 0,
      total_users: totalUsers[0].count,
      total_services: totalServices[0].count,
      service_revenue: serviceRevenue[0].revenue || 0,
      hotel_revenue: hotelRevenue[0].revenue || 0,
      monthly_revenue: monthlyRevenue,
      status_distribution: statusDistribution,
      top_services: topServices,
      revenue_by_service: revenueByService,
      top_hotels: topHotels,
      formatCurrency: formatCurrency
    });
  } catch (error) {
    console.error('Reports error:', error);
    res.status(500).render('error', { error: 'Failed to load reports' });
  }
});

// Manage blog
router.get('/manage_blog', async (req, res) => {
  try {
    const connection = await getConnection();
    const [blogs] = await connection.execute("SELECT * FROM blogs ORDER BY created_at DESC");

    res.render('admin/manage_blog', { blogs: blogs });
  } catch (error) {
    console.error('Manage blog error:', error);
    res.status(500).render('error', { error: 'Failed to load blogs' });
  }
});

// Activity logs
router.get('/activity_logs', async (req, res) => {
  try {
    const connection = await getConnection();
    const [logs] = await connection.execute(`
      SELECT l.*, u.name as user_name
      FROM activity_logs l
      LEFT JOIN users u ON l.user_id = u.id
      ORDER BY l.created_at DESC
      LIMIT 100
    `);

    res.render('admin/activity_logs', { logs: logs });
  } catch (error) {
    console.error('Activity logs error:', error);
    res.status(500).render('error', { error: 'Failed to load activity logs' });
  }
});

module.exports = router;
