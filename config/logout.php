<?php
include 'config/koneksi.php';
session_start();
$username = $_SESSION['username'];
$role = $_SESSION['role'];

session_destroy();
echo "<script>alert('Logout successful!'); window.location.href='pages/login.php';</script>";
header("Location: ../pages/login.php");
exit;
