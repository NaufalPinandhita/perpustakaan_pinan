<?php
include '../../config/koneksi.php';
session_start();
if (isset($_SESSION['admin'])) {
    header("Location: ../../index.php");
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
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link rel="stylesheet" href="../../assets/sidebar.css">
    <title>Add Book</title>
</head>

<body>
    <div class="sidebar">
        <div class="text-center mb-4">
            <img src="../../assets/img/avatar.jpg" alt="Admin" class="rounded-circle" width="80">
            <h5 class="mt-2"><?php echo $_SESSION['username']?></h5>
            <p><span class="badge bg-success">Online</span></p>
        </div>
        <a href="../dashboard.php"><i class="fas fa-home me-2"></i>Dashboard</a>
        <a href="list_buku.php" class="active"><i class="fas fa-book me-2"></i>Kelola Buku</a>
        <a href="daftar_pinjaman.php"><i class="fas fa-sync-alt me-2"></i>Peminjaman</a>
        <a href="list_user.php"><i class="fas fa-users me-2"></i>Daftar User</a>
        <a href="../signup.php"><i class="fas fa-user-plus me-2"></i>Tambah User</a>
        <a href="../../config/logout.php"><i class="fas fa-sign-out-alt me-2"></i>Logout</a>
    </div>
    <div class="content">
        <div class="mx-auto" style="width: 100%; margin-top: 20px;">
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
                                <input type="text" class="form-control" name="judul" value="">
                            </div>
                        </div>
                        <div class="mb-3 row">
                            <label for="pengarang" class="col-sm-2 col-form-label">Pengarang </label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" name="pengarang" value="">
                            </div>
                        </div>
                        <div class="mb-3 row">
                            <label for="penerbit" class="col-sm-2 col-form-label">Penerbit </label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" name="penerbit" value="">
                            </div>
                        </div>
                        <div class="mb-3 row">
                            <label for="deskripsi" class="col-sm-2 col-form-label">Deskripsi </label>
                            <div class="col-sm-10">
                                <textarea class="form-control" name="deskripsi" rows="5"></textarea>
                            </div>
                        </div>
                        <div class="mb-3 row">
                            <label for="tahun" class="col-sm-2 col-form-label">Tahun Terbit </label>
                            <div class="col-sm-10">
                                <input type="number" maxlength="4" class="form-control" name="tahun_terbit" value="">
                            </div>
                        </div>
                        <?php
                        // Ambil data genre dari database
                        $query_genre = mysqli_query($koneksi, "SELECT id, nama_genre FROM genre");
                        ?>
                        <div class="mb-3 row">
                            <label for="genre" class="col-sm-2 col-form-label">Genre </label>
                            <div class="col-sm-10">
                                <div class="row">
                                    <?php
                                    $genres = [];
                                    while ($row = mysqli_fetch_assoc($query_genre)) {
                                        $genres[] = $row;
                                    }
                                    $total = count($genres);
                                    $per_col = ceil($total / 3);
                                    ?>
                                    <div class="col-md-4">
                                        <?php for ($i = 0; $i < $per_col; $i++): ?>
                                            <?php if (isset($genres[$i])): ?>
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" name="genre[]" value="<?= htmlspecialchars($genres[$i]['id']) ?>" id="genre<?= $genres[$i]['id'] ?>">
                                                    <label class="form-check-label" for="genre<?= $genres[$i]['id'] ?>">
                                                        <?= htmlspecialchars($genres[$i]['nama_genre']) ?>
                                                    </label>
                                                </div>
                                            <?php endif; ?>
                                        <?php endfor; ?>
                                    </div>
                                    <div class="col-md-4">
                                        <?php for ($i = $per_col; $i < 2 * $per_col; $i++): ?>
                                            <?php if (isset($genres[$i])): ?>
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" name="genre[]" value="<?= htmlspecialchars($genres[$i]['id']) ?>" id="genre<?= $genres[$i]['id'] ?>">
                                                    <label class="form-check-label" for="genre<?= $genres[$i]['id'] ?>">
                                                        <?= htmlspecialchars($genres[$i]['nama_genre']) ?>
                                                    </label>
                                                </div>
                                            <?php endif; ?>
                                        <?php endfor; ?>
                                    </div>
                                    <div class="col-md-4">
                                        <?php for ($i = 2 * $per_col; $i < $total; $i++): ?>
                                            <?php if (isset($genres[$i])): ?>
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" name="genre[]" value="<?= htmlspecialchars($genres[$i]['id']) ?>" id="genre<?= $genres[$i]['id'] ?>">
                                                    <label class="form-check-label" for="genre<?= $genres[$i]['id'] ?>">
                                                        <?= htmlspecialchars($genres[$i]['nama_genre']) ?>
                                                    </label>
                                                </div>
                                            <?php endif; ?>
                                        <?php endfor; ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="mb-3 row">
                            <label for="stok" class="col-sm-2 col-form-label">Jumlah Stok </label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" name="stok" value="">
                            </div>
                        </div>
                        <div class="mb-3 row">
                            <label for="cover" class="col-sm-2 col-form-label">Cover Image </label>
                            <div class="col-sm-10">
                                <input type="file" class="form-control" name="cover" accept="image/*">
                            </div>
                        </div>
                        <button type="submit" class="btn btn-secondary" name="submit">Submit</button>
                        <a href="../buku/list_buku.php" class="btn btn-secondary">List Buku</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
</body>

</html>