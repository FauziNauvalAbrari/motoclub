<?php
// Memulai sesi jika belum dimulai
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Menyertakan file koneksi
require_once 'koneksi.php';

$message = ''; // menyimpan pesan 

// Jika pengguna sudah login, alihkan ke halaman index
if (isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}

// Menangani permintaan POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $identifier = trim($_POST['identifier']);
    $password = $_POST['password'];

    // Validasi input
    if (empty($identifier) || empty($password)) {
        $message = "<div class='alert alert-danger'>Username/Email dan Password wajib diisi.</div>";
    } else {
        // Menentukan jenis field berdasarkan format input (email atau username)
        $field_type = filter_var($identifier, FILTER_VALIDATE_EMAIL) ? 'email' : 'username';

        // Query untuk mencari user berdasarkan username/email
        $sql = "SELECT id, username, password_hash FROM users WHERE $field_type = ?";
        $stmt = $conn->prepare($sql);
       
        if ($stmt === false) {
            $message = "<div class='alert alert-danger'>Terjadi kesalahan pada sistem. Silakan coba lagi nanti.</div>";
        } else {
            // Bind dan eksekusi query
            $stmt->bind_param("s", $identifier);
            $stmt->execute();
            $result = $stmt->get_result();

            // Verifikasi hasil query
            if ($user = $result->fetch_assoc()) {
                // Verifikasi password
                if (password_verify($password, $user['password_hash'])) {

                    $_SESSION['user_id'] = $user['id'];
                    $_SESSION['username'] = $user['username'];
                    $_SESSION['loggedin'] = true;

                    header("Location: index.php");
                    exit();
                } else {
                    $message = "<div class='alert alert-danger'>Password salah.</div>";
                }
            } else {
                $message = "<div class='alert alert-danger'>Username atau Email tidak ditemukan.</div>";
            }
            $stmt->close();
        }
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Masuk - Club Motor Nusantara</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/app.css">
</head>
<body>
    <div class="whole">
    <div class="card-container">
        <div class="card">
            <div class="card-header"><h3>Masuk Akun</h3></div>
            <div class="card-body">
                <?php 
                if (!empty($message)) {
                    echo $message; 
                }
                ?>
                <form action="signin.php" method="post">
                    <div class="form-group">
                        <label for="identifier">Username atau Email</label>
                        <input type="text" class="form-control" id="identifier" name="identifier" required value="<?php echo isset($_POST['identifier']) ? htmlspecialchars($_POST['identifier']) : ''; ?>">
                    </div>
                    <div class="form-group">
                        <label for="password">Password</label>
                        <input type="password" class="form-control" id="password" name="password" required>
                    </div>
                    <button type="submit" class="btn btn-custom-orange btn-block">Masuk</button>
                </form>
                <div class="form-footer-links">
                    <p>Belum punya akun? <a href="signup.php">Daftar</a> | <a href="index.php">Beranda</a></p>
                </div>
            </div>
        </div>
    </div></div>
    <?php
        if (isset($conn) && $conn instanceof mysqli) {
        }
    ?>
</body>
</html>