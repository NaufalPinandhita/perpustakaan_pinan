<?php
include '../database.php';

$nama = $_POST['username'];
$password = md5($_POST['password']);
$role = $_POST['role'];

$query = "INSERT INTO users (username, password, role) VALUES ('$nama', '$password', '$role')";

if (!empty($nama) && !empty($password) && !empty($role)) {
    if (mysqli_query($koneksi, $query)) {
        $_SESSION['username'] = $nama;
        $_SESSION['role'] = $role;

        $success = urlencode("Data User Berhasil Ditambahkan");
        header("Location: ../pages/signup.php?success=" . $success);
    } else {
        $error = urlencode("Data User Gagal Ditambahkan");
        header("Location: ../pages/signup.php?error=" . $error);
    }
} else {
    $error = urlencode("Semua field harus diisi");
    header("Location: ../pages/signup.php?error=" . $error);
}
