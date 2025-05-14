<?php
require_once 'koneksi.php';

// membuat variabel
$judulBuku = "";
$pengarangBuku = "";
$penerbitBuku = "";
$tahunBuku = "";
$genreBuku = "";
$jumlahBuku = "";
$fotoBuku = "";

$success = "";
$error = "";

//ambil data
if (isset($_POST['submit'])) {
    $judulBuku      = $_POST['judul'];
    $pengarangBuku  = $_POST['pengarang'];
    $penerbitBuku   = $_POST['penerbit'];
    $tahunBuku      = $_POST['tahun_terbit'];
    $genreBuku      = $_POST['genre'];
    $jumlahBuku     = $_POST['stok'];

    // Proses upload gambar
    $coverNama = $_FILES['cover']['name'];
    $coverTmp = $_FILES['cover']['tmp_name'];
    $folderTujuan = __DIR__ . '/../assets/img/covers/';
    $pathCover = $folderTujuan . $coverNama;

    if ($judulBuku && $pengarangBuku && $penerbitBuku && $tahunBuku && $genreBuku && $jumlahBuku && $coverNama) {

        if (move_uploaded_file($coverTmp, $pathCover)) {
            $sql1 = "INSERT INTO buku (judul, pengarang, penerbit, tahun_terbit, genre, stok, cover) VALUES ('$judulBuku', '$pengarangBuku', '$penerbitBuku', '$tahunBuku', '$genreBuku', '$jumlahBuku', '$coverNama')";
            $query = mysqli_query($koneksi, $sql1);

            if ($query) {
                $success = urlencode("Data Buku Berhasil Ditambahkan");
                header("Location: ../pages/buku/tambah_buku.php?success=" . $success);
            } else {
                $error = urlencode("Data Buku Gagal Ditambahkan");
                header("Location: ../pages/buku/tambah_buku.php?error=" . $error);
            }
        } else {
            $error = urlencode("Upload foto gagal");
            header("Location: ../pages/buku/tambah_buku.php?error=" . $error);
        }
    } else {
        $error = urlencode("Data Buku Tidak Boleh Kosong");
        header("Location: ../pages/buku/tambah_buku.php?error=" . $error);
    }
}
?>