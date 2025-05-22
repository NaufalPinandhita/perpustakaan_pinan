<?php
include '../../config/database.php';
session_start();

if ($_SESSION['role'] != 'user') {
    header("Location: ../dashboard.php");
    exit;
}
$id_buku = $_GET['id'];

$q1 = mysqli_query($koneksi, "SELECT * FROM buku WHERE id = $id_buku");
$buku = mysqli_fetch_assoc($q1);
$q2 = mysqli_query($koneksi, "SELECT * FROM users WHERE username = '" . $_SESSION['username'] . "'");
$user = mysqli_fetch_assoc($q2);
if ($buku['stok'] <= 0) {
    echo "<script>alert('Stok buku tidak tersedia'); window.location.href = '../books/book_list.php';</script>";
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Form Peminjaman Buku</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../../assets/css/theme.css">
</head>
<body class="text-dark">
<div class="container mt-5">
    <h2 class="text-center mb-4">Form Peminjaman Buku</h2>
    <form action="../../config/book/borrow.php" method="post" class="shadow p-4 rounded bg-light">
        <input type="hidden" name="id_buku" value="<?= $buku['id']; ?>">

        <div class="mb-3">
            <label class="form-label">Judul Buku:</label>
            <input type="text" class="form-control" value="<?= $buku['judul']; ?>" readonly>
        </div>

        <div class="mb-3">
            <label class="form-label">Nama Peminjam:</label>
            <input type="text" class="form-control" name="nama_peminjam" value="<?= $user['username']; ?>" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Tanggal Pinjam:</label>
            <input type="date" class="form-control" name="tanggal_pinjam" required>
        </div>

        <button type="submit" name="submit" class="btn btn-primary">Pinjam</button>
        <a href="book_list.php" class="btn btn-primary text-white">Kembali</a>
    </form>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
