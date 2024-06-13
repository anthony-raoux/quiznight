<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
if (!isset($_SESSION['user_id'])) {
    header('Location: ../login.php');
    exit();
}
$username = $_SESSION['username'];
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="Responsive Admin &amp; Dashboard Template based on Bootstrap 5">
    <meta name="author" content="AdminKit">
    <meta name="keywords" content="adminkit, bootstrap, bootstrap 5, admin, dashboard, template, responsive, css, sass, html, theme, front-end, ui kit, web">
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link rel="shortcut icon" href="img/icons/icon-48x48.png">
    <link rel="canonical" href="https://demo-basic.adminkit.io/">
    <title>AdminKit Demo - Bootstrap 5 Admin Template</title>
    <link href="css/app.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
</head>

<body>
    <div class="wrapper">
        <nav id="sidebar" class="sidebar js-sidebar">
            <div class="sidebar-content js-simplebar">
                <a class="sidebar-brand" href="index.php">
                    <span class="align-middle">Quiz Night</span>
                </a>
                <ul class="sidebar-nav">
                    <li class="sidebar-header">Pages</li>
                    <li class="sidebar-item <?= ($page == 'home') ? 'active' : '' ?>">
                        <a class="sidebar-link" href="index.php">
                            <i class="bi bi-house"></i>
                            <span class="align-middle">Acceuil</span>
                        </a>
                    </li>
                    <li class="sidebar-item <?= ($page == 'view_quiz') ? 'active' : '' ?>">
                        <a class="sidebar-link" href="pages-profile.html">
                            <i class="bi bi-eye align-middle"></i>
                            <span class="align-middle">Voir mes Quiz</span>
                        </a>
                    </li>
                    <li class="sidebar-item <?= ($page == 'add_quiz') ? 'active' : '' ?>">
                        <a class="sidebar-link" href="add_quiz.php">
                            <i class="align-middle" data-feather="plus"></i>
                            <span class="align-middle">Ajouter un quiz</span>
                        </a>
                    </li>
                    <li class="sidebar-item <?= ($page == 'participate') ? 'active' : '' ?>">
                        <a class="sidebar-link" href="index.html">
                            <i class="bi bi-joystick"></i>
                            <span class="align-middle">Participez à un quiz</span>
                        </a>
                    </li>
                    <li class="sidebar-item">
                        <a class="sidebar-link" href="pages-sign-in.html">
                            <span class="align-middle">⚠️ En maintenance</span>
                        </a>
                    </li>
                    <li class="sidebar-item">
                        <a class="sidebar-link" href="pages-sign-up.html">
                            <span class="align-middle">⚠️ En maintenance</span>
                        </a>
                    </li>
                    <li class="sidebar-item">
                        <a class="sidebar-link" href="pages-blank.html">
                            <span class="align-middle">⚠️ En maintenance</span>
                        </a>
                    </li>
                </ul>
            </div>
        </nav>
        <div class="main">
            <!-- Sidebar -->
            <nav class="navbar navbar-expand navbar-light navbar-bg">
                <a class="sidebar-toggle js-sidebar-toggle">
                    <i class="hamburger align-self-center"></i>
                </a>
                <div class="navbar-collapse collapse">
                    <ul class="navbar-nav navbar-align">
                        <li class="nav-item dropdown">
                            <a class="nav-icon dropdown-toggle d-inline-block d-sm-none" href="#" data-bs-toggle="dropdown">
                                <i class="align-middle" data-feather="settings"></i>
                            </a>
                            <a class="nav-link dropdown-toggle d-none d-sm-inline-block" href="#" data-bs-toggle="dropdown">
                                <img src="img/avatars/Luffy.jpg" class="avatar img-fluid rounded me-1" alt="Admin" /> <span class="text-dark"><?php echo htmlspecialchars($username); ?></span>
                            </a>
                            <div class="dropdown-menu dropdown-menu-end">
                                <a class="dropdown-item" href="pages-profile.html"><i class="align-middle me-1" data-feather="user"></i> Profile</a>
                                <a class="dropdown-item" href="#"><i class="align-middle me-1" data-feather="pie-chart"></i> Analytics</a>
                                <div class="dropdown-divider"></div>
                                <a class="dropdown-item" href="index.html"><i class="align-middle me-1" data-feather="settings"></i> Settings & Privacy</a>
                                <a class="dropdown-item" href="#"><i class="align-middle me-1" data-feather="help-circle"></i> Help Center</a>
                                <div class="dropdown-divider"></div>
                                <form class="p-0" method="POST" action="../logout.php" id="logout-form">
                                    <button type="submit" class="btn text-danger">Déconnexion</button>
                                </form>
                            </div>
                        </li>
                    </ul>
                </div>
            </nav>