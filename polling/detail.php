<?php
session_start();
require_once '../config/Database.php';

// Set timezone agar waktu konsisten
date_default_timezone_set('Asia/Makassar');

/* =========================
   VALIDASI ID
========================= */
if (!isset($_GET['id'])) {
    die('Polling tidak ditemukan');
}
$polling_id = (int) $_GET['id'];

/* =========================
   KONEKSI DATABASE
========================= */
$database = new Database();
$conn = $database->getConnection();

/* =========================
   AMBIL DATA POLLING
========================= */
$stmt = $conn->prepare("SELECT * FROM pollings WHERE id = ?");
$stmt->execute([$polling_id]);
$polling = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$polling) {
    die('Polling tidak ditemukan' . $polling_id);
}

/* =========================
   CEK STATUS POLLING BERDASARKAN WAKTU
========================= */
$now = time();
$endTimestamp = strtotime($polling['end_date']);
$isClosed = ($endTimestamp < $now);
$isActive = !$isClosed;

/* =========================
   PROSES VOTE (TANPA REDIRECT)
========================= */
$voted = false;
$votedOptionId = null;

if (isset($_POST['vote'])) {

    if ($isClosed) {
        $errorVote = 'Polling sudah ditutup';
    } else if (!isset($_SESSION['user_id'])) {
        header("Location: ../auth/login.php");
        exit;
    } else if (!isset($_POST['option_id'])) {
        $errorVote = 'Opsi tidak valid';
    } else {
        $user_id   = $_SESSION['user_id'];
        $option_id = (int) $_POST['option_id'];

        // Cek apakah sudah vote
        $cek = $conn->prepare("
            SELECT id FROM votes
            WHERE user_id = ? AND polling_id = ?
        ");
        $cek->execute([$user_id, $polling_id]);

        if ($cek->rowCount() > 0) {
            $errorVote = 'Anda sudah melakukan voting';
        } else {
            // Simpan vote
            $stmtVote = $conn->prepare("
                INSERT INTO votes (user_id, polling_id, option_id)
                VALUES (?, ?, ?)
            ");
            $stmtVote->execute([$user_id, $polling_id, $option_id]);

            // Tandai bahwa user sudah vote
            $voted = true;
            $votedOptionId = $option_id;
        }
    }
}

/* =========================
   PROSES KOMENTAR
========================= */
$errorKomentar = '';
$successKomentar = '';

if (isset($_POST['komentar'])) {
    if (!isset($_SESSION['user_id'])) {
        $errorKomentar = "Silakan login untuk berkomentar.";
    } else {
        $isi = trim($_POST['isi']);
        if ($isi !== '') {
            $stmtKomen = $conn->prepare("
                INSERT INTO comments (polling_id, user_id, isi, status)
                VALUES (?, ?, ?, 'approved')
            ");
            $stmtKomen->execute([
                $polling_id,
                $_SESSION['user_id'],
                $isi
            ]);
            $successKomentar = "Komentar berhasil dikirim dan menunggu persetujuan.";
        }
    }
}

/* =========================
   AMBIL OPSI
========================= */
$stmtOpt = $conn->prepare("SELECT * FROM options WHERE polling_id = ?");
$stmtOpt->execute([$polling_id]);
$options = $stmtOpt->fetchAll(PDO::FETCH_ASSOC);

/* =========================
   AMBIL HASIL VOTING UNTUK DITAMPILKAN
========================= */
$userResult = [];
$totalVotes = 0;

if (isset($_SESSION['user_id'])) {
    // Ambil hasil voting untuk ditampilkan (untuk semua user yang sudah vote)
    $stmtHasil = $conn->prepare("
        SELECT o.nama_opsi, COUNT(v.id) AS total_vote
        FROM options o
        LEFT JOIN votes v ON o.id = v.option_id
        WHERE o.polling_id = ?
        GROUP BY o.id
    ");
    $stmtHasil->execute([$polling_id]);
    $userResult = $stmtHasil->fetchAll(PDO::FETCH_ASSOC);
    $totalVotes = array_sum(array_column($userResult, 'total_vote'));
}

// Cek apakah user sudah vote (untuk menentukan tampilkan form atau hasil)
$hasVoted = false;
if (isset($_SESSION['user_id'])) {
    $stmtCheck = $conn->prepare("SELECT id FROM votes WHERE user_id = ? AND polling_id = ?");
    $stmtCheck->execute([$_SESSION['user_id'], $polling_id]);
    $hasVoted = $stmtCheck->rowCount() > 0;
}

require_once '../layouts/header.php';
?>

<div class="container">
    <div class="card">

        <h2><?= htmlspecialchars($polling['judul']) ?></h2>
        <p><?= htmlspecialchars($polling['deskripsi']) ?></p>

        <!-- STATUS POLLING -->
        <?php if ($isActive): ?>
            <div style="background: #d4edda; padding: 10px; border-radius: 6px; margin-bottom: 15px;">
                <strong>Polling sedang berlangsung</strong><br>
                Berakhir: 
                <span class="local-time" data-timestamp="<?= $endTimestamp ?>"></span>
            </div>
        <?php else: ?>
            <p style="color:red;"><b>Polling sudah ditutup</b></p>
        <?php endif; ?>

        <!-- FORM VOTE ATAU HASIL VOTING -->
        <?php if ($isActive): ?>
            <?php if ($voted || $hasVoted): ?>
                <!-- TAMPILKAN HASIL VOTING -->
                <h3>Hasil Voting</h3>
                <p style="font-style: italic; color: #666;">Hasil ini bersifat sementara (hanya terlihat oleh Anda).</p>

                <?php foreach ($userResult as $opt):
                    $percent = ($totalVotes > 0) ? round(($opt['total_vote'] / $totalVotes) * 100, 1) : 0;
                    $barColor = ($opt['total_vote'] > 0 && $opt['id'] == $votedOptionId) ? '#4caf50' : '#e0e0e0';
                ?>
                    <div class="result-row">
                        <strong><?= htmlspecialchars($opt['nama_opsi']) ?></strong> — 
                        <?= $opt['total_vote'] ?> suara (<?= $percent ?>%)
                    </div>
                    <div class="progress">
                        <div class="progress-bar" style="width: <?= $percent ?>%; background-color: <?= $barColor ?>;"></div>
                    </div>
                    <br>
                <?php endforeach; ?>
            <?php else: ?>
                <!-- TAMPILKAN FORM VOTE -->
                <form method="post">
                    <?php foreach ($options as $opt): ?>
                        <div class="option-row">
                            <input type="radio" name="option_id" value="<?= $opt['id'] ?>" required>
                            <?= htmlspecialchars($opt['nama_opsi']) ?>
                        </div>
                    <?php endforeach; ?>
                    <button type="submit" name="vote" class="btn-vote">Vote</button>
                </form>

                <!-- TAMPILKAN PESAN ERROR JIKA ADA -->
                <?php if (isset($errorVote)): ?>
                    <p style="color:red; margin-top: 10px;"><?= $errorVote ?></p>
                <?php endif; ?>
            <?php endif; ?>
        <?php endif; ?>

        <hr>

        <!-- KOMENTAR -->
        <h4>Komentar</h4>

        <?php if (!empty($errorKomentar)): ?>
            <p style="color:red"><?= $errorKomentar ?></p>
        <?php endif; ?>

        <?php if (!empty($successKomentar)): ?>
            <p style="color:green"><?= $successKomentar ?></p>
        <?php endif; ?>

        <form method="post">
            <textarea name="isi" required></textarea><br>
            <button type="submit" name="komentar">Kirim Komentar</button>
        </form>

    </div>
</div>

<?php require_once '../layouts/footer.php'; ?>