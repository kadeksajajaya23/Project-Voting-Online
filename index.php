<?php
include 'layouts/header.php';
?>

<div class="container">
<h2>
    <?php if (isset($_SESSION['username'])): ?>
        Selamat Datang, <strong><?= htmlspecialchars($_SESSION['username']) ?></strong>.
    <?php else: ?>
        Selamat Datang
    <?php endif; ?>
</h2>
<p>
Aplikasi ini memungkinkan pengguna untuk membuat polling,
melakukan voting, dan melihat hasil secara real-time setelah polling berakhir.
</p>

<a href="polling/index.php">
    <button>Lihat Polling</button>
</a>
</div>

<?php include 'layouts/footer.php'; ?>
