<?php
include 'koneksi.php';

$id = $_GET['id'];

// Ambil id_buku dari peminjaman
$ambil = mysqli_query($koneksi, "SELECT id_buku FROM peminjaman WHERE id_peminjaman = $id");
$data = mysqli_fetch_assoc($ambil);
$id_buku = $data['id_buku'];

// Update status dan stok
$updateStatus = "UPDATE peminjaman SET status = 'dikembalikan' WHERE id_peminjaman = $id";
$updateStok = "UPDATE buku SET stok = stok + 1 WHERE id = $id_buku";

if (mysqli_query($koneksi, $updateStatus) && mysqli_query($koneksi, $updateStok)) {
    echo "<script>alert('Buku berhasil dikembalikan!'); window.location.href = document.referrer;</script>";
} else {
    echo "Gagal mengembalikan buku: " . mysqli_error($koneksi);
}
?>
