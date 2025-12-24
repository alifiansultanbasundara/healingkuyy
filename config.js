const mysql = require('mysql2/promise');

const dbConfig = {
  host: 'localhost',
  user: 'root',
  password: '',
  database: 'healingkuy',
  waitForConnections: true,
  connectionLimit: 10,
  queueLimit: 0
};

let pool;

async function getConnection() {
  if (!pool) {
    pool = mysql.createPool(dbConfig);
  }
  return pool;
}

function sanitize(data) {
  return data.toString().replace(/[<>\"']/g, '');
}

function isLoggedIn(req) {
  return req.session && req.session.user_id;
}

function isAdmin(req) {
  return req.session && req.session.role === 'admin';
}

function generateBookingCode() {
  const date = new Date();
  const dateStr = date.getFullYear().toString() +
                  (date.getMonth() + 1).toString().padStart(2, '0') +
                  date.getDate().toString().padStart(2, '0');
  const randomStr = Math.random().toString(36).substring(2, 8).toUpperCase();
  return 'HK' + dateStr + randomStr;
}

async function logActivity(userId, action, description = '', req) {
  try {
    const connection = await getConnection();
    const ip = req.ip || req.connection.remoteAddress;
    const userAgent = req.get('User-Agent') || '';

    await connection.execute(
      'INSERT INTO activity_logs (user_id, action, description, ip_address, user_agent) VALUES (?, ?, ?, ?, ?)',
      [userId, action, description, ip, userAgent]
    );
  } catch (error) {
    console.error('Error logging activity:', error);
  }
}

function checkAuth(req, res, next) {
  if (!isLoggedIn(req)) {
    req.session.error = "Silakan login terlebih dahulu";
    return res.redirect('/login');
  }
  next();
}

function checkAdmin(req, res, next) {
  checkAuth(req, res, () => {
    if (!isAdmin(req)) {
      req.session.error = "Akses ditolak. Halaman untuk admin saja.";
      return res.redirect('/');
    }
    next();
  });
}

async function getUserInfo(userId) {
  try {
    const connection = await getConnection();
    const [rows] = await connection.execute('SELECT * FROM users WHERE id = ?', [userId]);
    return rows[0] || null;
  } catch (error) {
    console.error('Error getting user info:', error);
    return null;
  }
}

function formatCurrency(amount) {
  return 'Rp ' + amount.toString().replace(/\B(?=(\d{3})+(?!\d))/g, '.');
}

async function getServiceInfo(serviceId) {
  try {
    const connection = await getConnection();
    const [rows] = await connection.execute('SELECT * FROM services WHERE id = ?', [serviceId]);
    return rows[0] || null;
  } catch (error) {
    console.error('Error getting service info:', error);
    return null;
  }
}

module.exports = {
  getConnection,
  sanitize,
  isLoggedIn,
  isAdmin,
  generateBookingCode,
  logActivity,
  checkAuth,
  checkAdmin,
  getUserInfo,
  formatCurrency,
  getServiceInfo
};
