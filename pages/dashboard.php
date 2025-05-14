<?php
require "../config/koneksi.php";
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: login.php?login=failed");
    exit;
}

// Redirect jika role tidak sesuai
if ($_SESSION['role'] === 'user' && basename($_SERVER['PHP_SELF']) === 'admin.php') {
    header("Location: user.php");
    exit;
} elseif ($_SESSION['role'] === 'admin' && basename($_SERVER['PHP_SELF']) === 'user.php') {
    header("Location: admin.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Perpustakaan Pinan</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
    .bordered {
        background-color: rgb(162, 204, 247);
        border-radius: 10px;
    }
    </style>
</head>

<body>
    <header class="bg-primary text-white text-center py-3">
        <h1>Perpustakaan Pinan</h1>
        <nav class="navbar navbar-expand-lg navbar-light bg-light">
            <div class="container-fluid">
                <a class="navbar-brand" href="#">Dashboard</a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
                    aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarNav">
                    <ul class="navbar-nav">
                        <li class="nav-item">
                            <a class="nav-link" href="dashboard.php">Dashboard</a>
                        </li>
                        <?php if ($_SESSION['role'] === 'admin') : ?>
                        <li class="nav-item">
                            <a class="nav-link" href="admin.php">Admin</a>
                        </li>
                        <?php endif; ?>
                        <li class="nav-item">
                            <a class="nav-link" href="user.php">User</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="../config/proses_logout.php">Logout</a>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>
    </header>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>