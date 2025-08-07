<?php
session_start();
require_once 'koneksi.php';

$message = '';

// Proses saat form dikirim
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Ambil data dari form
    $username         = trim($_POST['username']);
    $email            = trim($_POST['email']);
    $password         = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    // Validasi awal
    if (empty($username) || empty($email) || empty($password) || empty($confirm_password)) {
        $message = "<div class='alert alert-danger'>Semua field wajib diisi.</div>";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $message = "<div class='alert alert-danger'>Format email tidak valid.</div>";
    } elseif (strlen($password) < 6) {
        $message = "<div class='alert alert-danger'>Password minimal 6 karakter.</div>";
    } elseif ($password !== $confirm_password) {
        $message = "<div class='alert alert-danger'>Konfirmasi password tidak cocok.</div>";
    } else {
        // Cek apakah email sudah digunakan
        $stmt_check = $conn->prepare("SELECT id FROM users WHERE username = ? OR email = ?");
        $stmt_check->bind_param("ss", $username, $email);
        $stmt_check->execute();
        $stmt_check->store_result();

        if ($stmt_check->num_rows > 0) {
            $message = "<div class='alert alert-danger'>Username atau Email sudah terdaftar.</div>";
        } else {
            // Simpan user
            $password_hashed = password_hash($password, PASSWORD_DEFAULT);
            $stmt_insert = $conn->prepare("INSERT INTO users (username, email, password_hash) VALUES (?, ?, ?)");
            $stmt_insert->bind_param("sss", $username, $email, $password_hashed);

            if ($stmt_insert->execute()) {
                $message = "<div class='alert alert-success'>Pendaftaran berhasil! Silakan <a href='signin.php'>masuk</a>.</div>";
            } else {
                $message = "<div class='alert alert-danger'>Pendaftaran gagal: " . $stmt_insert->error . "</div>";
            }
            $stmt_insert->close();
        }

        $stmt_check->close();
    }

    $conn->close();
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Daftar - Club Motor Nusantara</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/app.css">
</head>
<body>
    <div class="whole">
    <div class="card-container">
        <div class="card">
            <div class="card-header"><h3>Daftar Akun</h3></div>
            <div class="card-body">
                <?php echo $message; ?>
                <?php
                $hideForm = false;
                if (strpos($message, 'alert-success') !== false && strpos($message, 'berhasil') !== false) {
                    $hideForm = true;
                }
                ?>
                <?php if (!$hideForm): ?>
                <form action="signup.php" method="post">
                    <div class="form-group">
                        <label for="username">Username</label>
                        <input type="text" class="form-control" id="username" name="username" value="<?php echo isset($_POST['username']) ? htmlspecialchars($_POST['username']) : ''; ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="email">Email</label>
                        <input type="email" class="form-control" id="email" name="email" value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="password">Password</label>
                        <input type="password" class="form-control" id="password" name="password" required>
                    </div>
                    <div class="form-group">
                        <label for="confirm_password">Konfirmasi Password</label>
                        <input type="password" class="form-control" id="confirm_password" name="confirm_password" required>
                    </div>
                    <button type="submit" class="btn btn-custom-orange btn-block">Daftar</button>
                </form>
                <?php endif; ?>
                <div class="form-footer-links">
                    <p>Sudah punya akun? <a href="signin.php">Masuk</a> | <a href="index.php">Beranda</a></p>
                </div>
            </div>
        </div>
    </div>
                </div>
</body>
</html>