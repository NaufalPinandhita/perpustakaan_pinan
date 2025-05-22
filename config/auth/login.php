<?php
session_start();
include '../database.php';

$username = $_POST['username'];
$password = md5($_POST['password']);

$query = "SELECT * FROM users WHERE username='$username' AND password='$password'";
$result = mysqli_query($koneksi, $query);

if (mysqli_num_rows($result) > 0) {
    $user = mysqli_fetch_assoc($result);
    session_start();
    $_SESSION['username'] = $user['username'];
    $_SESSION['role'] = $user['role'];
    
    $success = urlencode("Login successful!");
    if ($user['role'] == 'admin') {
        header("Location: ../../pages/dashboard.php?success=" . $success);
    } else {
        header("Location: ../../index.php?success=" . $success);
    }
}
else {
    $error = urlencode("Invalid username or password!");
    header("Location: ../../pages/login.php?error=" . $error);
}