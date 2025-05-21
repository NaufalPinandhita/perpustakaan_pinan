<?php
include 'koneksi.php';

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    echo "Parameter id tidak valid.";
    exit;
}

$id = intval($_GET['id']);

// Ambil id_buku dari peminjaman
$stmt = $koneksi->prepare("SELECT id_buku FROM peminjaman WHERE id_peminjaman = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$data = $result->fetch_assoc();
$stmt->close();

if (!$data) {
    echo "Data peminjaman tidak ditemukan.";
    exit;
}

$id_buku = $data['id_buku'];

// Update status dan stok
$updateStatus = $koneksi->prepare("UPDATE peminjaman SET status = 'dikembalikan' WHERE id_peminjaman = ?");
$updateStatus->bind_param("i", $id);

$updateStok = $koneksi->prepare("UPDATE buku SET stok = stok + 1 WHERE id = ?");
$updateStok->bind_param("i", $id_buku);

$tanggal_kembali = date('Y-m-d');
$updateTanggal = $koneksi->prepare("UPDATE peminjaman SET tanggal_kembali = ? WHERE id_peminjaman = ?");
$updateTanggal->bind_param("si", $tanggal_kembali, $id);
$updateTanggal->execute();
$updateTanggal->close();

if ($updateStatus->execute() && $updateStok->execute()) {
    $updateStatus->close();
    $updateStok->close();
    echo "<script>alert('Buku berhasil dikembalikan!'); window.location.href = document.referrer;</script>";
} else {
    echo "Gagal mengembalikan buku: " . $koneksi->error;
}
?>
