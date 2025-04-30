<!-- filepath: c:\xampp\htdocs\perpustakaan_pinan\pages\signup.php -->
<?php
// Menghubungkan ke database
include '../config/koneksi.php';

// Cek jika form signup disubmit
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = mysqli_real_escape_string($koneksi, $_POST['username']);
    $password = mysqli_real_escape_string($koneksi, $_POST['password']);
    $role = mysqli_real_escape_string($koneksi, $_POST['role']); // Ambil role dari form

    // Hash password menggunakan SHA-256
    $hashed_password = hash('sha256', $password);

    // Query untuk menambahkan user baru ke database
    $query = "INSERT INTO users (username, password, role) VALUES ('$username', '$hashed_password', '$role')";
    if (mysqli_query($koneksi, $query)) {
        echo "<script>alert('Pendaftaran berhasil! Silakan login.');</script>";
        // Redirect ke halaman login
        header('Location: login.php');
        exit();
    } else {
        echo "<script>alert('Pendaftaran gagal! Username mungkin sudah digunakan.');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Signup - Perpustakaan Pinan</title>
</head>
<body>
    <h1>Signup Perpustakaan Pinan</h1>
    <form method="POST" action="">
        <label for="username">Username:</label>
        <input type="text" id="username" name="username" required>
        <br>
        <label for="password">Password:</label>
        <input type="password" id="password" name="password" required>
        <br>
        <label for="role">Role:</label>
        <select id="role" name="role" required>
            <option value="user">User</option>
            <option value="admin">Admin</option>
        </select>
        <br>
        <button type="submit">Signup</button>
    </form>
    <p>Sudah punya akun? <a href="login.php">Login di sini</a></p>
</body>
</html>