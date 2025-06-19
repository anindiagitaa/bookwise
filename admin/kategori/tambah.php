<?php
session_start();
include '../../config.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: ../../login.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');

    if (!$name) {
        $error = "Nama kategori harus diisi.";
    } else {
        $stmt = $conn->prepare("INSERT INTO categories (name) VALUES (?)");
        $stmt->bind_param("s", $name);
        if ($stmt->execute()) {
            header('Location: index.php');
            exit;
        } else {
            $error = "Gagal menambah kategori.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1" />
<title>Tambah Kategori</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<style>
    .btn-pink {
        background-color: #ff69b4;
        color: white;
        border: none;
    }
    .btn-pink:hover {
        background-color: #ff85c1;
    }
    .btn-softpink {
        background-color: #ffc0cb;
        color: #333;
        border: none;
    }
    .btn-softpink:hover {
        background-color: #ffb6c1;
    }
    .text-pink {
        color: #d63384;
    }
</style>
</head>
<body>
<div class="container mt-4" style="max-width: 500px;">
    <h3 class="mb-3 text-pink">Tambah Kategori Baru</h3>
    <?php if(isset($error)): ?>
        <div class="alert alert-danger"><?=htmlspecialchars($error)?></div>
    <?php endif; ?>
    <form method="post" action="">
        <div class="mb-3">
            <label>Nama Kategori</label>
            <input type="text" name="name" class="form-control" required autofocus>
        </div>
        <button class="btn btn-pink" type="submit">Simpan</button>
        <a href="index.php" class="btn btn-softpink">Batal</a>
    </form>
</div>
</body>
</html>
