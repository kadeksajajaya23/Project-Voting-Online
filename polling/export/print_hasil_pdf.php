<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: ../../auth/login.php");
    exit;
}
require_once '../../config/Database.php';

$polling_id = $_GET['id'] ?? null;
if (!$polling_id) die("Polling tidak ditemukan.");

$database = new Database();
$conn = $database->getConnection();

// Ambil data polling
$stmt = $conn->prepare("SELECT * FROM pollings WHERE id = ?");
$stmt->execute([$polling_id]);
$poll = $stmt->fetch(PDO::FETCH_ASSOC);
if (!$poll) die("Polling tidak ditemukan.");

// Ambil hasil voting
$stmt = $conn->prepare("
    SELECT o.nama_opsi, COUNT(v.id) AS total_vote
    FROM options o
    LEFT JOIN votes v ON o.id = v.option_id
    WHERE o.polling_id = ?
    GROUP BY o.id
");
$stmt->execute([$polling_id]);
$options = $stmt->fetchAll(PDO::FETCH_ASSOC);
$totalVotes = array_sum(array_column($options, 'total_vote'));
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title><?= htmlspecialchars($poll['judul']) ?> - Hasil</title>
    <link rel="stylesheet" href="../../assets/style.css">
</head>
<body>
    <div class="print-pdf">
        <h1><?= htmlspecialchars($poll['judul']) ?></h1>
        <p><strong>Deskripsi:</strong> <?= htmlspecialchars($poll['deskripsi']) ?></p>

        <h2>Hasil Voting</h2>
        <?php foreach ($options as $opt):
            $percent = ($totalVotes > 0) ? round(($opt['total_vote'] / $totalVotes) * 100, 1) : 0;
        ?>
            <div class="result">
                <?= htmlspecialchars($opt['nama_opsi']) ?>: 
                <?= $opt['total_vote'] ?> suara (<?= $percent ?>%)
            </div>
        <?php endforeach; ?>

        <div class="footer">
            Dicetak pada: <?= date('d M Y H:i') ?><br>
            Sistem Voting Online
        </div>

        <div class="no-print" style="text-align:center; margin-top:30px;">
            <button onclick="window.print()">🖨️ Cetak / Simpan sebagai PDF</button>
            <br><br>
            <a href="../hasil.php">← Kembali ke Hasil</a>
        </div>
    </div>
</body>
</html>