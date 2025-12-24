<?php
// File: logout.php
include 'includes/config.php';

session_destroy();
redirect('index.php');
?>