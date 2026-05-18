<?php
// Debug: cek apakah request sampai ke sini
error_log('REQUEST URI: ' . $_SERVER['REQUEST_URI']);

require __DIR__ . '/../public/index.php';