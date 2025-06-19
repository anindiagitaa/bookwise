<?php
session_start();
if (isset($_SESSION['role'])) {
    if ($_SESSION['role'] == 'admin') {
        header('Location: admin/admin_dashboard.php');
    } else {
        header('Location: user_dashboard.php');
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Bookwise - Tentang Kami</title>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet" />
  <style>
    body {
      margin: 0;
      font-family: 'Poppins', sans-serif;
      background: linear-gradient(135deg, #ffe4f0 0%, #fff0f5 100%);
      color: #333;
    }
    header {
      background: linear-gradient(90deg, #ff69b4 0%, #ff1493 100%);
      color: white;
      padding: 30px 20px;
      text-align: center;
      box-shadow: 0 4px 15px rgba(255, 20, 147, 0.4);
    }
    header h1 {
      font-weight: 700;
      font-size: 3rem;
      margin: 0 0 10px;
      letter-spacing: 2px;
      user-select: none;
    }
    header p {
      font-size: 1.2rem;
      margin: 0;
      font-style: italic;
      user-select: none;
    }
    nav {
      background-color: #ff6ec7;
      display: flex;
      justify-content: center;
      gap: 30px;
      padding: 15px 0;
      box-shadow: 0 2px 8px #ff1493;
    }
    nav a {
      color: white;
      text-decoration: none;
      font-weight: 600;
      font-size: 1.1rem;
      padding: 6px 12px;
      border-radius: 6px;
      transition: background-color 0.3s ease, transform 0.3s ease;
    }
    nav a:hover {
      background-color: #d63384;
      transform: scale(1.1);
      text-decoration: none;
    }
    main {
      max-width: 900px;
      margin: 40px auto;
      background-color: white;
      padding: 40px 50px;
      border-radius: 15px;
      box-shadow: 0 8px 30px rgba(0, 0, 0, 0.1);
      line-height: 1.7;
      transition: box-shadow 0.3s ease;
    }
    main:hover {
      box-shadow: 0 12px 40px rgba(0, 0, 0, 0.15);
    }
    footer {
      background-color: #ff6ec7;
      color: white;
      text-align: center;
      padding: 18px 20px;
      margin-top: 60px;
      font-weight: 600;
      font-size: 1rem;
      user-select: none;
      letter-spacing: 0.05em;
      box-shadow: 0 -2px 10px rgba(255, 20, 147, 0.4);
    }
  </style>
</head>

<body>
  <header>
    <h1>Bookwise</h1>
  </header>

  <nav>
    <a href="register.php">Daftar Akun</a>
    <a href="login.php">Masuk Akun</a>
  </nav>

  <main>
    <p>Selamat datang di <strong>Bookwise</strong>, platform rekomendasi buku yang didedikasikan untuk 
      membantu kamu menemukan buku terbaik sesuai minat dan kebutuhanmu. Kami percaya bahwa membaca adalah 
      salah satu cara terbaik untuk memperluas wawasan, mengembangkan diri, dan mendapatkan inspirasi.</p>
    <p>Di Bookwise, kami menyediakan daftar rekomendasi buku yang telah dipilih secara cermat dari berbagai 
      genre dan kategori. Setiap rekomendasi dilengkapi dengan deskripsi singkat yang membantu kamu memahami 
      isi dan manfaat buku tersebut sebelum memutuskan untuk membacanya.</p>
    <p>Kami terus berkomitmen untuk menghadirkan pengalaman terbaik bagi para pecinta buku dan membantu 
      membangun komunitas pembaca yang aktif dan inspiratif. Terima kasih telah memilih Bookwise sebagai teman membaca kamu!</p>
  </main>

  <footer>
    &copy; 2025 Bookwise
  </footer>

</body>
</html>
