<?php
session_start();
include 'config.php';

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'user') {
    header('Location: login.php');
    exit;
}

$id = $_GET['id'] ?? null;

if (!$id) {
    echo "ID buku tidak ditemukan.";
    exit;
}

$stmt = $conn->prepare("SELECT books.*, categories.name AS category_name FROM books LEFT JOIN categories ON books.category_id = categories.id WHERE books.id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();

if (!$row) {
    echo "Buku tidak ditemukan.";
    exit;
}

$sql = "SELECT books.*, categories.name AS category_name 
        FROM books 
        LEFT JOIN categories ON books.category_id = categories.id 
        WHERE books.id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    echo "Buku tidak ditemukan.";
    exit;
}

$book = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Detail Buku</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
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

        .text-pink {
            color: #d63384 !important;
        }

        .btn-pink {
            background-color: #d63384;
            border: none;
            color: white;
        }

        .btn-pink:hover {
            background-color: #c2186a;
        }

        .card {
            border-color: #f7d6e0;
        }

        .card-body {
            background-color: #fff0f6;
        }
    </style>
</head>
<body>
    <main>
        <div class="container mt-4">
            <h3 class="text-pink mb-4">Detail Buku</h3>

            <div class="card shadow">
                <div class="row g-0">
                    <div class="col-md-3">
                        <?php if ($book['cover'] && file_exists("uploads/" . $book['cover'])): ?>
                            <img src="uploads/<?= htmlspecialchars($book['cover']) ?>" alt="Cover" class="img-fluid rounded-start">
                        <?php else: ?>
                            <div class="text-center p-3">Tidak ada cover</div>
                        <?php endif; ?>
                    </div>
                    <div class="col-md-9">
                        <div class="card-body">
                            <h5 class="card-title text-pink"><?= htmlspecialchars($book['title']) ?></h5>
                            <p class="card-text"><strong>Penulis:</strong> <?= htmlspecialchars($book['author']) ?></p>
                            <p class="card-text"><strong>Kategori:</strong> <?= htmlspecialchars($book['category_name']) ?></p>
                            <p class="card-text"><strong>Tahun Terbit:</strong> <?= htmlspecialchars($book['year']) ?></p>
                            <p><strong>Sinopsis:</strong><br><?= nl2br(htmlspecialchars($row['synopsis'])) ?></p>
                        </div>
                    </div>
                </div>
            </div>

            <a href="daftar_buku.php" class="btn btn-pink mt-3" style="margin-bottom: 80px;">Kembali ke Daftar Buku</a>
            <a href="user_dashboard.php" class="btn btn-pink mt-3" style="margin-bottom: 80px;">Kembali ke Dashboard</a>
        </div>
    </main>
</body>

<footer>
    &copy; 2025 Bookwise
</footer>

</html>