<?php
session_start();
include '../../config.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: ../../login.php');
    exit;
}

$id = $_GET['id'] ?? null;

if ($id) {
    // Hapus file cover jika ada
    $stmt = $conn->prepare("SELECT cover FROM books WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $book = $result->fetch_assoc();

    if ($book && $book['cover'] && file_exists("../uploads/".$book['cover'])) {
        unlink("../uploads/".$book['cover']);
    }

    // Hapus data buku
    $stmt = $conn->prepare("DELETE FROM books WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
}

header('Location: index.php');
exit;
?>
