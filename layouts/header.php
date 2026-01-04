<?php session_start(); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Sistem Voting Online</title>
    <link rel="stylesheet" href="../assets/style.css">
</head>
<body>

<header>
    <div class="container">
        <h1>Sistem Voting Online</h1>
        <nav>
            <a href="/voting-app/index.php">Home</a>
            <a href="/voting-app/polling/index.php">Polling</a>
            <?php if (isset($_SESSION['user_id'])) { ?>
                <a href="/voting-app/auth/logout.php">Logout</a>
            <?php } else { ?>
                <a href="/voting-app/auth/login.php">Login</a>
            <?php } ?>
        </nav>
    </div>
</header>
</body>
</html>
