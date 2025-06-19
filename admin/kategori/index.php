<?php
session_start();
include '../../config.php';

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header('Location: ../../login.php');
    exit;
}

$search = $_GET['search'] ?? '';

if ($search) {
    $stmt = $conn->prepare("SELECT * FROM categories WHERE name LIKE ? ORDER BY name ASC");
    $like_search = "%$search%";
    $stmt->bind_param("s", $like_search);
} else {
    $stmt = $conn->prepare("SELECT * FROM categories ORDER BY name ASC");
}

$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Kategori Buku</title>
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

        .container {
            flex: 1;
        }
        .text-pink {
            color: #d63384 !important;
        }
        .btn-pink {
            background-color: #d63384;
            color: white;
        }
        .btn-pink:hover {
            background-color: #bd2c74;
            color: white;
        }
        .btn-outline-pink {
            border: 1px solid #d63384;
            color: #d63384;
        }
        .btn-outline-pink:hover {
            background-color: #d63384;
            color: white;
        }
    </style>
</head>
<body>
    <main>
        <div class="container mt-4">
            <h3 class="mb-3 text-pink">Daftar Kategori Buku</h3>
            <a href="tambah.php" class="btn btn-pink mb-3">Tambah Kategori</a>

            <form method="get" class="mb-3" action="">
                <div class="input-group">
                    <input type="text" name="search" class="form-control" placeholder="Cari kategori..." value="<?= htmlspecialchars($search) ?>">
                    <button class="btn btn-outline-pink" type="submit">Cari</button>
                </div>
            </form>

            <table class="table table-striped align-middle">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nama Kategori</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $no = 1; ?>
                    <?php while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?= $no++ ?></td>
                        <td><?= htmlspecialchars($row['name']) ?></td>
                        <td>
                            <a href="edit.php?id=<?= $row['id'] ?>" class="btn btn-outline-pink btn-sm">Edit</a>
                            <a href="hapus.php?id=<?= $row['id'] ?>" class="btn btn-outline-danger btn-sm" onclick="return confirm('Yakin hapus kategori ini?');">Hapus</a>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>

            <a href="../admin_dashboard.php" class="btn btn-pink" style="margin-bottom: 80px;">Kembali ke Dashboard</a>
        </div>
    </main>

<footer>
    &copy; 2025 Bookwise
</footer>
</body>
</html>
