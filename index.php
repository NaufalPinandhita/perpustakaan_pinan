<?php
// Koneksi Database
include 'config/koneksi.php';
session_start();

// Ambil Top 4 Buku Favorit (terbanyak dipinjam)
$sqlFavorit = "SELECT b.id, b.judul, b.pengarang, b.cover, COUNT(p.id_peminjaman) AS total_pinjam
               FROM buku b
               JOIN peminjaman p ON b.id = p.id_buku
               WHERE p.status = 'dikembalikan'
               GROUP BY b.id
               ORDER BY total_pinjam DESC
               LIMIT 4";
$resultFavorit = $koneksi->query($sqlFavorit);
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Perpustakaan Pinan</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- AOS Animation CSS -->
    <link href="https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.css" rel="stylesheet">
    <link rel="stylesheet" href="assets/theme.css">
</head>

<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-light shadow-sm fixed-top bg-white">
        <div class="container">
            <a class="navbar-brand fw-bold" href="#home"><img src="assets/img/logo.png" alt="logo-perpus" width="30"
                    height="26"> Perpustakaan Pinan</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item"><a class="nav-link" href="#home">Home</a></li>
                    <li class="nav-item"><a class="nav-link" href="#features">Fitur</a></li>
                    <li class="nav-item"><a class="nav-link" href="#favorit">Buku</a></li>
                    <li class="nav-item"><a class="nav-link" href="#contact">Kontak</a></li>
                    <?php if(!isset($_SESSION['username'])):?>
                    <li class="nav-item"><a class="nav-link" href="pages/login.php">Login</a></li>
                    <?php endif; ?>
                </ul>
                <?php if(isset($_SESSION['username'])):?>
                <div class="btn-group">
                    <button class="btn btn-secondary btn-sm dropdown-toggle" type="button" data-bs-toggle="dropdown"
                        aria-expanded="false">
                        <?php 
                        $nama_user = $_SESSION['username'];
                        echo strlen($nama_user) > 5 ? substr($nama_user, 0, 5) . "..." : $nama_user; 
                        ?>
                    </button>
                    <ul class="dropdown-menu p-1">
                        <li><a class="nav-link btn-primary rounded p-1 mb-1" href="config/logout.php">Logout</a></li>
                        <li><a class="nav-link btn-primary rounded p-1" href="pages/users/reset_pass.php">Reset Password</a></li>
                    </ul>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section id="home" class="vh-100 d-flex align-items-center"
        style="padding-top:56px; background-image: url('https://w.wallhaven.cc/full/5w/wallhaven-5wr127.jpg');"
        data-aos="fade-up">
        <div class="container text-center p-3 rounded" style=" background-color: rgb(255, 255, 255, 0.6);">
            <h1 class="display-5 fw-bold">Selamat Datang di Perpustakaan Kami</h1>
            <p class="lead mt-3">Temukan ribuan buku, e-book, dan jurnal dengan mudah dan nyaman.</p>
            <a href="#features" class="btn btn-primary btn-lg rounded-pill mt-4">Jelajahi Fitur</a>
        </div>
    </section>

    <!-- Features -->
    <section id="features" class="py-5 bg-accent">
        <div class="container">
            <h2 class="text-center fw-bold mb-4" data-aos="fade-up">Fitur Unggulan</h2>
            <div class="row g-4">
                <div class="col-md-6 col-lg-3" data-aos="zoom-in">
                    <div class="card h-100 shadow-sm feature-card">
                        <div class="card-body text-center">
                            <h5 class="card-title">Pencarian Cepat</h5>
                            <p class="card-text">Cari buku berdasarkan judul, pengarang, atau kategori dengan sekali
                                klik.</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-lg-3" data-aos="zoom-in" data-aos-delay="100">
                    <div class="card h-100 shadow-sm feature-card">
                        <div class="card-body text-center">
                            <h5 class="card-title">Buku Digital</h5>
                            <p class="card-text">Akses e-book langsung di browser Anda tanpa perlu download.</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-lg-3" data-aos="zoom-in" data-aos-delay="200">
                    <div class="card h-100 shadow-sm feature-card">
                        <div class="card-body text-center">
                            <h5 class="card-title">Rekomendasi</h5>
                            <p class="card-text">Rangkuman dan rekomendasi buku sesuai minat Anda.</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-lg-3" data-aos="zoom-in" data-aos-delay="300">
                    <div class="card h-100 shadow-sm feature-card">
                        <div class="card-body text-center">
                            <h5 class="card-title">Statistik</h5>
                            <p class="card-text">Laporan bacaan dan riwayat pinjaman yang mudah dipantau.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Buku Favorit -->
    <section id="favorit" class="py-5 bg-light">
        <div class="container">
            <h2 class="text-center fw-bold mb-4" data-aos="fade-up">Buku Favorit</h2>
            <div class="row g-4">
                <?php if ($resultFavorit && $resultFavorit->num_rows > 0): ?>
                <?php while($row = $resultFavorit->fetch_assoc()): ?>
                <div class="col-md-6 col-lg-3" data-aos="zoom-in">
                    <div class="card h-100 shadow-sm favorit-card">
                        <img src="assets/img/covers/<?= htmlspecialchars($row['cover']); ?>"
                            class="card-img-top cover-img" alt="<?= htmlspecialchars($row['judul']); ?>">
                        <div class="card-body text-center">
                            <h5 class="card-title"><?= htmlspecialchars($row['judul']); ?></h5>
                            <p class="card-text"><small>by <?= htmlspecialchars($row['pengarang']); ?></small></p>
                        </div>
                    </div>
                </div>
                <?php endwhile; ?>
                <?php else: ?>
                <p class="text-center">Belum ada data buku favorit.</p>
                <?php endif; ?>
                <a href="pages/buku/list_buku.php" class="btn btn-primary btn-lg rounded-pill mt-4">Jelajahi Buku</a>
            </div>
    </section>

    <?php if (!isset($_SESSION['username'])):?>
    <!-- CTA  -->
    <section id="about" class="py-5 text-center bg-accent" data-aos="fade-up">
        <div class="container">
            <h2 class="fw-bold text-secondary">Gabung Sekarang</h2>
            <p class="lead">Daftar akun gratis dan nikmati kemudahan akses digital perpustakaan.</p>
            <a href="#contact" class="btn btn-primary btn-lg rounded-pill mt-3">Daftar Gratis</a>
        </div>
    </section>
    <?php endif; ?>

    <!-- Contact -->
    <section id="contact" class="py-5">
        <div class="container" data-aos="fade-up">
            <h2 class="text-center fw-bold mb-4">Kontak Kami</h2>
            <div id="alertPlaceholder"></div>
            <form id="contactForm" class="row g-3 justify-content-center">
                <div class="col-md-6">
                    <input type="text" id="name" class="form-control" placeholder="Nama Anda" required>
                </div>
                <div class="col-md-6">
                    <input type="email" id="email" class="form-control" placeholder="Email Anda" required>
                </div>
                <div class="col-12">
                    <textarea id="message" class="form-control" rows="4" placeholder="Pesan..." required></textarea>
                </div>
                <div class="col-12 text-center">
                    <button type="submit" class="btn btn-primary rounded-pill px-5">Kirim</button>
                </div>
            </form>
        </div>
    </section>



    <!-- Footer -->
    <footer class="py-4 text-center footer">
        <div class="container">
            <small>&copy; 2025 Perpustakaan Elegan. Semua hak dilindungi.
        </div>
    </footer>

    <!-- Bootstrap & AOS JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.js"></script>
    <script>
    AOS.init({
        duration: 800,
        once: true
    });
    </script>
    <!-- Contact Form Script using Formspree -->
    <script>
    const form = document.getElementById('contactForm');
    form.addEventListener('submit', async (e) => {
        e.preventDefault();
        const data = {
            name: document.getElementById('name').value,
            email: document.getElementById('email').value,
            message: document.getElementById('message').value
        };
        try {
            const res = await fetch('https://formspree.io/f/xwpozllb', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify(data)
            });
            if (res.ok) {
                showAlert('Pesan berhasil dikirim!', 'success');
                form.reset();
            } else {
                showAlert('Gagal mengirim pesan. Coba lagi nanti.', 'danger');
            }
        } catch (err) {
            showAlert('Gagal mengirim pesan. Coba lagi nanti.', 'danger');
        }
    });

    function showAlert(msg, type) {
        const wrapper = document.getElementById('alertPlaceholder');
        wrapper.innerHTML = `<div class="alert alert-${type} text-center" role="alert">${msg}</div>`;
    }
    </script>
</body>

</html>