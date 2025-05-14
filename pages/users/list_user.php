<?php
session_start();
include '../../config/koneksi.php';

if (!isset($_SESSION['username'])) {
    header("Location: ../pages/login.php");
    exit;
} else if ($_SESSION['role'] != 'admin') {
    header("Location: ../pages/dashboard.php");
    exit;
}

// Pagination setup
$limit = 10; // Jumlah data per halaman
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$page = max($page, 1); // Pastikan halaman minimal 1
$offset = ($page - 1) * $limit;

// Cek apakah ada parameter pencarian
$search = isset($_GET['search']) ? trim($_GET['search']) : '';
$searchCondition = $search ? "WHERE username LIKE '%" . mysqli_real_escape_string($koneksi, $search) . "%'" : '';

// Query untuk mendapatkan data pengguna dengan pencarian
$query = "SELECT id, username, role FROM users $searchCondition ORDER BY role = 'admin' DESC, id ASC LIMIT $limit OFFSET $offset";
$result = mysqli_query($koneksi, $query);

// Query untuk menghitung total pengguna dengan pencarian
$queryTotal = "SELECT COUNT(*) AS total FROM users $searchCondition";
$resultTotal = mysqli_query($koneksi, $queryTotal);
$total = ($resultTotal && $rowTotal = mysqli_fetch_assoc($resultTotal)) ? $rowTotal['total'] : 0;

// Hitung total halaman
$totalPages = ceil($total / $limit);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>List User</title>
    <!-- Bootstrap CSS -->
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
                            <li><a class="dropdown-item" href="../buku/list_buku.php">Tambah Buku</a></li>
                            <li>
                                <hr class="dropdown-divider">
                            </li>
                            <li><a class="dropdown-item" href="../../config/logout.php">Logout</a></li>
                        </ul>
                    </li>
                </ul>
            </div>
            <form class="d-flex" role="search" method="GET" action="list_user.php">
                <input class="form-control me-2" type="search" name="search" placeholder="Search by name"
                    aria-label="Search"
                    value="<?php echo isset($_GET['search']) ? htmlspecialchars($_GET['search']) : ''; ?>">
                <button class="btn btn-outline-success" type="submit">Search</button>
            </form>
        </div>
    </nav>
    <div class="container mt-5">
        <h1 class="text-center mb-4">Daftar Pengguna</h1>
        <table class="table table-bordered table-striped">
            <thead class="table-dark">
                <tr>
                    <th>ID</th>
                    <th>Nama User</th>
                    <th>Roles</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if ($result && mysqli_num_rows($result) > 0) {
                    while ($row = mysqli_fetch_assoc($result)) {
                        echo "<tr>";
                        echo "<td>" . htmlspecialchars($row['id']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['username']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['role']) . "</td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='3' class='text-center'>Tidak ada data pengguna.</td></tr>";
                }
                ?>
            </tbody>
        </table>

        <p class="text-end">Total Pengguna: <strong><?php echo htmlspecialchars($total); ?></strong></p>

        <!-- Pagination -->
        <nav aria-label="Page navigation example">
            <ul class="pagination justify-content-center">
                <?php if ($page > 1): ?>
                <li class="page-item">
                    <a class="page-link" href="?page=<?php echo $page - 1; ?>">Previous</a>
                </li>
                <?php else: ?>
                <li class="page-item disabled">
                    <a class="page-link">Previous</a>
                </li>
                <?php endif; ?>

                <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                <li class="page-item <?php echo ($i == $page) ? 'active' : ''; ?>">
                    <a class="page-link" href="?page=<?php echo $i; ?>"><?php echo $i; ?></a>
                </li>
                <?php endfor; ?>

                <?php if ($page < $totalPages): ?>
                <li class="page-item">
                    <a class="page-link" href="?page=<?php echo $page + 1; ?>">Next</a>
                </li>
                <?php else: ?>
                <li class="page-item disabled">
                    <a class="page-link">Next</a>
                </li>
                <?php endif; ?>
            </ul>
        </nav>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>