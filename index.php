<?php 
// Memulai session PHP untuk manajemen login/user
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistem Voting Online</title>
    <!-- Menghubungkan file css -->
    <link rel="stylesheet" href="assets/style.css">
</head>
<body>
    <?php 
    // Memuat header dari file terpisah
    include 'layouts/header.php'; 
    ?>

    <!-- Main content -->
    <main>
        <div class="container">
            <h2>Selamat Datang</h2>

            <p> 
                Aplikasi ini memungkinkan pengguna untuk membuat polling, melakukan voting, dan melihat hasil secara real-time setelah polling berakhir.
            </p>

            <!-- Tombol menuju halaman polling -->
            <a href="polling/index.php">
                <button>Lihat Polling</button>
            </a>
        </div>
    </main>

    <?php 
    // Memuat footer dari file terpisah
    include 'layouts/footer.php'; 
    ?>
</body>
</html>