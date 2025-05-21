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

$id = isset($_GET['id']) ? intval($_GET['id']) : null;

if (!$id) {
    $error = urlencode("ID tidak ditemukan");
    exit;
}
$result = mysqli_query($koneksi, "SELECT * FROM buku WHERE id='$id'");
$dataBuku = mysqli_fetch_assoc($result);

if (!$dataBuku) {
    echo "Data buku tidak ditemukan.";
    exit;
}

// Ambil genre yang sudah dipilih
$genre_terpilih = [];
if (!empty($dataBuku['genre'])) {
    $genre_terpilih = explode(',', $dataBuku['genre']);
}

if (isset($_POST['submit'])) {
    $judulBuku      = mysqli_real_escape_string($koneksi, $_POST['judul']);
    $pengarangBuku  = mysqli_real_escape_string($koneksi, $_POST['pengarang']);
    $penerbitBuku   = mysqli_real_escape_string($koneksi, $_POST['penerbit']);
    $deskripsiBuku  = mysqli_real_escape_string($koneksi, $_POST['deskripsi']);
    $tahunBuku      = intval($_POST['tahun_terbit']);
    $genreBuku      = isset($_POST['genre']) ? implode(',', $_POST['genre']) : '';
    $jumlahBuku     = intval($_POST['stok']);

    // Proses upload gambar
    $coverNama = $_FILES['cover']['name'];
    $coverTmp = $_FILES['cover']['tmp_name'];
    $folderTujuan = realpath(__DIR__ . '/../../assets/img/covers/') . '/';
    $pathCover = $folderTujuan . $coverNama;

    if ($judulBuku && $pengarangBuku && $penerbitBuku && $tahunBuku && $genreBuku && $jumlahBuku) {
        if ($coverNama) {
            $allowed_types = ['image/jpeg', 'image/png', 'image/jpg', 'image/gif'];
            $file_type = mime_content_type($coverTmp);
            if (!in_array($file_type, $allowed_types)) {
                $error = "Tipe file tidak didukung.";
            } elseif ($_FILES['cover']['size'] > 2 * 1024 * 1024) {
                $error = "Ukuran file maksimal 2MB.";
            } elseif (move_uploaded_file($coverTmp, $pathCover)) {
                $stmt = mysqli_prepare($koneksi, "UPDATE buku SET judul=?, pengarang=?, penerbit=?, deskripsi=?, tahun_terbit=?, genre=?, cover=?, stok=? WHERE id=?");
                mysqli_stmt_bind_param($stmt, "sssssssii", $judulBuku, $pengarangBuku, $penerbitBuku, $deskripsiBuku, $tahunBuku, $genreBuku, $coverNama, $jumlahBuku, $id);
                $query = mysqli_stmt_execute($stmt);
            } else {
                $error = "Upload foto gagal";
            }
        } else {
            $stmt = mysqli_prepare($koneksi, "UPDATE buku SET judul=?, pengarang=?, penerbit=?, deskripsi=?, tahun_terbit=?, stok=? WHERE id=?");
            mysqli_stmt_bind_param($stmt, "ssssiii", $judulBuku, $pengarangBuku, $penerbitBuku, $deskripsiBuku, $tahunBuku, $jumlahBuku, $id);
            $query = mysqli_stmt_execute($stmt);

            // Update relasi genre di tabel relasi_buku_genre
            // Hapus genre lama
            mysqli_query($koneksi, "DELETE FROM buku_genre WHERE id_buku = $id");
            // Tambah genre baru
            if (!empty($_POST['genre'])) {
                $stmt_genre = mysqli_prepare($koneksi, "INSERT INTO buku_genre (id_buku, id_genre) VALUES (?, ?)");
                foreach ($_POST['genre'] as $id_genre) {
                    mysqli_stmt_bind_param($stmt_genre, "ii", $id, $id_genre);
                    mysqli_stmt_execute($stmt_genre);
                }
            }
        }

        if (isset($query) && $query) {
            $success = "Data Buku Berhasil Diperbarui";
            // Refresh data buku
            $result = mysqli_query($koneksi, "SELECT * FROM buku WHERE id='$id'");
            $dataBuku = mysqli_fetch_assoc($result);
            $genre_terpilih = !empty($dataBuku['genre']) ? explode(',', $dataBuku['genre']) : [];
        } elseif (!$error) {
            $error = "Data Buku Gagal Diperbarui";
        }
    } else {
        $error = "Data Buku Tidak Boleh Kosong";
    }
}

if (isset($_POST['hapus'])) {
    // Set stok buku menjadi 0, bukan hapus data
    $stmt = mysqli_prepare($koneksi, "UPDATE buku SET stok=0 WHERE id=?");
    mysqli_stmt_bind_param($stmt, "i", $id);
    $query = mysqli_stmt_execute($stmt);
    if ($query) {
        $success = "Stok buku berhasil diubah menjadi 0 (buku dianggap dihapus).";
        // Refresh data buku
        $result = mysqli_query($koneksi, "SELECT * FROM buku WHERE id='$id'");
        $dataBuku = mysqli_fetch_assoc($result);
    } else {
        $error = "Gagal mengubah stok buku.";
    }
}
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
    <title>Edit Book</title>
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
                <h5 class="card-header">Edit Buku</h5>
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
                                <input type="text" class="form-control" name="judul"
                                    value="<?= htmlspecialchars($dataBuku['judul']) ?>">
                            </div>
                        </div>
                        <div class="mb-3 row">
                            <label for="pengarang" class="col-sm-2 col-form-label">Pengarang </label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" name="pengarang"
                                    value="<?= htmlspecialchars($dataBuku['pengarang']) ?>">
                            </div>
                        </div>
                        <div class="mb-3 row">
                            <label for="penerbit" class="col-sm-2 col-form-label">Penerbit </label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" name="penerbit"
                                    value="<?= htmlspecialchars($dataBuku['penerbit']) ?>">
                            </div>
                        </div>
                        <div class="mb-3 row">
                            <label for="deskripsi" class="col-sm-2 col-form-label">Deskripsi </label>
                            <div class="col-sm-10">
                                <textarea class="form-control" name="deskripsi"
                                    rows="5"><?= htmlspecialchars($dataBuku['deskripsi']) ?></textarea>
                            </div>
                        </div>
                        <div class="mb-3 row">
                            <label for="tahun" class="col-sm-2 col-form-label">Tahun Terbit </label>
                            <div class="col-sm-10">
                                <input type="number" maxlength="4" class="form-control" name="tahun_terbit"
                                    value="<?= htmlspecialchars($dataBuku['tahun_terbit']) ?>">
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
                                <input type="number" class="form-control" name="stok"
                                    value="<?= htmlspecialchars($dataBuku['stok']) ?>">
                            </div>
                        </div>
                        <div class="mb-3 row">
                            <label for="cover" class="col-sm-2 col-form-label">Cover Image </label>
                            <div class="col-sm-10">
                                <input type="file" class="form-control" name="cover" accept="image/*">
                                <?php if (!empty($dataBuku['cover'])): ?>
                                <img src="../../assets/img/covers/<?= htmlspecialchars($dataBuku['cover']) ?>"
                                    alt="Cover" style="max-width:100px; margin-top:10px;">
                                <?php endif; ?>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-secondary" name="submit">Submit</button>
                        <a href="../buku/list_buku.php" class="btn btn-info" style="color: white;">List Buku</a>
                        <button type="submit" class="btn btn-danger" name="hapus"
                            onclick="return confirm('Yakin ingin menghapus buku ini?')">Hapus Buku</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</body>

</html>