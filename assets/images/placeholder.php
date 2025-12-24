<?php
// File: assets/images/placeholder.php
header('Content-Type: image/svg+xml');

$width = isset($_GET['w']) ? intval($_GET['w']) : 400;
$height = isset($_GET['h']) ? intval($_GET['h']) : 300;
$text = isset($_GET['t']) ? urldecode($_GET['t']) : 'HealingKuy!.id';
$bg_color = isset($_GET['bg']) ? $_GET['bg'] : '2E8B57';
$text_color = isset($_GET['color']) ? $_GET['color'] : 'ffffff';

// Colors inspired by Indonesian nature
$nature_colors = ['2E8B57', '1E90FF', 'FFD700', '8B4513', '3CB371', '0066CC', 'D4AF37', 'A0522D'];
$random_color = $nature_colors[array_rand($nature_colors)];

$final_bg_color = $bg_color !== 'random' ? $bg_color : $random_color;

echo '<?xml version="1.0" encoding="UTF-8"?>
<svg width="' . $width . '" height="' . $height . '" xmlns="http://www.w3.org/2000/svg">
  <defs>
    <linearGradient id="gradient" x1="0%" y1="0%" x2="100%" y2="100%">
      <stop offset="0%" stop-color="#' . $final_bg_color . '" stop-opacity="0.8"/>
      <stop offset="100%" stop-color="#' . $final_bg_color . '" stop-opacity="0.6"/>
    </linearGradient>
  </defs>
  
  <rect width="100%" height="100%" fill="url(#gradient)"/>
  
  <!-- Decorative elements -->
  <circle cx="20%" cy="30%" r="40" fill="rgba(255,255,255,0.1)"/>
  <circle cx="80%" cy="70%" r="60" fill="rgba(255,255,255,0.1)"/>
  <circle cx="60%" cy="20%" r="30" fill="rgba(255,255,255,0.1)"/>
  
  <text x="50%" y="50%" dominant-baseline="middle" text-anchor="middle" 
        font-family="Arial, sans-serif" font-size="18" fill="#' . $text_color . '" font-weight="bold">
    ' . htmlspecialchars($text) . '
  </text>
  
  <text x="50%" y="65%" dominant-baseline="middle" text-anchor="middle" 
        font-family="Arial, sans-serif" font-size="12" fill="#' . $text_color . '" opacity="0.8">
    Indonesia Tourism
  </text>
</svg>';
?>