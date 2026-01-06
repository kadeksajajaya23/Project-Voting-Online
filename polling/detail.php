<?php
session_start();
require_once '../config/Database.php';

date_default_timezone_set('Asia/Jakarta');

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
    die('Polling tidak ditemukan');
}

/* =========================
   CEK STATUS POLLING (BERDASARKAN WAKTU)
========================= */
$now = date('Y-m-d H:i:s');

$isActive = true;

if (!empty($polling['end_date']) && $now > $polling['end_date']) {
    $isActive = false;
}

/* =========================
   PROSES VOTE
========================= */
if (isset($_POST['vote'])) {

    if (!$isActive) {
        die('Polling sudah ditutup');
    }

    if (!isset($_SESSION['user_id'])) {
        header("Location: ../auth/login.php");
        exit;
    }

    if (!isset($_POST['option_id'])) {
        die('Opsi tidak valid');
    }

    $user_id   = $_SESSION['user_id'];
    $option_id = (int) $_POST['option_id'];

    // Cek apakah sudah vote
    $cek = $conn->prepare("
        SELECT id FROM votes
        WHERE user_id = ? AND polling_id = ?
    ");
    $cek->execute([$user_id, $polling_id]);

    if ($cek->rowCount() > 0) {
        die('Anda sudah melakukan voting');
    }

    // Simpan vote
    $stmtVote = $conn->prepare("
        INSERT INTO votes (user_id, polling_id, option_id)
        VALUES (?, ?, ?)
    ");
    $stmtVote->execute([$user_id, $polling_id, $option_id]);

    header("Location: hasil.php?id=" . $polling_id);
    exit;
}

/* =========================
   PROSES KOMENTAR
========================= */
if (isset($_POST['komentar'])) {

    if (!isset($_SESSION['user_id'])) {
        $errorKomentar = "Silakan login untuk berkomentar.";
    } else {
        $isi = trim($_POST['isi']);

        if ($isi !== '') {
            $stmtKomen = $conn->prepare("
                INSERT INTO comments (polling_id, user_id, isi, status)
                VALUES (?, ?, ?, 'pending')
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

require_once '../layouts/header.php';
?>

<div class="container">
    <div class="card">

        <h2><?= htmlspecialchars($polling['judul']) ?></h2>
        <p><?= htmlspecialchars($polling['deskripsi']) ?></p>

        <!-- STATUS POLLING -->
        <?php if ($isActive): ?>
            <p style="color:green;"><b>Polling sedang berlangsung</b></p>
        <?php else: ?>
            <p style="color:red;"><b>Polling sudah ditutup</b></p>
        <?php endif; ?>

        <!-- FORM VOTE -->
        <?php if ($isActive): ?>
            <form method="post">
                <?php foreach ($options as $opt): ?>
                    <div class="option-row">
                        <input type="radio" name="option_id" value="<?= $opt['id'] ?>" required>
                        <?= htmlspecialchars($opt['nama_opsi']) ?>
                    </div>
                <?php endforeach; ?>
                <button type="submit" name="vote" class="btn-vote">Vote</button>
            </form>
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
