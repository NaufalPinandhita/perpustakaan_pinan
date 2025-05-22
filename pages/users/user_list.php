<?php
session_start();
include '../../config/database.php';

if (!isset($_SESSION['admin'])) {
    header("Location: ../../index.php");
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
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
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
        <a href="../books/book_list.php"><i class="fas fa-book me-2"></i>Kelola Buku</a>
        <a href="../books/borrow_history.php.php"><i class="fas fa-sync-alt me-2"></i>Peminjaman</a>
        <a href="user_list.php" class="active"><i class="fas fa-users me-2"></i>Daftar User</a>
        <a href="../register.php"><i class="fas fa-user-plus me-2"></i>Tambah User</a>
        <a href="../../config/auth/logout.php"><i class="fas fa-sign-out-alt me-2"></i>Logout</a>
    </div>
    <div class="content">
        <div class="container">
            <div class="d-flex justify-content-between align-items-center mb-3 bg-light p-3 rounded">
                <h2 class="mb-0">Daftar User</h2>
            </div>
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
    </div>
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>