<?php
session_start();
if (!isset($_SESSION['admin'])) {
    header("Location: ../index.php");
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
    <link rel="stylesheet" href="../assets/css/theme.css">
    <title>Register</title>
</head>

<body data-bs-spy="scroll" data-bs-target=".sidebar" data-bs-offset="100" tabindex="0">
    <button class="sidebar-toggle" id="sidebarToggle"><i class="fas fa-bars"></i></button>
    <div class="sidebar" id="sidebarMenu">
        <div class="text-center mb-4">
            <img src="../assets/img/avatar.jpg" alt="Admin" class="rounded-circle" width="80">
            <h5 class="mt-2"><?php echo $_SESSION['username']?></h5>
            <p><span class="badge bg-success">Online</span></p>
        </div>
        <a href="dashboard.php" class="active"><i class="fas fa-home me-2"></i>Dashboard</a>
        <a href="books/book_list.php"><i class="fas fa-book me-2"></i>Kelola Buku</a>
        <a href="books/borrow_history.php.php"><i class="fas fa-sync-alt me-2"></i>Peminjaman</a>
        <a href="users/user_list.php"><i class="fas fa-users me-2"></i>Daftar User</a>
        <a href="register.php"><i class="fas fa-user-plus me-2"></i>Tambah User</a>
        <a href="../config/auth/logout.php"><i class="fas fa-sign-out-alt me-2"></i>Logout</a>
    </div>
    <div class="content">
        <div class="container mt-5">
            <h1>Register</h1>
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
            <form action="../config/proses_register.php" method="post">
                <div class="mb-3">
                    <label for="username" class="form-label">Username</label>
                    <input type="text" class="form-control" id="username" name="username">
                </div>
                <div class="mb-3">
                    <label for="password" class="form-label">Password</label>
                    <input type="password" class="form-control" id="password" name="password">
                </div>
                <div class="mb3">
                    <label for="role" class="form-role">Role</label>
                    <select class="form-select" id="role" name="role">
                        <option value="admin">Admin</option>
                        <option value="user">User</option>
                    </select>
                </div>
                <div class="mb3">
                    <button type="submit" class="btn btn-secondary mt-3">Daftar</button>
                    <button class="btn btn-secondary mt-3"><a href="dashboard.php"
                            style="color: white; text-decoration: none;">Dashboard</a></button>
                </div>
            </form>
        </div>
    </div>
</body>

</html>