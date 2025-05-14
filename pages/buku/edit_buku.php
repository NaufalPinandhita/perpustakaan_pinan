<?php
require_once '../../config/koneksi.php';
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: ../../pages/login.php");
    exit;
}
if ($_SESSION['role'] != 'admin') {
    header("Location: ../../pages/dashboard.php");
    exit;
}

$success = isset($_GET['success']) ? $_GET['success'] : '';
$error = isset($_GET['error']) ? $_GET['error'] : '';

$id = isset($_GET['id']) ? $_GET['id'] : null;

if (!$id) {
    $error = urlencode("ID tidak ditemukan");
    exit;
}
$result = mysqli_query($koneksi, "SELECT * FROM buku WHERE id='$id'");
$row = mysqli_fetch_assoc($result);

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
    $folderTujuan = realpath(__DIR__ . '/../../assets/img/covers/') . '/';
    $pathCover = $folderTujuan . $coverNama;

    if ($judulBuku && $pengarangBuku && $penerbitBuku && $tahunBuku && $genreBuku && $jumlahBuku) {

        if ($coverNama) {
            if (move_uploaded_file($coverTmp, $pathCover)) {
                $query = mysqli_query($koneksi, "UPDATE buku SET judul='$judulBuku', pengarang='$pengarangBuku', penerbit='$penerbitBuku', tahun_terbit='$tahunBuku', genre='$genreBuku', cover='$coverNama' WHERE id=$id");
            } else {
                $error = "Upload foto gagal";
            }
        } else {
            $query = mysqli_query($koneksi, "UPDATE buku SET judul='$judulBuku', pengarang='$pengarangBuku', penerbit='$penerbitBuku', tahun_terbit='$tahunBuku', genre='$genreBuku' WHERE id=$id");
        }

        if ($query) {
            $success = "Data Buku Berhasil Diperbarui";
        } else {
            $error = "Data Buku Gagal Diperbarui";
        }
    } else {
        $error = "Data Buku Tidak Boleh Kosong";
    }
}

if (isset($_POST['hapus'])) {
    $query = mysqli_query($koneksi, "DELETE FROM buku WHERE id=$id");
    if ($query) {
        $success = "Data Buku Berhasil Dihapus";
    } else {
        $error = "Data Buku Gagal Dihapus";
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-SgOJa3DmI69IUzQ2PVdRZhwQ+dy64/BUtbMJw1MZ8t5HZApcHrRKUc4W0kG879m7" crossorigin="anonymous">
    <title>Add Book</title>
</head>

<body>
    <div class="mx-auto" style="width: 60%; margin-top: 20px;">
        <div class="card">
            <h5 class="card-header">Tambah Buku</h5>
            <div class="card-body">
                <?php if ($success): ?>
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <?= htmlspecialchars($success) ?>
                </div>
                <?php endif; ?>

                <?php if ($error): ?>
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <?= htmlspecialchars($error) ?>
                </div>
                <?php endif; ?>
                <form method="post" enctype="multipart/form-data">
                    <div class="mb-3 row">
                        <label for="judul" class="col-sm-2 col-form-label">Judul Buku </label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" name="judul" value='<?php echo $row['judul']; ?>'>
                        </div>
                    </div>
                    <div class="mb-3 row">
                        <label for="pengarang" class="col-sm-2 col-form-label">Pengarang </label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" name="pengarang" value='<?php echo $row['pengarang']; ?>'>
                        </div>
                    </div>
                    <div class="mb-3 row">
                        <label for="penerbit" class="col-sm-2 col-form-label">Penerbit </label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" name="penerbit" value='<?php echo $row['penerbit']; ?>'>
                        </div>
                    </div>
                    <div class="mb-3 row">
                        <label for="tahun" class="col-sm-2 col-form-label">Tahun Terbit </label>
                        <div class="col-sm-10">
                            <input type="number" maxlength="4" class="form-control" name="tahun_terbit" value='<?php echo $row['tahun_terbit']; ?>'>
                        </div>
                    </div>
                    <div class="mb-3 row">
                        <label for="genre" class="col-sm-2 col-form-label">Genre </label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" name="genre" value='<?php echo $row['genre']; ?>'>
                        </div>
                    </div>
                    <div class="mb-3 row">
                        <label for="stok" class="col-sm-2 col-form-label">Jumlah Stok </label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" name="stok" value='<?php echo $row['stok']; ?>'>
                        </div>
                    </div>
                    <div class="mb-3 row">
                        <label for="cover" class="col-sm-2 col-form-label">Cover Image </label>
                        <div class="col-sm-10">
                            <input type="file" class="form-control" name="cover" accept="image/*">
                        </div>
                    </div>
                    <button type="submit" class="btn btn-secondary" name="submit">Submit</button>
                    <button type="submit" class="btn btn-secondary"><a href="list_buku.php" style="color: white; text-decoration: none;">List Buku</a></button>
                    <button type="submit" class="btn btn-danger" name="hapus">Hapus Buku</button>
                </form>
            </div>
        </div>
    </div>
</body>

</html>