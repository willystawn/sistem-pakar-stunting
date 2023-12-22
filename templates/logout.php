<?php
session_start();

// Lakukan logika logout di sini, misalnya menghapus session atau membersihkan data pengguna

// Contoh logika logout: menghapus semua data session
session_unset();
session_destroy();

// Redirect ke halaman login setelah logout
header('Location: ../login');
exit();
?>