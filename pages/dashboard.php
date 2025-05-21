<?php
// dashboard.php
session_start();
include '../config/koneksi.php';

// Ambil data dari database
$buku = mysqli_query($koneksi, "SELECT COUNT(*) AS total FROM buku");
$peminjaman = mysqli_query($koneksi, "SELECT COUNT(*) AS total FROM peminjaman WHERE status='dipinjam'");
$user = mysqli_query($koneksi, "SELECT COUNT(*) AS total FROM users");

$total_buku = mysqli_fetch_assoc($buku)['total'];
$total_peminjaman = mysqli_fetch_assoc($peminjaman)['total'];
$total_user = mysqli_fetch_assoc($user)['total'];

// Data grafik peminjaman per bulan
$grafik = mysqli_query($koneksi, "
    SELECT MONTH(tanggal_pinjam) AS bulan, COUNT(*) AS total 
    FROM peminjaman 
    GROUP BY MONTH(tanggal_pinjam)
");

$data_bulan = [];
$data_total = [];

while ($row = mysqli_fetch_assoc($grafik)) {
    $data_bulan[] = date('F', mktime(0, 0, 0, $row['bulan'], 10)); // Nama bulan
    $data_total[] = $row['total'];
}

?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Dashboard Admin</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link rel="stylesheet" href="../assets/theme.css">
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
        <a href="buku/list_buku.php"><i class="fas fa-book me-2"></i>Kelola Buku</a>
        <a href="buku/daftar_pinjaman.php"><i class="fas fa-sync-alt me-2"></i>Peminjaman</a>
        <a href="users/list_user.php"><i class="fas fa-users me-2"></i>Daftar User</a>
        <a href="signup.php"><i class="fas fa-user-plus me-2"></i>Tambah User</a>
        <a href="../config/logout.php"><i class="fas fa-sign-out-alt me-2"></i>Logout</a>
    </div>

    <div class="content text-dark">
        <div class="d-flex justify-content-between align-items-center mb-3 bg-light p-3 rounded">
            <h2>Dashboard Admin</h2>
        </div>
        <div class="row mt-4 g-4">
            <div class="col-12 col-md-4">
                <div class="card p-4 text-center bg-white">
                    <i class="fas fa-book text-primary"></i>
                    <h5>Total Buku</h5>
                    <h3 class="counter" data-count="<?php echo $total_buku; ?>">0</h3>
                </div>
            </div>
            <div class="col-12 col-md-4">
                <div class="card p-4 text-center bg-white">
                    <i class="fas fa-exchange-alt text-success"></i>
                    <h5>Peminjaman Aktif</h5>
                    <h3 class="counter" data-count="<?php echo $total_peminjaman; ?>">0</h3>
                </div>
            </div>
            <div class="col-12 col-md-4">
                <div class="card p-4 text-center bg-white">
                    <i class="fas fa-user text-warning"></i>
                    <h5>Total User</h5>
                    <h3 class="counter" data-count="<?php echo $total_user; ?>">0</h3>
                </div>
            </div>
        </div>
        <div class="row mt-5">
            <div class="col-12">
                <div class="card p-4">
                    <h5 class="mb-4">Grafik Peminjaman Buku per Bulan</h5>
                    <canvas id="grafikPeminjaman" height="100"></canvas>
                </div>
            </div>
        </div>
    </div>



    <script>
    // Sidebar toggle for mobile
    $('#sidebarToggle').on('click', function() {
        $('#sidebarMenu').toggleClass('active');
    });
    // Close sidebar when clicking outside (mobile)
    $(document).on('click', function(e) {
        if ($(window).width() < 992) {
            if (!$(e.target).closest('#sidebarMenu, #sidebarToggle').length) {
                $('#sidebarMenu').removeClass('active');
            }
        }
    });
    // Counter animation
    $('.counter').each(function() {
        var $this = $(this),
            countTo = $this.attr('data-count');
        $({
            countNum: $this.text()
        }).animate({
            countNum: countTo
        }, {
            duration: 1200,
            easing: 'swing',
            step: function() {
                $this.text(Math.floor(this.countNum));
            },
            complete: function() {
                $this.text(this.countNum);
            }
        });
    });
    </script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
    const ctx = document.getElementById('grafikPeminjaman').getContext('2d');
    const chart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: <?= json_encode($data_bulan); ?>,
            datasets: [{
                label: 'Jumlah Peminjaman',
                data: <?= json_encode($data_total); ?>,
                backgroundColor: 'rgba(54, 162, 235, 0.7)',
                borderColor: 'rgba(54, 162, 235, 1)',
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        stepSize: 1
                    }
                }
            }
        }
    });
    </script>

</body>

</html>