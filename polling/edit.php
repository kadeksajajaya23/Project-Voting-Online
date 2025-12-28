<?php
include '../layouts/header.php';
include '../config/Database.php';
include '../classes/Polling.php';

$db = (new Database())->getConnection();
$polling = new Polling($db);
$data = $polling->find($_GET['id']);

if ($data['user_id'] != $_SESSION['user_id']) {
    die("Akses ditolak");
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $stmt = $db->prepare(
        "UPDATE pollings SET judul=?, deskripsi=?, end_date=? WHERE id=?"
    );
    $stmt->execute([
        $_POST['judul'],
        $_POST['deskripsi'],
        $_POST['end_date'],
        $_GET['id']
    ]);

    header("Location: index.php");
}
?>

<div class="container">
<h2>Edit Polling</h2>

<form method="post">
    Judul
    <input type="text" name="judul" value="<?= $data['judul']; ?>">

    Deskripsi
    <textarea name="deskripsi"><?= $data['deskripsi']; ?></textarea>

    Berakhir
    <input type="datetime-local" name="end_date"
           value="<?= date('Y-m-d\TH:i', strtotime($data['end_date'])); ?>">

    <button>Update</button>
</form>
</div>

<?php include '../layouts/footer.php'; ?>
