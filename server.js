const express = require('express');
const session = require('express-session');
const bodyParser = require('body-parser');
const path = require('path');
const bcrypt = require('bcryptjs');
const methodOverride = require('method-override');

// Import routes
const indexRoutes = require('./routes/index');
const authRoutes = require('./routes/auth');
const userRoutes = require('./routes/user');
const adminRoutes = require('./routes/admin');
const apiRoutes = require('./routes/api');

const app = express();
const PORT = process.env.PORT || 3000;

// Set view engine
app.set('view engine', 'ejs');
app.set('views', path.join(__dirname, 'views'));

// Middleware
app.use(express.static(path.join(__dirname, 'assets')));
app.use(bodyParser.urlencoded({ extended: true }));
app.use(bodyParser.json());
app.use(methodOverride('_method'));

// Session configuration
app.use(session({
  secret: 'healingkuy-secret-key-2024',
  resave: false,
  saveUninitialized: false,
  cookie: {
    secure: false, // Set to true in production with HTTPS
    maxAge: 24 * 60 * 60 * 1000 // 24 hours
  }
}));

// Global variables middleware
app.use((req, res, next) => {
  res.locals.isLoggedIn = req.session && req.session.user_id;
  res.locals.isAdmin = req.session && req.session.role === 'admin';
  res.locals.user = req.session || {};
  next();
});

// Routes
app.use('/', indexRoutes);
app.use('/', authRoutes);
app.use('/user', userRoutes);
app.use('/admin', adminRoutes);
app.use('/api', apiRoutes);

// Logout route
app.get('/logout', (req, res) => {
  req.session.destroy((err) => {
    if (err) {
      console.error('Logout error:', err);
    }
    res.redirect('/');
  });
});

// 404 handler
app.use((req, res) => {
  res.status(404).render('error', { error: 'Halaman tidak ditemukan' });
});

// Error handler
app.use((err, req, res, next) => {
  console.error('Server error:', err);
  res.status(500).render('error', { error: 'Terjadi kesalahan server' });
});

// Start server
app.listen(PORT, () => {
  console.log(`HealingKuy server running on port ${PORT}`);
  console.log(`Visit: http://localhost:${PORT}`);
});
