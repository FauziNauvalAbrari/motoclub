<?php
if (session_status() == PHP_SESSION_NONE) session_start();
require_once 'koneksi.php';
include_once 'sidebar.php';

if (!($_SESSION['loggedin'] ?? false)) {
    $_SESSION['flash_message'] = ['type' => 'danger', 'text' => 'Akses ditolak. Anda harus login.'];
    header("Location: signin.php");
    exit();
}

$artikel_id = $_GET['id'] ?? $_POST['artikel_id'] ?? null;
$judul = '';
$konten = '';
$gambar_lama = '';
$form_errors = [];

if ($_SERVER["REQUEST_METHOD"] == "GET" && filter_var($artikel_id, FILTER_VALIDATE_INT)) {
    $stmt = $conn->prepare("SELECT id, judul, konten, gambar FROM artikel WHERE id = ?");
    $stmt->bind_param("i", $artikel_id);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($data = $result->fetch_assoc()) {
        $judul = $data['judul'];
        $konten = $data['konten'];
        $gambar_lama = $data['gambar'];
    } else {
        $_SESSION['flash_message'] = ['type' => 'danger', 'text' => 'Artikel tidak ditemukan.'];
        header("Location: artikel.php");
        exit();
    }
    $stmt->close();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $judul = trim($_POST['judul'] ?? '');
    $konten = trim($_POST['konten'] ?? '');
    $gambar_lama = $_POST['gambar_lama'] ?? '';

    if (!$judul) $form_errors['judul'] = "Judul wajib diisi.";
    if (!$konten) $form_errors['konten'] = "Konten wajib diisi.";

    $gambar_nama = $gambar_lama;

    if (!empty($_FILES['gambar']['name'])) {
        $gambar_tmp = $_FILES['gambar']['tmp_name'];
        $gambar_ext = strtolower(pathinfo($_FILES['gambar']['name'], PATHINFO_EXTENSION));
        $allowed_ext = ['jpg', 'jpeg', 'png', 'gif', 'webp'];

        if (in_array($gambar_ext, $allowed_ext)) {
            $gambar_nama = uniqid() . '.' . $gambar_ext;
            $target_path = "uploads/artikel/" . $gambar_nama;
            if (!move_uploaded_file($gambar_tmp, $target_path)) {
                $form_errors['gambar'] = "Gagal mengupload gambar.";
            } else {
                if (!empty($gambar_lama) && file_exists("uploads/artikel/" . $gambar_lama)) {
                    unlink("uploads/artikel/" . $gambar_lama);
                }
            }
        } else {
            $form_errors['gambar'] = "Ekstensi file tidak diizinkan.";
        }
    }

    if (empty($form_errors)) {
        $stmt = $conn->prepare("UPDATE artikel SET judul = ?, konten = ?, gambar = ? WHERE id = ?");
        $stmt->bind_param("sssi", $judul, $konten, $gambar_nama, $artikel_id);
        if ($stmt->execute()) {
            $_SESSION['flash_message'] = ['type' => 'success', 'text' => 'Artikel berhasil diperbarui!'];
            header("Location: artikel.php");
            exit();
        } else {
            $form_errors['db'] = "Gagal memperbarui artikel: " . htmlspecialchars($stmt->error);
        }
        $stmt->close();
    }
}
?>

<!-- Form HTML -->
<div class="content-area">
    <div class="container-fluid">
        <h1 class="gallery-page-title">EDIT ARTIKEL</h1>
        <hr class="gallery-page-hr">

        <?php if ($artikel_id && empty($form_errors['id'])) : ?>
        <div class="form-container-edit">
            <form action="edit_artikel.php" method="POST" enctype="multipart/form-data">
                <input type="hidden" name="artikel_id" value="<?= htmlspecialchars($artikel_id) ?>">
                <input type="hidden" name="gambar_lama" value="<?= htmlspecialchars($gambar_lama) ?>">

                <div class="form-group">
                    <label for="judul">Judul Artikel <span class="text-danger">*</span></label>
                    <input type="text" class="form-control <?= isset($form_errors['judul']) ? 'is-invalid' : '' ?>" id="judul" name="judul" value="<?= htmlspecialchars($judul) ?>" required>
                    <?php if (isset($form_errors['judul'])): ?>
                        <div class="invalid-feedback"><?= $form_errors['judul'] ?></div>
                    <?php endif; ?>
                </div>

                <div class="form-group">
                    <label for="konten">Konten Artikel <span class="text-danger">*</span></label>
                    <textarea class="form-control <?= isset($form_errors['konten']) ? 'is-invalid' : '' ?>" id="konten" name="konten" rows="10" required><?= htmlspecialchars($konten) ?></textarea>
                    <?php if (isset($form_errors['konten'])): ?>
                        <div class="invalid-feedback"><?= $form_errors['konten'] ?></div>
                    <?php endif; ?>
                </div>

                <div class="form-group">
                    <label for="gambar">Gambar Artikel</label>
                    <input type="file" class="form-control-file <?= isset($form_errors['gambar']) ? 'is-invalid' : '' ?>" name="gambar" accept="image/*">
                    <?php if (isset($form_errors['gambar'])): ?>
                        <div class="invalid-feedback"><?= $form_errors['gambar'] ?></div>
                    <?php endif; ?>

                    <?php if (!empty($gambar_lama)) : ?>
                        <div class="mt-2">
                            <p>Gambar saat ini:</p>
                            <img src="uploads/artikel/<?= htmlspecialchars($gambar_lama) ?>" alt="Gambar Artikel" style="max-width: 200px;">
                        </div>
                    <?php endif; ?>
                </div>

                <hr>
                <button type="submit" class="btn btn-submit-custom"><i class="fas fa-save"></i> Simpan Perubahan</button>
                <a href="artikel.php" class="btn btn-outline-secondary ml-2"><i class="fas fa-times"></i> Batal</a>
            </form>
        </div>
        <?php endif; ?>
    </div>
</div>

<!-- JS Bootstrap -->
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
