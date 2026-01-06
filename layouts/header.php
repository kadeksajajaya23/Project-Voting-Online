<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Sistem Voting Online</title>
    <link rel="stylesheet" href="../assets/style.css">
</head>
<body>

<header class="header">
    <div class="container">
        <h1>Sistem Voting Online</h1>
        <nav>
            <a href="../index.php">Home</a>
            <a href="../polling/index.php">Polling</a>
            <?php if (isset($_SESSION['user_id'])) { ?>
                <a href="../auth/logout.php">Logout</a>
            <?php } else { ?>
                <a href="../auth/login.php">Login</a>
            <?php } ?>
        </nav>
    </div>
</header>
