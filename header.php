<?php
session_start();

function loadHeader($title) {
    $isAdmin = isset($_SESSION["admin_authenticated"]);
    $isJudge = isset($_SESSION["judge_id"]);

    echo '<!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <title>' . htmlspecialchars($title) . '</title>
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
        <link rel="stylesheet" href="styles.css">
    </head>
    <body>
        <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
            <div class="container">
                <a class="navbar-brand" href="index.php">Home</a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarNav">
                    <ul class="navbar-nav ms-auto">';

                    // Judge Login or Portal
                    if ($isJudge) {
                        echo '<li class="nav-item"><a class="nav-link" href="judge.php">Judges Portal</a></li>';
                    } else {
                        echo '<li class="nav-item"><a class="nav-link" href="judge_login.php">Judges Login</a></li>';
                    }

                    // Admin Login or Panel
                    if ($isAdmin) {
                        echo '<li class="nav-item"><a class="nav-link" href="admin.php">Admin Panel</a></li>';
                    } else {
                        echo '<li class="nav-item"><a class="nav-link" href="admin.php">Admin Login</a></li>';
                    }

                    // Logout Option for Logged-in Users
                    if ($isAdmin || $isJudge) {
                        echo '<li class="nav-item"><a class="nav-link text-danger" href="logout.php">Logout</a></li>';
                    }

                    echo '</ul>
                </div>
            </div>
        </nav>';

    // Auto logout when user leaves page
    echo '<script>
        window.addEventListener("beforeunload", function () {
            fetch("logout.php");
        });
    </script>';
}
?>