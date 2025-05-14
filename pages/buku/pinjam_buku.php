<?php
include '../../config/koneksi.php';
session_start();
$id_buku = $_GET['id'];
$q1 = mysqli_query($koneksi, "SELECT * FROM buku WHERE id = $id_buku");
$buku = mysqli_fetch_assoc($q1);
$q2 = mysqli_query($koneksi, "SELECT * FROM users WHERE username = '" . $_SESSION['username'] . "'");
$user = mysqli_fetch_assoc($q2);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Form Peminjaman Buku</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5">
    <h2 class="text-center mb-4">Form Peminjaman Buku</h2>
    <form action="../../config/pinjam_config.php" method="post" class="shadow p-4 rounded bg-light">
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

        <div class="mb-3">
            <label class="form-label">Tanggal Kembali:</label>
            <input type="date" class="form-control" name="tanggal_kembali" required>
        </div>

        <button type="submit" name="submit" class="btn btn-primary w-100">Pinjam</button>
    </form>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
