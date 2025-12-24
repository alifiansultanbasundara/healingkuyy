const express = require('express');
const router = express.Router();
const { getConnection } = require('../config');

// Home page
router.get('/', async (req, res) => {
  try {
    const connection = await getConnection();

    // Fetch service images for slideshow
    const [images] = await connection.execute(
      "SELECT image FROM services WHERE status = 'active' AND image != ''"
    );

    let imageUrls = images.map(img => 'assets/images/' + img.image);
    if (imageUrls.length === 0) {
      imageUrls = ['https://images.unsplash.com/photo-1469474968028-56623f02e42e']; // fallback
    }

    // Fetch services for display
    const [services] = await connection.execute(
      "SELECT * FROM services WHERE status = 'active' LIMIT 6"
    );

    res.render('index', {
      images: imageUrls,
      services: services
    });
  } catch (error) {
    console.error('Error loading home page:', error);
    res.status(500).render('error', { error: 'Failed to load page' });
  }
});

// Error page
router.get('/error', (req, res) => {
  res.render('error', { error: req.query.error || 'An error occurred' });
});

module.exports = router;
