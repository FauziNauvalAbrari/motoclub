<?php
// Mulai session
session_start();

// Hapus semua data session
$_SESSION = array();

// Jika session menggunakan cookie, hapus cookie session di browser
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000, 
        $params["path"], $params["domain"],
        $params["secure"], $params["httponly"]
    );
}

// Hancurkan session di server
session_destroy();

// Redirect ke halaman signin setelah logout
header("Location: signin.php");
exit();

?>