<?php
include '../../config/koneksi.php';
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: ../../pages/login.php");
    exit;
}
if ($_SESSION['role'] != 'admin') {
    header("Location: ../../pages/dashboard.php");
    exit;
}

$search = isset($_GET['search']) ? $_GET['search'] : '';
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$limit = 10;
$offset = ($page - 1) * $limit;

// Hitung total data
$total_query = mysqli_query($koneksi, "SELECT COUNT(*) as total 
    FROM peminjaman p 
    JOIN buku b ON p.id_buku = b.id 
    WHERE p.nama_peminjam LIKE '%" . mysqli_real_escape_string($koneksi, $search) . "%'");
$total_data = mysqli_fetch_assoc($total_query)['total'];
$total_pages = ceil($total_data / $limit);

// Ambil data dengan limit dan offset
$query = mysqli_query($koneksi, "SELECT p.*, b.judul 
    FROM peminjaman p 
    JOIN buku b ON p.id_buku = b.id 
    WHERE p.nama_peminjam LIKE '%" . mysqli_real_escape_string($koneksi, $search) . "%'
    ORDER BY p.id_peminjaman DESC
    LIMIT $limit OFFSET $offset");
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Peminjaman</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="../../assets/sidebar.css">
</head>

<body>
    <div class="sidebar">
        <div class="text-center mb-4">
            <img src="../../assets/img/avatar.jpg" alt="Admin" class="rounded-circle" width="80">
            <h5 class="mt-2"><?php echo $_SESSION['username']?></h5>
            <p><span class="badge bg-success">Online</span></p>
        </div>
        <a href="../dashboard.php"><i class="fas fa-home me-2"></i>Dashboard</a>
        <a href="list_buku.php"><i class="fas fa-book me-2"></i>Kelola Buku</a>
        <a href="daftar_pinjaman.php" class="active"><i class="fas fa-sync-alt me-2"></i>Peminjaman</a>
        <a href="../users/list_user.php"><i class="fas fa-users me-2"></i>Daftar User</a>
        <a href="../signup.php"><i class="fas fa-user-plus me-2"></i>Tambah User</a>
        <a href="../../config/logout.php"><i class="fas fa-sign-out-alt me-2"></i>Logout</a>
    </div>
    <div class="content">
        <div class="container">
            <div class="d-flex justify-content-between align-items-center mb-3 bg-light p-3 rounded">
                <h2>Daftar Peminjaman</h2>
            </div>
            <table class="table table-bordered table-striped">
                <thead class="table-dark">
                    <tr>
                        <th>Judul Buku</th>
                        <th>Nama Peminjam</th>
                        <th>Tanggal Pinjam</th>
                        <th>Tanggal Kembali</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = mysqli_fetch_assoc($query)) : ?>
                    <tr>
                        <td><?= $row['judul']; ?></td>
                        <td><?= $row['nama_peminjam']; ?></td>
                        <td><?= $row['tanggal_pinjam']; ?></td>
                        <td><?= $row['tanggal_kembali']; ?></td>
                        <td><?= $row['status']; ?></td>
                        <td>
                            <?php if ($row['status'] == 'dipinjam') : ?>
                                <form action="../../config/kembalikan.php" method="get" class="d-inline d-flex align-items-center gap-2" onsubmit="return confirm('Yakin ingin mengembalikan buku ini?')">
                                    <input type="hidden" name="id" value="<?= $row['id_peminjaman']; ?>">
                                    <input type="date" name="tanggal_kembali" class="form-control form-control-sm" style="width: 130px; height: 32px;" required>
                                    <button type="submit" class="btn btn-sm btn-outline-primary d-flex align-items-center justify-content-center" style="height: 32px;"><i class="bi bi-check-lg"></i></button>
                                </form>
                            <?php else : ?>
                                <span class="text-muted">-</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>

            <!-- Pagination -->
            <nav aria-label="..." class="d-flex justify-content-center">
                <ul class="pagination">
                    <?php if ($page > 1) : ?>
                    <li class="page-item">
                        <a class="page-link"
                            href="?search=<?= htmlspecialchars($search) ?>&page=<?= $page - 1 ?>">Previous</a>
                    </li>
                    <?php else : ?>
                    <li class="page-item disabled">
                        <a class="page-link">Previous</a>
                    </li>
                    <?php endif; ?>
                    <?php for ($i = 1; $i <= $total_pages; $i++) : ?>
                    <li class="page-item <?= $i == $page ? 'active' : '' ?>"
                        aria-current="<?= $i == $page ? 'page' : '' ?>">
                        <a class="page-link"
                            href="?search=<?= htmlspecialchars($search) ?>&page=<?= $i ?>"><?= $i ?></a>
                    </li>
                    <?php endfor; ?>
                    <?php if ($page < $total_pages) : ?>
                    <li class="page-item">
                        <a class="page-link"
                            href="?search=<?= htmlspecialchars($search) ?>&page=<?= $page + 1 ?>">Next</a>
                    </li>
                    <?php else : ?>
                    <li class="page-item disabled">
                        <a class="page-link">Next</a>
                    </li>
                    <?php endif; ?>
                </ul>
            </nav>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>