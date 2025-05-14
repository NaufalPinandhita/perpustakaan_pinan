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
</head>

<body>
    <nav class="navbar navbar-expand-lg bg-body-tertiary">
        <div class="container-fluid">
            <img src="../../assets/img/logo.png" alt="" width="30px" class="d-inline-block align-text-top">
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false"
                aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <li class="nav-item ms-3">
                        <a class="nav-link" href="#"><?php echo $_SESSION['username']; ?></a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" aria-current="page" href="../dashboard.php">Home</a>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown"
                            aria-expanded="false">
                            Pages
                        </a>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="../buku/list_buku.php">Daftar Buku</a></li>
                            <li><a class="dropdown-item" href="../buku/tambah_buku.php">Tambah Buku</a></li>
                            <li>
                                <hr class="dropdown-divider">
                            </li>
                            <li><a class="dropdown-item" href="../../config/logout.php">Logout</a></li>
                        </ul>
                    </li>
                </ul>
                <form class="d-flex" role="search" method="GET" action="">
                    <input class="form-control me-2" type="search" name="search" placeholder="Cari nama peminjam"
                        aria-label="Search" value="<?= htmlspecialchars($search) ?>">
                    <button class="btn btn-outline-success" type="submit">Cari</button>
                </form>
            </div>
        </div>
    </nav>
    <div class="container mt-5">
        <h2 class="mb-4">Daftar Peminjaman</h2>
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
                        <a href="../../config/kembalikan.php?id=<?= $row['id_peminjaman']; ?>"
                            class="btn btn-primary btn-sm"
                            onclick="return confirm('Yakin ingin mengembalikan buku ini?')">Kembalikan</a>
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
            <a class="page-link" href="?search=<?= htmlspecialchars($search) ?>&page=<?= $page - 1 ?>">Previous</a>
            </li>
            <?php else : ?>
            <li class="page-item disabled">
            <a class="page-link">Previous</a>
            </li>
            <?php endif; ?>
            <?php for ($i = 1; $i <= $total_pages; $i++) : ?>
            <li class="page-item <?= $i == $page ? 'active' : '' ?>" aria-current="<?= $i == $page ? 'page' : '' ?>">
            <a class="page-link" href="?search=<?= htmlspecialchars($search) ?>&page=<?= $i ?>"><?= $i ?></a>
            </li>
            <?php endfor; ?>
            <?php if ($page < $total_pages) : ?>
            <li class="page-item">
            <a class="page-link" href="?search=<?= htmlspecialchars($search) ?>&page=<?= $page + 1 ?>">Next</a>
            </li>
            <?php else : ?>
            <li class="page-item disabled">
            <a class="page-link">Next</a>
            </li>
            <?php endif; ?>
            </ul>
        </nav>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>