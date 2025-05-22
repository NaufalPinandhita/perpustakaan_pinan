<?php
include '../database.php';

if (isset($_POST['submit'])) {
    $id_buku = $_POST['id_buku'];
    $nama_peminjam = $_POST['nama_peminjam'];
    $tanggal_pinjam = $_POST['tanggal_pinjam'];

    // Cek stok
    $cek = mysqli_query($koneksi, "SELECT stok FROM buku WHERE id = $id_buku");
    $data = mysqli_fetch_assoc($cek);

    if ($data['stok'] <= 0) {
        echo "<script>alert('Stok buku habis!'); window.history.back();</script>";
        exit;
    }

    // Masukkan ke tabel peminjaman tanpa tanggal_kembali
    $query = "INSERT INTO peminjaman (id_buku, nama_peminjam, tanggal_pinjam, status) VALUES ('$id_buku', '$nama_peminjam', '$tanggal_pinjam', 'dipinjam')";

    // Kurangi stok
    $updateStok = "UPDATE buku SET stok = stok - 1 WHERE id = $id_buku";

    if (mysqli_query($koneksi, $query) && mysqli_query($koneksi, $updateStok)) {
        echo "<script>alert('Peminjaman berhasil!'); window.location.href = '../../pages/books/book_list.php';</script>";
    } else {
        echo "Gagal meminjam buku: " . mysqli_error($koneksi);
    }
}
?>
