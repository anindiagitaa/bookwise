<?php
session_start();
include '../../config.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header('Location: ../../login.php');
    exit;
}

$id = $_GET['id'] ?? null;
if (!$id) {
    echo "ID tidak valid.";
    exit;
}

$stmt = $conn->prepare("SELECT * FROM books WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$book = $result->fetch_assoc();

if (!$book) {
    echo "Buku tidak ditemukan.";
    exit;
}

$catResult = $conn->query("SELECT * FROM categories ORDER BY name ASC");

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = trim($_POST['title'] ?? '');
    $author = trim($_POST['author'] ?? '');
    $category_id = intval($_POST['category_id'] ?? 0);
    $year = intval($_POST['year'] ?? 0);
    $synopsis = trim($_POST['synopsis'] ?? '');
    $cover_name = $book['cover'];

    if (!empty($_FILES['cover']['name'])) {
        $allowed = ['image/jpeg', 'image/png', 'image/gif'];
        if (in_array($_FILES['cover']['type'], $allowed) && $_FILES['cover']['size'] <= 2 * 1024 * 1024) {
            $ext = pathinfo($_FILES['cover']['name'], PATHINFO_EXTENSION);
            $cover_name = uniqid('cover_') . '.' . $ext;
            $upload_path = __DIR__ . "/../../uploads/" . $cover_name;
            move_uploaded_file($_FILES['cover']['tmp_name'], $upload_path);
        } else {
            $error = "Cover tidak valid atau terlalu besar.";
        }
    }

    if (!$error) {
        $stmt = $conn->prepare("UPDATE books SET title=?, author=?, category_id=?, year=?, cover=?, synopsis=? WHERE id=?");
        $stmt->bind_param("ssisssi", $title, $author, $category_id, $year, $cover_name, $synopsis, $id);
        if ($stmt->execute()) {
            header("Location: index.php");
            exit;
        } else {
            $error = "Gagal mengupdate buku.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Edit Buku</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body {
            background-color: #fff0f5;
        }

        h3.text-primary {
            color: #e83e8c !important; /* pink */
        }

        .btn-pink {
            background-color: #e83e8c;
            color: white;
            border: none;
        }

        .btn-pink:hover {
            background-color: #d63384;
        }

        .btn-secondary {
            background-color: #f8c6d8;
            color: #6c757d;
            border: none;
        }

        .btn-secondary:hover {
            background-color: #f5b1cb;
        }
    </style>
</head>
<body>
<div class="container mt-4" style="max-width: 600px;">
    <h3 class="mb-3 text-primary">Edit Buku</h3>

    <?php if ($error): ?>
        <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <form method="post" enctype="multipart/form-data">
        <div class="mb-3">
            <label>Judul</label>
            <input type="text" name="title" class="form-control" value="<?= htmlspecialchars($book['title']) ?>" required>
        </div>
        <div class="mb-3">
            <label>Penulis</label>
            <input type="text" name="author" class="form-control" value="<?= htmlspecialchars($book['author']) ?>" required>
        </div>
        <div class="mb-3">
            <label>Kategori</label>
            <select name="category_id" class="form-select" required>
                <?php while ($cat = $catResult->fetch_assoc()): ?>
                    <option value="<?= $cat['id'] ?>" <?= $cat['id'] == $book['category_id'] ? 'selected' : '' ?>>
                        <?= htmlspecialchars($cat['name']) ?>
                    </option>
                <?php endwhile; ?>
            </select>
        </div>
        <div class="mb-3">
            <label>Tahun Terbit</label>
            <input type="number" name="year" class="form-control" value="<?= $book['year'] ?>" required>
        </div>
        <div class="mb-3">
            <label>Sinopsis</label>
            <textarea name="synopsis" class="form-control" rows="4"><?= htmlspecialchars($book['synopsis']) ?></textarea>
        </div>
        <div class="mb-3">
            <label>Cover Baru (Opsional)</label>
            <input type="file" name="cover" class="form-control">
            <?php if ($book['cover']): ?>
                <img src="../../uploads/<?= htmlspecialchars($book['cover']) ?>" style="height:80px;" class="mt-2">
            <?php endif; ?>
        </div>
        <button class="btn btn-pink" type="submit">Simpan Perubahan</button>
        <a href="index.php" class="btn btn-secondary">Batal</a>
    </form>
</div>
</body>
</html>