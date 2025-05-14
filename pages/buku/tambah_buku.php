<?php
require_once '../../config/add_config.php';
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
                <form action="../../config/add_config.php" method="post" enctype="multipart/form-data">
                    <div class="mb-3 row">
                        <label for="judul" class="col-sm-2 col-form-label">Judul Buku </label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" name="judul">
                        </div>
                    </div>
                    <div class="mb-3 row">
                        <label for="pengarang" class="col-sm-2 col-form-label">Pengarang </label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" name="pengarang">
                        </div>
                    </div>
                    <div class="mb-3 row">
                        <label for="penerbit" class="col-sm-2 col-form-label">Penerbit </label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" name="penerbit">
                        </div>
                    </div>
                    <div class="mb-3 row">
                        <label for="tahun" class="col-sm-2 col-form-label">Tahun Terbit </label>
                        <div class="col-sm-10">
                            <input type="number" class="form-control" name="tahun_terbit" maxlength="4" min="1900" max="<?= date('Y') ?>">
                        </div>
                    </div>
                    <div class="mb-3 row">
                        <label for="genre" class="col-sm-2 col-form-label">Genre </label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" name="genre">
                        </div>
                    </div>
                    <div class="mb-3 row">
                        <label for="stok" class="col-sm-2 col-form-label">Jumlah Stok </label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" name="stok">
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
                </form>
            </div>
        </div>
    </div>
</body>

</html>