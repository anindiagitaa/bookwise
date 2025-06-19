<?php
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header('Location: ../login.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Admin Dashboard - Bookwise</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        html, body {
            height: 100%;
            margin: 0;
            padding: 0;
        }

        body {
            display: flex;
            flex-direction: column;
            background-color: #fff0f5;
            font-family: 'Segoe UI', sans-serif;
        }

        main {
            flex: 1;
        }

        footer {
            background-color: #ff69b4;
            color: white;
            text-align: center;
            padding: 12px 0;
        }

        .navbar {
            background-color: #ff69b4 !important;
        }

        .navbar .nav-link,
        .navbar .navbar-brand {
            color: white !important;
        }
    </style>
</head>

<body>
    <nav class="navbar navbar-expand-lg navbar-dark">
        <div class="container">
            <a class="navbar-brand" href="admin_dashboard.php">Admin Dashboard</a>
            <div class="collapse navbar-collapse">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item"><a href="kategori/index.php" class="nav-link">Kelola Kategori</a></li>
                    <li class="nav-item"><a href="buku/index.php" class="nav-link">Kelola Buku</a></li>
                    <li class="nav-item"><a href="../logout.php" class="nav-link">Logout</a></li>
                </ul>
            </div>
        </div>
    </nav>

    <main>
        <div class="container mt-5">
            <h1 class="text-pink fw-bold">Selamat Datang, Admin!</h1>
            <p class="mb-4">Anda dapat mengelola data buku, kategori, serta melihat jumlah pengguna di sistem Bookwise.</p>

            <div class="row g-4">
                <div class="col-md-4">
                    <div class="card border-0 shadow-sm">
                        <div class="card-body">
                            <h5 class="card-title text-pink">Total Buku</h5>
                            <p class="card-text fs-4 fw-semibold">
                                <?php
                                include '../config.php';
                                $result = $conn->query("SELECT COUNT(*) AS total FROM books");
                                echo $result->fetch_assoc()['total'];
                                ?>
                            </p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card border-0 shadow-sm">
                        <div class="card-body">
                            <h5 class="card-title text-pink">Total Kategori</h5>
                            <p class="card-text fs-4 fw-semibold">
                                <?php
                                $result = $conn->query("SELECT COUNT(*) AS total FROM categories");
                                echo $result->fetch_assoc()['total'];
                                ?>
                            </p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card border-0 shadow-sm">
                        <div class="card-body">
                            <h5 class="card-title text-pink"> Jumlah User</h5>
                            <p class="card-text fs-4 fw-semibold">
                                <?php
                                $result = $conn->query("SELECT COUNT(*) AS total FROM users WHERE role = 'user'");
                                echo $result->fetch_assoc()['total'];
                                ?>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <footer>
        &copy; 2025 Bookwise
    </footer>
</body>
</html>