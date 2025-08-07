<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once 'koneksi.php';

// Cek login
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    $_SESSION['flash_message'] = [
        'type' => 'danger',
        'text' => 'Akses ditolak. Anda harus login untuk menghapus artikel.'
    ];
    header("Location: signin.php");
    exit();
}

// Default error
$flash_message = [
    'type' => 'danger',
    'text' => 'Terjadi kesalahan saat menghapus artikel.'
];

// Validasi ID
if (isset($_GET['id']) && filter_var($_GET['id'], FILTER_VALIDATE_INT) && $_GET['id'] > 0) {
    $artikel_id = (int)$_GET['id'];

    $sql = "DELETE FROM artikel WHERE id = ?";
    $stmt = $conn->prepare($sql);

    if ($stmt) {
        $stmt->bind_param("i", $artikel_id);
        if ($stmt->execute()) {
            if ($stmt->affected_rows > 0) {
                $flash_message = [
                    'type' => 'success',
                    'text' => 'Artikel berhasil dihapus.'
                ];
            } else {
                $flash_message = [
                    'type' => 'warning',
                    'text' => 'Artikel tidak ditemukan atau sudah dihapus.'
                ];
            }
        } else {
            $flash_message['text'] = 'Gagal menghapus artikel dari database: ' . htmlspecialchars($stmt->error);
        }
        $stmt->close();
    } else {
        $flash_message['text'] = 'Gagal mempersiapkan perintah: ' . htmlspecialchars($conn->error);
    }
} else {
    $flash_message['text'] = 'ID Artikel tidak valid atau tidak disertakan.';
}

$_SESSION['flash_message'] = $flash_message;
header("Location: artikel.php");
exit();
