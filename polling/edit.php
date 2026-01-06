<?php
session_start();
include '../layouts/header.php';
include '../config/Database.php';
include '../classes/Polling.php';
include '../classes/Notification.php';

/* CEK LOGIN */
if (!isset($_SESSION['user_id'])) {
    header("Location: ../auth/login.php");
    exit;
}

/* CEK ID */
if (!isset($_GET['id'])) {
    die("Polling tidak ditemukan");
}

$db = (new Database())->getConnection();
$polling = new Polling($db);
$data = $polling->find($_GET['id']);
$notif = new Notification($db);

if (!$data) {
    die("Polling tidak ditemukan");
}

/* CEK PEMILIK POLLING */
if ($data['user_id'] != $_SESSION['user_id']) {
    die("Akses ditolak");
}

/* PROSES UPDATE */
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $judul = $_POST['judul'];
    $deskripsi = $_POST['deskripsi'];
    $end_date = $_POST['end_date'];

    $stmt = $db->prepare(
        "UPDATE pollings SET judul=?, deskripsi=?, end_date=? WHERE id=?"
    );
$stmt->execute([
    $judul,
    $deskripsi,
    $end_date,
    $_GET['id']
]);

$notif->send(
    $_SESSION['user_id'],
    "Polling '{$judul}' berhasil diperbarui"
);

header("Location: index.php");
exit;
}
?>

<div class="container">
    <h2>Edit Polling</h2>

    <form method="post">
        <label>Judul</label>
        <input type="text" name="judul"
               value="<?= htmlspecialchars($data['judul']); ?>" required>

        <label>Deskripsi</label>
        <textarea name="deskripsi" required><?= htmlspecialchars($data['deskripsi']); ?></textarea>

        <label>Berakhir</label>
        <input type="datetime-local" name="end_date"
               value="<?= date('Y-m-d\TH:i', strtotime($data['end_date'])); ?>" required>

        <button class="btn-primary">Update</button>
    </form>
</div>

<?php include '../layouts/footer.php'; ?>
