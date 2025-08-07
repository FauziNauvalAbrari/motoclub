<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Club Motor Nusantara</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Righteous&display=swap" rel="stylesheet">
    <link rel="icon" href="logo.ico" type="image/x-icon" />
    <link rel="stylesheet" href="css/app.css">
</head>
<body>

    <div class="main-wrapper">
        <nav class="sidebar">
            <div class="logo-container">
                <img src="img/logo.png" alt="Logo Club Motor Nusantara" class="logo">
                <div class="social-icons">
                    <a href="#" target="_blank" title="Facebook"><i class="fab fa-facebook-f"></i></a>
                    <a href="#" target="_blank" title="Twitter"><i class="fab fa-twitter"></i></a>
                    <a href="#" target="_blank" title="Instagram"><i class="fab fa-instagram"></i></a>
                    <a href="#" target="_blank" title="LinkedIn"><i class="fab fa-linkedin-in"></i></a>
                </div>
            </div>
            <ul class="nav flex-column">
                <li class="nav-item">
                    <a class="nav-link" href="index.php"><i class="fas fa-home mr-1"></i></i>Home</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="artikel.php"><i class="fas fa-newspaper mr-1"></i>Artikel</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="event.php"><i class="fas fa-calendar-alt"></i>Event</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="galeri.php"><i class="fas fa-images mr-1"></i>Galery Foto</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="keluarga.php"><i class="fas fa-users mr-1"></i>Member kami</a>
                </li>
                <?php if (isset($_SESSION['user_id'])): ?>
                <li class="nav-item">
                    <a class="nav-link" href="logout.php"><i class="fas fa-sign-out-alt mr-1"></i>Logout</a>
                </li>
                <?php else: ?>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="loginDropdownToggle" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <i class="fas fa-sign-in-alt mr-1"></i>Login
                        </a>
                        <div class="dropdown-menu" aria-labelledby="loginDropdownToggle">
                            <a class="dropdown-item" href="signin.php"><i class="fas fa-sign-in-alt mr-1"></i>Sign in</a>
                            <a class="dropdown-item" href="signup.php"><i class="fas fa-user-plus mr-1"></i>Sign up</a>
                        </div>
                    </li>
                <?php endif; ?>
            </ul>
        </nav>