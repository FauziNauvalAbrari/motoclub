<?php
// Mulai session jika belum dimulai
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// file sidebar
if (file_exists('sidebar.php')) {
    include 'sidebar.php';
}

// Hubungkan ke database
require_once 'koneksi.php';

// Cek apakah pengguna sudah login
$is_loggedin = isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true;

// Query untuk mengambil daftar artikel yang dipublikasikan, urut dari yang terbaru
$sql = "SELECT id, judul, publikasi, konten, gambar FROM artikel ORDER BY publikasi DESC, id DESC";
$result = $conn->query($sql);

// Proses hasil query
if ($result) {
    if ($result->num_rows > 0) {
        // Simpan semua artikel ke dalam array
        while ($row = $result->fetch_assoc()) {
            $artikel_list[] = $row;
        }
    } else {
        $info_message = "Belum ada artikel yang dipublikasikan.";
    }
} else {
    $error_message = "Gagal mengambil data artikel: " . $conn->error;
}

// Fungsi untuk memotong teks panjang menjadi ringkasan
function potong_teks($teks, $panjang = 10, $akhiran = '...') {
    if (strlen($teks) > $panjang) {
        $teks = substr($teks, 0, $panjang);
        $teks = substr($teks, 0, strrpos($teks, ' '));
        $teks .= $akhiran;
    }
    return $teks;
}

// Fungsi untuk memformat tanggal MySQL menjadi format bahasa Indonesia
function format_tanggal($tanggal_mysql) {
    if (empty($tanggal_mysql) || $tanggal_mysql === '0000-00-00') {
        return 'Tanggal tidak valid';
    }
    try {
        $date = new DateTime($tanggal_mysql);
        $hari = array("Minggu", "Senin", "Selasa", "Rabu", "Kamis", "Jumat", "Sabtu");
        $bulan = array("Januari", "Februari", "Maret", "April", "Mei", "Juni", "Juli", "Agustus", "September", "Oktober", "November", "Desember");
        
        return $hari[$date->format('w')] . ", " . $date->format('j') . " " . $bulan[$date->format('n') - 1] . " ";
    } catch (Exception $e) {
        return 'Format tanggal salah';
    }
}
?>
<style>
    .sidebar {
        position: fixed; 
    }
</style>

<div class="content-areas">
    <div class="container-fluid">
        <h1 class="gallery-page-title">ARTIKEL</h1>
        <hr class="gallery-page-hr">

        <div class="page-header">
            <!-- Tombol tambah artikel jika user sudah login -->
            <?php if ($is_loggedin): ?>
                <a href="tambah_artikel.php" class="btn btn-tambah-artikel">
                    <i class="fas fa-plus-circle"></i> Tambah Artikel Baru
                </a>
            <?php endif; ?>
        </div>
        
        <!-- Tampilkan daftar artikel -->
        <?php if (!empty($artikel_list)): ?>
            <?php foreach ($artikel_list as $artikel): ?>
                <div class="article-card" id="artikel-<?php echo $artikel['id']; ?>">
                    <img src="uploads/artikel/<?php echo htmlspecialchars($artikel['gambar']); ?>" 
                         alt="<?php echo htmlspecialchars($artikel['judul']); ?>">
                    
                    <div class="article-content-wrapper">
                        <h2 class="card-title">
                            <a href="detail_artikel.php?id=<?php echo $artikel['id']; ?>">
                                <?php echo htmlspecialchars($artikel['judul']); ?>
                            </a>
                        </h2>

                        <div class="article-meta mb-2">
                            <i class="fas fa-calendar-alt"></i>
                            Dipublikasikan pada: <?php echo format_tanggal($artikel['publikasi']); ?>
                        </div>

                        <div class="article-content mb-2">
                            <?php
                                $konten_asli = $artikel['konten'];
                                $panjang_ringkasan = 150;

                                $ringkasan_teks = potong_teks($konten_asli, $panjang_ringkasan);

                                echo nl2br(htmlspecialchars($ringkasan_teks));
                                if (strlen($konten_asli) > strlen($ringkasan_teks)) {
                                    echo ' <a href="detail_artikel.php?id=' . $artikel['id'] . '" class="font-weight-bold">Baca Selengkapnya</a>';
                                }
                            ?>
                        </div>

                        <a href="detail_artikel.php?id=<?php echo $artikel['id']; ?>" class="btn btn-outline-primary btn-sm">Baca Selengkapnya</a>

                        <?php if ($is_loggedin): ?>
                            <div class="article-actions mt-2">
                                <a href="edit_artikel.php?id=<?php echo $artikel['id']; ?>" class="btn btn-sm btn-outline-primary">
                                    <i class="fas fa-edit"></i> Edit
                                </a>
                                <a href="hapus_artikel.php?id=<?php echo $artikel['id']; ?>" class="btn btn-sm btn-outline-danger" onclick="return confirm('Apakah Anda yakin ingin menghapus artikel ini?');">
                                    <i class="fas fa-trash-alt"></i> Hapus
                                </a>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>

            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>