<?php
session_start();
include '../../config.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header('Location: ../../login.php');
    exit;
}

$catResult = $conn->query("SELECT * FROM categories ORDER BY name ASC");

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = trim($_POST['title'] ?? '');
    $author = trim($_POST['author'] ?? '');
    $category_id = intval($_POST['category_id'] ?? 0);
    $year = intval($_POST['year'] ?? 0);

    if (!$title || !$author || !$category_id || $year < 1000 || $year > date('Y')) {
        $error = "Semua field wajib diisi dengan benar.";
    } else {
        $cover_name = null;
        if (!empty($_FILES['cover']['name'])) {
            $allowed_types = ['image/jpeg', 'image/png', 'image/gif'];
            if (!in_array($_FILES['cover']['type'], $allowed_types)) {
                $error = "Format cover hanya JPG, PNG, atau GIF.";
            } elseif ($_FILES['cover']['size'] > 2 * 1024 * 1024) {
                $error = "Ukuran file cover maksimal 2MB.";
            } else {
                $ext = pathinfo($_FILES['cover']['name'], PATHINFO_EXTENSION);
                $cover_name = uniqid('cover_') . '.' . $ext;
                $upload_path = __DIR__ . "/../../uploads/" . $cover_name;
                if (!move_uploaded_file($_FILES['cover']['tmp_name'], $upload_path)) {
                    $error = "Gagal mengunggah file cover.";
                }
            }
        }

        if (!$error) {
            $synopsis = trim($_POST['synopsis'] ?? '');
            $stmt = $conn->prepare("INSERT INTO books (title, author, category_id, year, cover, synopsis) VALUES (?, ?, ?, ?, ?, ?)");
            $stmt->bind_param("ssisss", $title, $author, $category_id, $year, $cover_name, $synopsis);

            if ($stmt->execute()) {
                header('Location: index.php');
                exit;
            } else {
                $error = "Gagal menyimpan data buku.";
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Tambah Buku</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
    <style>
        body {
            background-color: #fff0f5;
        }
        h3.text-primary {
            color: #e83e8c !important;
        }
        .btn-pink {
            background-color: #e83e8c;
            color: white;
        }
        .btn-pink:hover {
            background-color: #d63384;
        }
        .alert-danger {
            background-color: #fce4ec;
            color: #c2185b;
            border: 1px solid #f8bbd0;
        }
    </style>
</head>
<body>
<div class="container mt-4" style="max-width: 600px;">
    <h3 class="mb-3 text-primary">Tambah Buku Baru</h3>

    <?php if ($error): ?>
        <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <form method="post" action="" enctype="multipart/form-data" novalidate>
        <div class="mb-3">
            <label>Judul</label>
            <input type="text" name="title" class="form-control" required value="<?= htmlspecialchars($_POST['title'] ?? '') ?>">
        </div>
        <div class="mb-3">
            <label>Penulis</label>
            <input type="text" name="author" class="form-control" required value="<?= htmlspecialchars($_POST['author'] ?? '') ?>">
        </div>
        <div class="mb-3">
            <label>Kategori</label>
            <select name="category_id" class="form-select" required>
                <option value="">-- Pilih Kategori --</option>
                <?php while ($cat = $catResult->fetch_assoc()): ?>
                    <option value="<?= $cat['id'] ?>" <?= (isset($_POST['category_id']) && $_POST['category_id'] == $cat['id']) ? 'selected' : '' ?>>
                        <?= htmlspecialchars($cat['name']) ?>
                    </option>
                <?php endwhile; ?>
            </select>
        </div>
        <div class="mb-3">
            <label>Tahun Terbit</label>
            <input type="number" name="year" class="form-control" required min="1000" max="<?= date('Y') ?>" value="<?= htmlspecialchars($_POST['year'] ?? '') ?>">
        </div>
        <div class="mb-3">
            <label>Cover (JPG/PNG/GIF max 2MB)</label>
            <input type="file" name="cover" class="form-control" accept="image/jpeg,image/png,image/gif">
        </div>
        <div class="mb-3">
            <label>Sinopsis</label>
            <textarea name="synopsis" class="form-control" rows="4"><?= htmlspecialchars($_POST['synopsis'] ?? '') ?></textarea>
        </div>

        <button class="btn btn-pink" type="submit">Simpan</button>
        <a href="index.php" class="btn btn-secondary">Batal</a>
    </form>
</div>
</body>
</html>