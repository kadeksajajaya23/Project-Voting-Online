<?php
session_start();
require_once '../config/Database.php';

if (!isset($_GET['id'])) {
    die('Polling tidak ditemukan');
}

$polling_id = (int) $_GET['id'];

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
   PROSES VOTE
========================= */
if (isset($_POST['vote'])) {

    if (!isset($_SESSION['user_id'])) {
        header("Location: ../auth/login.php");
        exit;
    }

    if ($polling['status'] !== 'Aktif') {
        die('Polling sudah ditutup');
    }

    if (!isset($_POST['option_id'])) {
        die('Opsi tidak valid');
    }

    $user_id   = $_SESSION['user_id'];
    $option_id = (int) $_POST['option_id'];

    // Cek sudah vote atau belum
    $cek = $conn->prepare("
        SELECT id FROM votes 
        WHERE user_id = ? AND polling_id = ?
    ");
    $cek->execute([$user_id, $polling_id]);

    if ($cek->rowCount() > 0) {
        die('Anda sudah melakukan voting');
    }

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

            $successKomentar = "Komentar menunggu persetujuan.";
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

        <?php if (isset($polling['status']) && $polling['status'] === 'Aktif'): ?>
        <form method="post">
            <?php foreach ($options as $opt): ?>
                <div>
                    <input type="radio"
                           name="option_id"
                           value="<?= $opt['id'] ?>"
                           required>
                    <?= htmlspecialchars($opt['nama_opsi']) ?>
                </div>
            <?php endforeach; ?>

            <button type="submit" name="vote">Vote</button>
        </form>
        <?php else: ?>
            <p><b>Polling sudah ditutup.</b></p>
        <?php endif; ?>

        <hr>

        <h4>Komentar</h4>

        <?php if (!empty($errorKomentar)): ?>
            <p style="color:red"><?= $errorKomentar ?></p>
        <?php endif; ?>

        <?php if (!empty($successKomentar)): ?>
            <p style="color:green"><?= $successKomentar ?></p>
        <?php endif; ?>

        <form method="post">
            <textarea name="isi" required></textarea>
            <button type="submit" name="komentar">Kirim Komentar</button>
        </form>

    </div>
</div>

<?php require_once '../layouts/footer.php'; ?>
