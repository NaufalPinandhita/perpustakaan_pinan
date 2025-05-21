<?php
require_once '../../config/koneksi.php';
session_start();
// Jika belum login, redirect ke login
if (!isset($_SESSION['username'])) {
  header('Location: ../login.php');
  exit;
}

// Ambil data genre dan pengarang untuk filter
$genreResult = $koneksi->query("SELECT id, nama_genre FROM genre ORDER BY nama_genre ASC");
$pengarangResult = $koneksi->query("SELECT DISTINCT pengarang FROM buku ORDER BY pengarang ASC");
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Daftar Buku | Perpustakaan Pinan</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
    <!-- Theme CSS (variabel & override) -->
    <link rel="stylesheet" href="../../assets/theme.css">
</head>

<body>

    <?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin'): ?>
    <!-- Sidebar untuk Admin -->
    <div class="d-flex">
        <div class="sidebar" id="sidebarMenu">
            <div class="text-center mb-4">
                <img src="../../assets/img/avatar.jpg" alt="Admin" class="rounded-circle" width="80">
                <h5 class="mt-2"><?php echo $_SESSION['username']?></h5>
                <p><span class="badge bg-success">Online</span></p>
            </div>
            <a href="../dashboard.php"><i class="fas fa-home me-2"></i>Dashboard</a>
            <a href="list_buku.php" class="active"><i class="fas fa-book me-2"></i>Kelola Buku</a>
            <a href="daftar_pinjaman.php"><i class="fas fa-sync-alt me-2"></i>Peminjaman</a>
            <a href="../users/list_user.php"><i class="fas fa-users me-2"></i>Daftar User</a>
            <a href="../signup.php"><i class="fas fa-user-plus me-2"></i>Tambah User</a>
            <a href="../config/logout.php"><i class="fas fa-sign-out-alt me-2"></i>Logout</a>
        </div>
        <div class="content">
            <?php else: ?>
            <!-- Navbar untuk User -->
            <nav class="navbar navbar-expand-lg navbar-light shadow-sm fixed-top bg-white">
                <div class="container">
                    <a class="navbar-brand fw-bold" href="#home">Perpustakaan Pinan</a>
                    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                        <span class="navbar-toggler-icon"></span>
                    </button>
                    <div class="collapse navbar-collapse" id="navbarNav">
                        <ul class="navbar-nav ms-auto">
                            <li class="nav-item"><a class="nav-link" href="../../index.php#home">Home</a></li>
                            <li class="nav-item"><a class="nav-link" href="../../index.php#features">Fitur</a></li>
                            <li class="nav-item"><a class="nav-link" href="">Buku</a></li>
                            <li class="nav-item"><a class="nav-link" href="../../index.php#contact">Kontak</a></li>
                        </ul>

                        <?php if(isset($_SESSION['username'])):?>
                        <div class="btn-group">
                            <button class="btn btn-secondary btn-sm dropdown-toggle" type="button"
                                data-bs-toggle="dropdown" aria-expanded="false">
                                <?php 
                        $nama_user = $_SESSION['username'];
                        echo strlen($nama_user) > 5 ? substr($nama_user, 0, 5) . "..." : $nama_user; 
                        ?>
                            </button>
                            <ul class="dropdown-menu p-1">
                                <li><a class="nav-link btn-primary rounded p-1 mb-1" href="../../config/logout.php">Logout</a>
                                </li>
                                <li><a class="nav-link btn-primary rounded p-1" href="pages/users/reset_pass.php">Reset
                                        Password</a></li>
                            </ul>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>
            </nav>
            <div class="container pt-5 mt-5">
                <?php endif; ?>

                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h1 class="display-5 fw-bold">Koleksi Buku</h1>
                    <?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin'): ?>
                    <a href="tambah_buku.php" class="btn btn-primary btn-lg rounded-pill">Tambah Buku</a>
                    <?php endif; ?>
                </div>

                <!-- Filter Form -->
                <form id="filterForm" class="row g-3 mb-5">
                    <div class="col-12 col-md-4">
                        <input type="text" id="search" name="search" class="form-control"
                            placeholder="Cari judul buku...">
                    </div>
                    <div class="col-6 col-md-3">
                        <select id="genre" name="genre" class="form-control">
                            <option value="">Semua Genre</option>
                            <?php while ($g = $genreResult->fetch_assoc()): ?>
                            <option value="<?= htmlspecialchars($g['id']) ?>"><?= htmlspecialchars($g['nama_genre']) ?>
                            </option>
                            <?php endwhile; ?>
                        </select>
                    </div>
                    <div class="col-6 col-md-3">
                        <select id="pengarang" name="pengarang" class="form-control">
                            <option value="">Semua Pengarang</option>
                            <?php while ($p = $pengarangResult->fetch_assoc()): ?>
                            <option value="<?= htmlspecialchars($p['pengarang']) ?>">
                                <?= htmlspecialchars($p['pengarang']) ?></option>
                            <?php endwhile; ?>
                        </select>
                    </div>
                    <div class="col-12 col-md-2 d-grid">
                        <button type="button" id="resetBtn" class="btn btn-secondary">Reset</button>
                    </div>
                </form>

                <!-- Grid Buku: responsif mobile-first -->
                <div id="hasil-buku" class="row row-cols-1 row-cols-sm-2 row-cols-md-3 row-cols-lg-4 g-4">
                    <!-- Card Buku akan di-load di sini via AJAX -->
                </div>

                <div id="notfound" class="alert alert-warning text-center mt-4" style="display:none;">
                    Data buku tidak ditemukan.
                </div>

                <?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin'): ?>
            </div> <!-- tutup flex-grow -->
        </div> <!-- tutup d-flex -->
        <?php else: ?>
    </div> <!-- tutup container -->
    <?php endif; ?>

    <!-- JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
    // Elemen UI
    const hasilEl = document.getElementById('hasil-buku');
    const notfoundEl = document.getElementById('notfound');
    const searchInput = document.getElementById('search');
    const genreSelect = document.getElementById('genre');
    const pengarangSelect = document.getElementById('pengarang');
    const resetBtn = document.getElementById('resetBtn');
    let debounceTimer;

    // Fungsi load data via AJAX
    function loadData(page = 1) {
        const params = new URLSearchParams({
            search: searchInput.value,
            genre: genreSelect.value,
            pengarang: pengarangSelect.value,
            page
        });
        // Tampilkan spinner sementara
        hasilEl.innerHTML = `
        <div class="d-flex justify-content-center my-5">
          <div class="spinner-border text-primary" role="status">
            <span class="visually-hidden">Loading...</span>
          </div>
        </div>`;

        fetch(`../../config/search.php?${params}`)
            .then(res => res.text())
            .then(html => {
                hasilEl.innerHTML = html.trim() || '';
                notfoundEl.style.display = html.trim() === '' ? 'block' : 'none';
            })
            .catch(() => {
                hasilEl.innerHTML = '';
                notfoundEl.textContent = 'Gagal memuat data.';
                notfoundEl.style.display = 'block';
            });
    }

    // Debounce untuk input
    function debounce(fn, delay) {
        clearTimeout(debounceTimer);
        debounceTimer = setTimeout(fn, delay);
    }

    // Event listeners
    searchInput.addEventListener('input', () => debounce(() => loadData(), 300));
    genreSelect.addEventListener('change', () => loadData());
    pengarangSelect.addEventListener('change', () => loadData());
    resetBtn.addEventListener('click', () => {
        searchInput.value = '';
        genreSelect.value = '';
        pengarangSelect.value = '';
        loadData();
    });

    // Inisialisasi load pertama
    document.addEventListener('DOMContentLoaded', () => loadData());

    hasilEl.addEventListener('click', function(e) {
        const detailBtn = e.target.closest('.btn-detail');
        if (detailBtn) {
            const idBuku = detailBtn.dataset.id;
            fetch(`../config/detail_buku.php?id=${idBuku}`)
                .then(res => res.text())
                .then(html => {
                    document.querySelector('#detailModal .modal-body').innerHTML = html;
                    new bootstrap.Modal(document.getElementById('detailModal')).show();
                });
        }
    });
    </script>
</body>

</html>