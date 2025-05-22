<?php
// search.php
require_once 'database.php';
session_start();

// Ambil parameter

$search    = isset($_GET['search']) ? $koneksi->real_escape_string($_GET['search']) : '';
$genre     = isset($_GET['genre']) ? (int)$_GET['genre'] : 0;
$pengarang = isset($_GET['pengarang']) ? $koneksi->real_escape_string($_GET['pengarang']) : '';
$page      = isset($_GET['page']) ? max(1, (int)$_GET['page']) : 1;
$limit     = 8;
$offset    = ($page - 1) * $limit;

// Hitung total rows untuk pagination
$countSql  = "SELECT COUNT(DISTINCT b.id) AS total " .
             "FROM buku b " .
             "LEFT JOIN buku_genre bg ON b.id = bg.id_buku " .
             "WHERE 1 ";
if (!empty($search))    $countSql .= "AND b.judul LIKE '%$search%' ";
if (!empty($genre))     $countSql .= "AND bg.id_genre = $genre ";
if (!empty($pengarang)) $countSql .= "AND b.pengarang LIKE '%$pengarang%' ";

$countResult = $koneksi->query($countSql);
$totalRows   = ($countResult) ? (int)$countResult->fetch_assoc()['total'] : 0;
$totalPages  = ceil($totalRows / $limit);

// Query data buku
$sql = "SELECT b.id, b.judul, b.pengarang, b.penerbit, b.tahun_terbit, b.deskripsi, b.cover, b.stok, " .
       "GROUP_CONCAT(DISTINCT g.nama_genre SEPARATOR ', ') AS genres " .
       "FROM buku b " .
       "LEFT JOIN buku_genre bg ON b.id = bg.id_buku " .
       "LEFT JOIN genre g ON bg.id_genre = g.id " .
       "WHERE 1 ";
if (!empty($search))    $sql .= "AND b.judul LIKE '%$search%' ";
if (!empty($genre))     $sql .= "AND bg.id_genre = $genre ";
if (!empty($pengarang)) $sql .= "AND b.pengarang LIKE '%$pengarang%' ";
$sql .= "GROUP BY b.id " .
        "ORDER BY b.judul ASC " .
        "LIMIT $limit OFFSET $offset";

$result = $koneksi->query($sql);

// Output card jika ada
if ($result && $result->num_rows) {
  while ($b = $result->fetch_assoc()) {
?>
<!-- Modal -->
<div class="modal fade" id="deskripsiModal<?= $b['id'] ?>" tabindex="-1" aria-labelledby="deskripsiLabel<?= $b['id'] ?>"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content rounded-4 shadow">
            <div class="modal-header">
                <h5 class="modal-title" id="deskripsiLabel<?= $b['id'] ?>">Deskripsi</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
            </div>
            <div class="modal-body" style="max-height: 400px; overflow-y: auto;">
                <p><strong>Judul:</strong> <?= htmlspecialchars($b['judul']) ?></p>
                <p><strong>Pengarang:</strong> <?= htmlspecialchars($b['pengarang']) ?></p>
                <p><strong>Penerbit:</strong> <?= htmlspecialchars($b['penerbit']) ?></p>
                <p><strong>Tahun Terbit:</strong> <?= htmlspecialchars($b['tahun_terbit']) ?></p>
                <p><strong>Genre:</strong> <?= htmlspecialchars($b['genres'] ?? '-') ?></p>
                <hr>
                <strong>Deskripsi:</strong>
                <p><?= nl2br(htmlspecialchars($b['deskripsi'])) ?></p>
            </div>
        </div>
    </div>
</div>
<!-- Card -->
<div class="col">
    <div class="card h-100 shadow-sm favorit-card">
        <img src="../../assets/img/covers/<?= htmlspecialchars($b['cover']) ?>" class="card-img-top cover-img"
            alt="<?= htmlspecialchars($b['judul']) ?>">
        <div class="card-body d-flex flex-column">
            <h5 class="card-title"><?= htmlspecialchars($b['judul']) ?></h5>
            <p class="card-text text-muted mb-2"><?= htmlspecialchars($b['pengarang']) ?></p>
            <p class="card-text small mt-auto"><strong>Genre:</strong> <?= htmlspecialchars($b['genres']) ?></p>
            <p class="card-text small"><?= htmlspecialchars($b['penerbit']) ?> | <?= htmlspecialchars($b['stok']) ?> Buku</p>
            <div class="d-flex gap-2 mt-3">
                <button type="button" class="btn btn-outline-primary btn-sm flex-fill btn-deskripsi" data-bs-toggle="modal"
                    data-bs-target="#deskripsiModal<?= $b['id'] ?>">
                    Deskripsi
                </button>
                <?php if ($_SESSION['role'] == 'admin'): ?>
                <a href="edit_book.php?id=<?= htmlspecialchars($b['id']) ?>"
                    class="btn btn-sm btn-outline-primary d-flex align-items-center justify-content-center">
                    <i class="bi bi-pencil-square"></i>
                </a>
                <?php elseif ($_SESSION['role'] == 'user'): ?>
                <?php if ($b['stok'] > 0): ?>
                <a href="borrow_book.php?id=<?= htmlspecialchars($b['id']) ?>"
                    class="btn btn-sm btn-outline-primary d-flex align-items-center justify-content-center">
                    <i class="bi bi-bookmark-plus" style="font-size: 1rem;"></i>
                </a>
                <?php else: ?>
                <button class="btn btn-sm btn-outline-secondary" disabled>
                    <i class="bi bi-bookmark-plus"></i>
                </button>
                <?php endif; ?>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
<?php
  }

  // Pagination
  if ($totalPages > 1): ?>
<div class="col-12">
    <nav aria-label="Page navigation">
        <ul class="pagination justify-content-center">
            <?php for ($i = 1; $i <= $totalPages; $i++): ?>
            <li class="page-item bg-primary rounded<?= $i === $page ? 'active rounded' : '' ?>">
                <a class="page-link btn-outline-primary" href="javascript:void(0)" onclick="loadData(<?= $i ?>)"><?= $i ?></a>
            </li>
            <?php endfor; ?>
        </ul>
    </nav>
</div>
<?php endif;

} else {
  // Jika kosong, jangan output apa pun (JS akan tampilkan notfound)
}