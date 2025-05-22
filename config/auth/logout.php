<?php
include '../database.php';
session_start();
$username = $_SESSION['username'];
$role = $_SESSION['role'];

session_destroy();
header("Location: ../../index.php?logout+successful");
exit;
