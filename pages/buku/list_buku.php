<?php
require_once '../../config/koneksi.php';
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: ../../pages/login.php");
    exit;
}

$search = isset($_GET['search']) ? mysqli_real_escape_string($koneksi, $_GET['search']) : '';
$limit = 18; // Maksimal 18 buku per halaman
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$page = max($page, 1); // Pastikan halaman minimal 1
$offset = ($page - 1) * $limit;

// Hitung total data
$totalQuery = "SELECT COUNT(*) AS total FROM buku WHERE judul LIKE '%$search%'";
$totalResult = mysqli_query($koneksi, $totalQuery);
$totalRow = mysqli_fetch_assoc($totalResult);
$totalData = $totalRow['total'];
$totalPages = ceil($totalData / $limit);

// Ambil data buku dengan limit dan offset
$query = "SELECT * FROM buku WHERE judul LIKE '%$search%' LIMIT $limit OFFSET $offset";
$result = mysqli_query($koneksi, $query);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>List Buku</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
    .card-img-top {
        height: 160px;
        object-fit: cover;
    }

    .card {
        transition: transform 0.2s;
        font-size: 0.85rem;
    }

    .card:hover {
        transform: scale(1.03);
        box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2);
    }

    .card-title {
        font-size: 1rem;
    }
    </style>
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
                    <input class="form-control me-2" type="search" name="search" placeholder="Cari judul buku"
                        aria-label="Search" value="<?= htmlspecialchars($search) ?>">
                    <button class="btn btn-outline-success" type="submit">Cari</button>
                </form>
            </div>
        </div>
    </nav>
    <div class="container py-4">
        <h4 class="mb-3 text-center">Daftar Buku</h4>
        <div class="row g-3 justify-content-center">
            <?php if (mysqli_num_rows($result) > 0): ?>
            <?php while ($buku = mysqli_fetch_assoc($result)) : ?>
            <div class="col-6 col-sm-4 col-md-3 col-lg-2">
                <div class="card h-100">
                    <img src="../../assets/img/covers/<?= htmlspecialchars($buku['cover']) ?>" class="card-img-top"
                        alt="<?= htmlspecialchars($buku['judul']) ?>">
                    <div class="card-body p-2 d-flex flex-column">
                        <h6 class="card-title mb-1"><?= htmlspecialchars($buku['judul']) ?></h6>
                        <small class="text-muted mb-2"><?= htmlspecialchars($buku['pengarang']) ?></small>
                        <div class="mt-auto">
                            <small> </small><br>
                            <small> <?= htmlspecialchars($buku['genre']) ?> | <?= $buku['stok'] ?> Buku</small><br>
                            <small class="text-muted mb-2"><?= htmlspecialchars($buku['tahun_terbit']) ?> |
                                <?= htmlspecialchars($buku['penerbit']) ?></small>
                            <a href="#" class="btn btn-sm btn-outline-primary w-100 mt-1">Detail</a>
                            <?php
                            if ($_SESSION['role'] == 'admin'){?>
                                <a href="edit_buku.php?id=<?= htmlspecialchars($buku['id']) ?>" class="btn btn-sm btn-outline-primary w-100 mt-1">Edit</a>
                            <?php } else if ($_SESSION['role'] == 'user'){ ?>
                                <a href="pinjam_buku.php?id=<?= htmlspecialchars($buku['id']) ?>" class="btn btn-sm btn-outline-primary w-100 mt-1">Pinjam</a>
                            <?php } ?>
                        </div>
                    </div>
                </div>
            </div>
            <?php endwhile; ?>
            <?php else: ?>
            <p class="text-center">Buku tidak ditemukan.</p>
            <?php endif; ?>
        </div>
        <nav aria-label="..." class="mt-4">
            <ul class="pagination justify-content-center">
                <?php if ($page > 1): ?>
                <li class="page-item">
                    <a class="page-link" href="?search=<?= urlencode($search) ?>&page=<?= $page - 1 ?>">Previous</a>
                </li>
                <?php else: ?>
                <li class="page-item disabled">
                    <span class="page-link">Previous</span>
                </li>
                <?php endif; ?>

                <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                <?php if ($i == $page): ?>
                <li class="page-item active" aria-current="page">
                    <span class="page-link"><?= $i ?></span>
                </li>
                <?php else: ?>
                <li class="page-item">
                    <a class="page-link" href="?search=<?= urlencode($search) ?>&page=<?= $i ?>"><?= $i ?></a>
                </li>
                <?php endif; ?>
                <?php endfor; ?>

                <?php if ($page < $totalPages): ?>
                <li class="page-item">
                    <a class="page-link" href="?search=<?= urlencode($search) ?>&page=<?= $page + 1 ?>">Next</a>
                </li>
                <?php else: ?>
                <li class="page-item disabled">
                    <span class="page-link">Next</span>
                </li>
                <?php endif; ?>
            </ul>
        </nav>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>