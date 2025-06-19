<?php
session_start();
include 'config.php';

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'user') {
    header('Location: login.php');
    exit;
}

$sql = "SELECT books.*, categories.name AS category_name 
        FROM books 
        LEFT JOIN categories ON books.category_id = categories.id 
        ORDER BY books.id DESC 
        LIMIT 10";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>User Dashboard - Bookwise</title>
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

    .navbar {
        background-color: #ff69b4 !important;
    }

    .navbar .nav-link,
    .navbar .navbar-brand {
        color: white !important;
    }

    .card {
        border: 1px solid #ffc0cb;
        transition: transform 0.2s;
    }

    .card:hover {
        transform: scale(1.03);
        box-shadow: 0 4px 8px rgba(255, 105, 180, 0.3);
    }

    .book-cover {
        height: 80px;
        object-fit: cover;
    }

    h1,
    h6,
    p {
        color: #cc3366;
    }
  </style>
</head>

<body>
  <nav class="navbar navbar-expand-lg navbar-dark">
    <div class="container">
      <a class="navbar-brand" href="user_dashboard.php">Bookwise Dashboard</a>

      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
        aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>

      <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav ms-auto">
          <li class="nav-item"><a href="daftar_buku.php" class="nav-link">Daftar Buku</a></li>
          <li class="nav-item"><a href="kategori_buku.php" class="nav-link">Kategori Buku</a></li>
          <li class="nav-item"><a href="logout.php" class="nav-link">Keluar</a></li>
        </ul>
      </div>
    </div>
  </nav>
  
  <main>
    <div class="container mt-5 mb-5">
        <div class="row row-cols-2 row-cols-sm-3 row-cols-md-4 row-cols-lg-5 g-4">
            <?php while ($row = $result->fetch_assoc()): ?>
                <div class="col">
                    <a href="detail_buku.php?id=<?= $row['id'] ?>" class="text-decoration-none text-dark">
                        <div class="card h-100 shadow-sm" style="width: 100%;">
                            <?php if ($row['cover'] && file_exists("uploads/" . $row['cover'])): ?>
                                <img src="uploads/<?= htmlspecialchars($row['cover']) ?>" 
                                     class="card-img-top" 
                                     alt="Cover <?= htmlspecialchars($row['title']) ?>" 
                                     style="height: 280px; object-fit: cover; object-position: top; width: 100%; padding: 4px;">
                            <?php else: ?>
                                <div class="bg-secondary text-white d-flex align-items-center justify-content-center" 
                                     style="height: 280px; font-size: 14px; padding: 4px;">
                                     Tidak ada cover
                                </div>
                            <?php endif; ?>
                            <div class="card-body p-2">
                                <h6 class="card-title text-center mb-0" style="font-size: 14px;">
                                    <?= htmlspecialchars($row['title']) ?>
                                </h6>
                            </div>
                        </div>
                    </a>
                </div>
            <?php endwhile; ?>
        </div>
    </div>
  </main>
  
  <footer>
    &copy; 2025 Bookwise
  </footer>

</body>
</html>