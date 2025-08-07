<?php
// Mulai session jika belum dimulai
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Sertakan file koneksi ke database
require_once 'koneksi.php';

// Sertakan file sidebar jika ada
if (file_exists('sidebar.php')) {
    include 'sidebar.php';
}

// Cek apakah user sudah login
$is_loggedin = isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true;

// Inisialisasi variabel untuk artikel dan pesan error
$artikel = null;
$error_message = '';

// Fungsi untuk memformat tanggal dari format MySQL ke format Indonesia
if (!function_exists('format_tanggal')) {
    function format_tanggal($tanggal_mysql) {
        if (empty($tanggal_mysql) || $tanggal_mysql === '0000-00-00 00:00:00' || $tanggal_mysql === '0000-00-00') {
            return 'Tanggal tidak valid';
        }
        try {
            // Konversi ke zona waktu Asia/Makassar
            $date = new DateTime($tanggal_mysql, new DateTimeZone('UTC'));
            $date->setTimezone(new DateTimeZone('Asia/Makassar'));

            // Array hari dan bulan dalam bahasa Indonesia
            $hari = array("Minggu", "Senin", "Selasa", "Rabu", "Kamis", "Jumat", "Sabtu");
            $bulan = array("Januari", "Februari", "Maret", "April", "Mei", "Juni", "Juli", "Agustus", "September", "Oktober", "November", "Desember");

            // Format akhir: Senin, 7 Juni
            return $hari[$date->format('w')] . ", " . $date->format('j') . " " . $bulan[$date->format('n') - 1] . " ";
        } catch (Exception $e) {
            error_log("Error formatting date: " . $e->getMessage() . " for date: " . $tanggal_mysql);
            return 'Format tanggal salah';
        }
    }
}

// Ambil ID artikel dari URL dan pastikan valid
if (isset($_GET['id']) && filter_var($_GET['id'], FILTER_VALIDATE_INT)) {
    $artikel_id = (int)$_GET['id'];

    // Query untuk mengambil artikel berdasarkan ID
    $sql = "SELECT id, judul, konten, publikasi, gambar  FROM artikel WHERE id = ?";
    $stmt = $conn->prepare($sql);

    if ($stmt) {
        $stmt->bind_param("i", $artikel_id);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows === 1) {
            $artikel = $result->fetch_assoc(); // Simpan data artikel
        } else {
            $error_message = "Artikel tidak ditemukan.";
        }
        $stmt->close();
    } else {
        $error_message = "Gagal mempersiapkan statement: " . $conn->error;
    }
} else {
    $error_message = "ID Artikel tidak valid atau tidak disertakan.";
}
?>

<!-- Style tambahan untuk sidebar -->
<style>
    .sidebar {
        position: fixed;
    }
</style>

<!-- Konten Utama -->
<div class="content-areas">
    <div class="container-fluid">

        <!-- Tampilkan pesan error jika ada -->
        <?php if (!empty($error_message)): ?>
            <div class="alert alert-danger alert-custom" role="alert">
                <?php echo htmlspecialchars($error_message); ?>
                <p><a href="artikel.php" class="alert-link">Kembali ke daftar artikel</a></p>
            </div>

        <!-- Tampilkan artikel jika data berhasil ditemukan -->
        <?php elseif ($artikel): ?>
            <article class="article-detail-container">
                <h1 class="gallery-page-title"><?php echo htmlspecialchars($artikel['judul']); ?></h1>
                <hr class="gallery-page-hr">
                
                <div class="article-meta-detail">
                    <span><i class="fas fa-calendar-alt"></i> 
                        <?php echo format_tanggal($artikel['publikasi']); ?>
                    </span>
                </div>

                <!-- Tampilkan gambar jika ada -->
                <?php if (!empty($artikel['gambar']) && file_exists("uploads/artikel/" . $artikel['gambar'])): ?>
                    <div class="article-image mt-3 mb-4 text-center">
                        <img src="uploads/artikel/<?php echo htmlspecialchars($artikel['gambar']); ?>" 
                            alt="Gambar Artikel" 
                            style="max-width: 100%; height: auto; border-radius: 8px;">
                    </div>
                <?php endif; ?>

                <div class="article-full-content">
                    <?php echo nl2br(htmlspecialchars($artikel['konten'])); ?>
                </div>

                <?php if ($is_loggedin): ?>
                    <div class="edit-link-container mt-4 pt-3 border-top">
                        <a href="edit_artikel.php?id=<?php echo $artikel['id']; ?>" class="btn btn-outline-primary">
                            <i class="fas fa-edit"></i> Edit Artikel Ini
                        </a>
                    </div>
                <?php endif; ?>
            </article>

            <div class="back-link-container text-center">
                <a href="artikel.php" class="btn btn-outline-secondary">
                    <i class="fas fa-arrow-left"></i> Kembali ke Daftar Artikel
                </a>
            </div>

        <?php else: ?>
            <div class="alert alert-warning alert-custom" role="alert">
                Artikel tidak dapat ditampilkan.
                <p><a href="artikel.php" class="alert-link">Kembali ke daftar artikel</a></p>
            </div>
        <?php endif; ?>

    </div>
</div>

<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
