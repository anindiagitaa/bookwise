<?php
session_start();
include 'config.php';

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'user') {
    header('Location: login.php');
    exit;
}

$search = $_GET['search'] ?? '';

$sql = "SELECT books.*, categories.name AS category_name 
        FROM books 
        LEFT JOIN categories ON books.category_id = categories.id";

if ($search) {
    $sql .= " WHERE books.title LIKE ? OR categories.name LIKE ?";
}

$sql .= " ORDER BY books.title ASC";

$stmt = $conn->prepare($sql);

if ($search) {
    $like_search = "%$search%";
    $stmt->bind_param("ss", $like_search, $like_search);
}

$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Daftar Buku</title>
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

        h3.text-pink {
            color: #d63384;
        }
        .btn-pink {
            background-color: #ff69b4;
            color: white;
        }
        .btn-pink:hover {
            background-color: #ff85c1;
        }
        .table thead {
            background-color: #ffc0cb;
        }
        a {
            color: #d63384;
        }
        a:hover {
            text-decoration: underline;
            color: #c2185b;
        }
    </style>
</head>
<body>
    <main>
        <div class="container mt-4">
            <h3 class="mb-3 text-pink">Daftar Buku</h3>

            <form method="get" class="mb-3" action="">
                <div class="input-group">
                    <input type="text" name="search" class="form-control" placeholder="Cari buku atau kategori..." value="<?= htmlspecialchars($search) ?>">
                    <button class="btn btn-pink" type="submit">Cari</button>
                </div>
            </form>

            <table class="table table-striped align-middle">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Judul</th>
                        <th>Penulis</th>
                        <th>Kategori</th>
                        <th>Tahun Terbit</th>
                        <th>Cover</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $no = 1; while($row = $result->fetch_assoc()): ?>
                        <tr>
                            <td><?= $no++ ?></td>
                            <td><a href="detail_buku.php?id=<?= $row['id'] ?>"><?= htmlspecialchars($row['title']) ?></a></td>
                            <td><?= htmlspecialchars($row['author']) ?></td>
                            <td><?= htmlspecialchars($row['category_name']) ?></td>
                            <td><?= htmlspecialchars($row['year']) ?></td>
                            <td>
                                <?php if ($row['cover'] && file_exists("uploads/" . $row['cover'])): ?>
                                    <img src="uploads/<?= htmlspecialchars($row['cover']) ?>" alt="Cover" style="height:60px;">
                                <?php else: ?>
                                    <span class="text-muted">Tidak ada</span>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>

            <a href="user_dashboard.php" class="btn btn-pink" style="margin-bottom: 80px;">Kembali ke Dashboard</a>
        </div>
    </main>
</body>

<footer>
    &copy; 2025 Bookwise
</footer>

</html>