<?php
require_once 'koneksi.php';

// membuat variabel
$judulBuku = "";
$pengarangBuku = "";
$penerbitBuku = "";
$deskripsiBuku = "";
$tahunBuku = "";
$jumlahBuku = "";
$fotoBuku = "";

$success = "";
$error = "";

//ambil data
if (isset($_POST['submit'])) {
    $judulBuku      = $_POST['judul'];
    $pengarangBuku  = $_POST['pengarang'];
    $penerbitBuku   = $_POST['penerbit'];
    $deskripsiBuku  = $_POST['deskripsi'];
    $tahunBuku      = $_POST['tahun_terbit'];
    $jumlahBuku     = $_POST['stok'];
    $genreIds       = isset($_POST['genre']) ? $_POST['genre'] : []; // array of genre IDs

    // Proses upload gambar
    $coverNama = $_FILES['cover']['name'];
    $coverTmp = $_FILES['cover']['tmp_name'];
    $folderTujuan = __DIR__ . '/../assets/img/covers/';
    $pathCover = $folderTujuan . $coverNama;

    if ($judulBuku && $pengarangBuku && $penerbitBuku && $deskripsiBuku && $tahunBuku && $jumlahBuku && $coverNama && !empty($genreIds)) {

        if (move_uploaded_file($coverTmp, $pathCover)) {
            $sql1 = "INSERT INTO buku (judul, pengarang, penerbit, deskripsi, tahun_terbit, stok, cover) VALUES (?, ?, ?, ?, ?, ?, ?)";
            $stmt = mysqli_prepare($koneksi, $sql1);
            mysqli_stmt_bind_param($stmt, "sssssis", $judulBuku, $pengarangBuku, $penerbitBuku, $deskripsiBuku, $tahunBuku, $jumlahBuku, $coverNama);
            $query = mysqli_stmt_execute($stmt);

            if ($query) {
                $id_buku = mysqli_insert_id($koneksi);

                // Insert ke tabel relasi buku_genre
                foreach ($genreIds as $id_genre) {
                    $sqlRelasi = "INSERT INTO buku_genre (id_buku, id_genre) VALUES (?, ?)";
                    $stmtRelasi = mysqli_prepare($koneksi, $sqlRelasi);
                    mysqli_stmt_bind_param($stmtRelasi, "ii", $id_buku, $id_genre);
                    mysqli_stmt_execute($stmtRelasi);
                }

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