<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Voting - APP</title>
    <link rel="stylesheet" href="assets/style.css">
</head>
<body>
    <?php
        include 'layouts/header.php';
    ?>

    <div class="container">
        <h2>Selamat Datang</h2>

        <p>
        Aplikasi ini memungkinkan pengguna untuk membuat polling,
        melakukan voting, dan melihat hasil secara real-time setelah polling berakhir.
        </p>

        <a href="polling/index.php">
            <button>Lihat Polling</button>
        </a>
    </div>

    <?php include 'layouts/footer.php'; ?>
</body>
</html>