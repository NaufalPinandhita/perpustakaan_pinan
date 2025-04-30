<?php
// Konfigurasi database
$host = "localhost"; // Host database
$user = "root"; // Username default phpMyAdmin
$password = ""; // Password default (kosong jika belum diatur)
$database = "perpustakaan_pinan"; // Nama database

// Membuat koneksi
$koneksi = mysqli_connect($host, $user, $password, $database);

// Cek koneksi
if (!$koneksi) {
    die("Koneksi ke database gagal: " . mysqli_connect_error());
}
?>