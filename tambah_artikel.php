<?php
// Mulai session
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once 'koneksi.php';

if (file_exists('sidebar.php')) {
    include 'sidebar.php';
}

// Cek login
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    $_SESSION['flash_message'] = [
        'type' => 'danger',
        'text' => 'Akses ditolak. Anda harus login untuk menambah artikel.'
    ];
    header("Location: signin.php");
    exit();
}

// Inisialisasi variabel form
$judul = '';
$konten = '';
$form_errors = [];

// Proses saat form disubmit
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $judul  = trim($_POST['judul'] ?? '');
    $konten = trim($_POST['konten'] ?? '');
    $gambar_nama = null;

    // Validasi input teks
    if (empty($judul)) {
        $form_errors['judul'] = "Judul artikel wajib diisi.";
    }

    if (empty($konten)) {
        $form_errors['konten'] = "Konten artikel wajib diisi.";
    }

    // Proses upload gambar
    if (isset($_FILES['gambar']) && $_FILES['gambar']['error'] === UPLOAD_ERR_OK) {
        $gambar_tmp  = $_FILES['gambar']['tmp_name'];
        $gambar_asli = basename($_FILES['gambar']['name']);
        $gambar_ext  = strtolower(pathinfo($gambar_asli, PATHINFO_EXTENSION));
        $nama_unik   = uniqid('img_', true) . '.' . $gambar_ext;
        $folder_tujuan = 'uploads/artikel/';

        // Cek dan buat folder jika belum ada
        if (!is_dir($folder_tujuan)) {
            mkdir($folder_tujuan, 0755, true);
        }

        $allowed_ext = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
        if (in_array($gambar_ext, $allowed_ext)) {
            if (move_uploaded_file($gambar_tmp, $folder_tujuan . $nama_unik)) {
                $gambar_nama = $nama_unik;
            } else {
                $form_errors['gambar'] = "Gagal mengunggah gambar.";
            }
        } else {
            $form_errors['gambar'] = "Format gambar tidak didukung. Hanya jpg, jpeg, png, gif, webp.";
        }
    }

    // Jika tidak ada error, simpan ke database
    if (empty($form_errors)) {
        $tanggal_publikasi = date("Y-m-d H:i:s");
        $sql = "INSERT INTO artikel (judul, konten, publikasi, gambar) VALUES (?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);

        if ($stmt) {
            $stmt->bind_param("ssss", $judul, $konten, $tanggal_publikasi, $gambar_nama);

            if ($stmt->execute()) {
                $_SESSION['flash_message'] = [
                    'type' => 'success',
                    'text' => 'Artikel berhasil ditambahkan!'
                ];
                header("Location: artikel.php");
                exit();
            } else {
                $form_errors['db'] = "Gagal menyimpan artikel: " . htmlspecialchars($stmt->error);
            }

            $stmt->close();
        } else {
            $form_errors['db'] = "Gagal menyiapkan query: " . htmlspecialchars($conn->error);
        }
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Tambah Artikel</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
<div class="content-area">
    <div class="container-fluid">
        <h1 class="gallery-page-title">Tambah Artikel Baru</h1>
        <hr class="gallery-page-hr">

        <?php if (!empty($form_errors['db'])): ?>
            <div class="alert alert-danger" role="alert">
                <?php echo $form_errors['db']; ?>
            </div>
        <?php endif; ?>

        <div class="form-container">
            <form action="tambah_artikel.php" method="POST" enctype="multipart/form-data">
                <div class="form-group">
                    <label for="judul">Judul Artikel <span class="text-danger">*</span></label>
                    <input type="text" class="form-control <?php echo isset($form_errors['judul']) ? 'is-invalid' : ''; ?>" id="judul" name="judul" value="<?php echo htmlspecialchars($judul); ?>" required>
                    <?php if (isset($form_errors['judul'])): ?>
                        <div class="invalid-feedback"><?php echo $form_errors['judul']; ?></div>
                    <?php endif; ?>
                </div>

                <div class="form-group">
                    <label for="konten">Konten Artikel <span class="text-danger">*</span></label>
                    <textarea class="form-control <?php echo isset($form_errors['konten']) ? 'is-invalid' : ''; ?>" id="konten" name="konten" rows="10" required><?php echo htmlspecialchars($konten); ?></textarea>
                    <?php if (isset($form_errors['konten'])): ?>
                        <div class="invalid-feedback"><?php echo $form_errors['konten']; ?></div>
                    <?php endif; ?>
                </div>

                <div class="form-group">
                    <label for="gambar">Gambar Artikel</label>
                    <input type="file" class="form-control-file" id="gambar" name="gambar" accept="image/*">
                    <?php if (isset($form_errors['gambar'])): ?>
                        <div class="text-danger mt-1"><?php echo $form_errors['gambar']; ?></div>
                    <?php endif; ?>
                </div>

                <hr>
                <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Simpan Artikel</button>
                <a href="artikel.php" class="btn btn-outline-secondary ml-2"><i class="fas fa-times"></i> Batal</a>
            </form>
        </div>
    </div>
</div>

<!-- JS Bootstrap -->
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
