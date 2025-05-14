<?php
    include 'config/koneksi.php';

    session_start();
?>

<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Index</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-4Q6Gf2aSP4eDXB8Miphtr37CMZZQ5oXLH2yaXMJ2w8e2ZtHTl7GptT4jmndRuHDT" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">
</head>

<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark fixed-top">
        <div class="container">
            <!-- Logo on the left -->
            <a class="navbar-brand" href="#">Logo</a>

            <!-- Menu on the right -->
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
                aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link active" aria-current="page" href="#">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">Features</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">Pricing</a>
                    </li>
                    <?php if (isset($_SESSION['username'])): ?>
                        <li class="nav-item">
                            <a class="nav-link" href="config/logout.php"><button class="btn btn-alert">Logout</button></a>
                        </li>
                    <?php else: ?>
                        <li class="nav-item">
                            <a class="nav-link" href="pages/login.php"><button class="btn btn-primary">Login</button></a>
                        </li>
                    <?php endif; ?>
                    
                </ul>
            </div>
        </div>
    </nav>

    <div class="container">
        <div class="d-flex justify-content-center align-items-center text-center text-white"
            style="background-image: url('https://w.wallhaven.cc/full/2y/wallhaven-2yp6gg.png'); background-size: cover; background-position: center; height: 100vh;">
            <div>
                <h1 class="display-4">Welcome</h1>
                <p class="lead">Explore our features and enjoy your stay!</p>
                <a href="#" class="btn btn-primary btn-lg">Get Started</a>
            </div>
        </div>
    </div>

    <div class="container">
        <div class="row my-5 text-center">
            <h1>Perpustakaan Pinan</h1>
            <p class="lead">Kami menyediakan berbagai macam buku untuk Anda.</p>
        </div>
        <div class="row my-5">
            <div class="col-md-4 mb-4">
                <div class="card">
                    <i class="bi bi-people text-center display-1"></i>
                    <div class="card-body text-center">
                        <h5 class="card-title fw-bold">Jumlah User</h5>
                        <p class="card-text fw-bold">100</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card">
                    <i class="bi bi-book text-center display-1"></i>
                    <div class="card-body text-center">
                        <h5 class="card-title fw-bold">Jumlah Buku</h5>
                        <p class="card-text fw-bold">100</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card">
                    <i class="bi bi-people-fill text-center display-1"></i>
                    <div class="card-body text-center">
                        <h5 class="card-title fw-bold">Jumlah User</h5>
                        <p class="card-text fw-bold">100</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card">
                    <i class="bi bi-people-fill text-center display-1"></i>
                    <div class="card-body text-center">
                        <h5 class="card-title fw-bold">Jumlah User</h5>
                        <p class="card-text fw-bold">100</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card">
                    <i class="bi bi-people-fill text-center display-1"></i>
                    <div class="card-body text-center">
                        <h5 class="card-title fw-bold">Jumlah User</h5>
                        <p class="card-text fw-bold">100</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card">
                    <i class="bi bi-people-fill text-center display-1"></i>
                    <div class="card-body text-center">
                        <h5 class="card-title fw-bold">Jumlah User</h5>
                        <p class="card-text fw-bold">100</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="container">
        <h1>Buku Terlaris</h1>
        <div class="card">
            <div class="card-body bg-dark rounded-3">
                <div class="card text-bg-dark " style="width: 18rem;">
                    <img src="https://w.wallhaven.cc/full/2y/wallhaven-2yp6gg.png" class="card-img-top" alt="...">
                    <style>
                        .card-img-top:hover {
                            transform: scale(1.04);
                            transition: all 0.2s ease;
                        }
                    </style>
                </div>
            </div>
        </div>
    </div>

    <footer class="bg-dark text-white py-4 mt-5">
        <div class="container">
            <div class="row">
                <!-- Jam Operasional -->
                <div class="col-md-5">
                    <h5>Jam Operasional Layanan</h5>
                    <p>Senin - Jumat  : 08.00 - 19.00 WIB</p>
                    <p>Sabtu - Minggu : 08.00 - 15.30 WIB</p>
                    <p>Cuti Bersama dan Libur Nasional Tutup</p>
                </div>
                <!-- Kontak Kami -->
                <div class="col-md-5">
                    <h5>Kontak Kami</h5>
                    <p><i class="bi bi-telephone"></i> 085779530466</p>
                    <p><i class="bi bi-envelope"></i> perpustakaanpinan@gmail.com</p>
                    <p><i class="bi bi-geo-alt"></i> Jl. Kusumabangsa No.1 </p>
                </div>                          
                <!-- Media Sosial -->
                <div class="col-md-2">
                    <h5>Ikuti Kami</h5>
                    <a href="#" class="text-white me-3"><i class="bi bi-facebook"></i></a>
                    <a href="#" class="text-white me-3"><i class="bi bi-twitter"></i></a>
                    <a href="#" class="text-white me-3"><i class="bi bi-instagram"></i></a>
                </div>
            </div>
            <div class="text-center mt-3">
                <p>&copy; 2025 Perpustakaan Pinan. All rights reserved.</p>
            </div>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-j1CDi7MgGQ12Z7Qab0qlWQ/Qqz24Gc6BM0thvEMVjHnfYGF0rmFCozFSxQBxwHKO"
        crossorigin="anonymous"></script>
</body>

</html>