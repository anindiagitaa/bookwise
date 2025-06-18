<?php
session_start();
include 'config.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = trim($_POST['username']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    if (!$username || !$password || !$confirm_password) {
        $error = "Semua field harus diisi.";
    } elseif ($password !== $confirm_password) {
        $error = "Password dan konfirmasi tidak sama.";
    } else {
        $stmt = $conn->prepare("SELECT id FROM users WHERE username = ?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $stmt->store_result();
        if ($stmt->num_rows > 0) {
            $error = "Username sudah digunakan.";
        } else {
            $password_hash = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $conn->prepare("INSERT INTO users (username, password, role) VALUES (?, ?, 'user')");
            $stmt->bind_param("ss", $username, $password_hash);
            if ($stmt->execute()) {
                $_SESSION['success'] = "Registrasi berhasil, silakan login.";
                header('Location: login.php');
                exit;
            } else {
                $error = "Gagal mendaftar, coba lagi.";
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
    <title>Daftar - Bookwise</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
</head>
<body class="bg-primary-subtle">
    <div class="container d-flex justify-content-center align-items-center" style="min-height: 100vh;">
        <div class="card shadow p-4" style="width: 100%; max-width: 400px;">
            <h4 class="text-center text-primary mb-3">Registrasi Akun</h4>

            <form id="registerForm">
                <div class="mb-3">
                    <label class="form-label">Username</label>
                    <input type="text" name="username" class="form-control" required autofocus />
                </div>
                <div class="mb-3">
                    <label class="form-label">Password</label>
                    <input type="password" name="password" class="form-control" required />
                </div>
                <div class="mb-3">
                    <label class="form-label">Konfirmasi Password</label>
                    <input type="password" name="confirm_password" class="form-control" required />
                </div>
                <button type="submit" class="btn btn-primary w-100">Daftar</button>
            </form>
            <p class="mt-3 text-center">
                Sudah punya akun? <a href="login.html">Login di sini</a>
            </p>
        </div>
    </div>

    <script>
      document.getElementById('registerForm').addEventListener('submit', function(event) {
        event.preventDefault(); 
        const password = this.password.value;
        const confirmPassword = this.confirm_password.value;

        if (password !== confirmPassword) {
          alert('Password dan Konfirmasi Password tidak sama.');
          return;
        }

        alert('Registrasi berhasil! Silakan login.');
        window.location.href = 'login.html';
      });
    </script>
</body>
</html>
