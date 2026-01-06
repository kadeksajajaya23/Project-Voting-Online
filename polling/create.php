<?php
// Tambahkan pengecekan sesi
if (!isset($_SESSION['user_id'])) {
    header("Location: ../auth/login.php");
    exit;
}

include '../layouts/header.php';
include '../config/database.php';
include '../classes/polling.php';
include '../classes/option.php';

$db = (new Database())->getConnection();
$polling = new Polling($db);
$option = new Option($db);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $polling->create(
        $_SESSION['user_id'],
        $_POST['judul'],
        $_POST['deskripsi'],
        $_POST['start_date'],
        $_POST['end_date']
    );

    $pollingId = $db->lastInsertId();

    foreach ($_POST['opsi'] as $o) {
        // Sanitasi input opsi
        $o = trim($o);
        if (!empty($o)) { // Hanya simpan opsi yang tidak kosong
            $option->add($pollingId, $o);
        }
    }

    header("Location: index.php");
    exit; // Pastikan exit setelah redirect
}
?>

<div class="container">
<h2>Buat Polling</h2>

<form method="post">
    Judul
    <input type="text" name="judul" required>

    Deskripsi
    <textarea name="deskripsi"></textarea>

    Mulai
    <input type="datetime-local" name="start_date" required>

    Berakhir
    <input type="datetime-local" name="end_date" required>

    Opsi 1
    <input type="text" name="opsi[]" required>

    Opsi 2
    <input type="text" name="opsi[]" required>

    Opsi 3
    <input type="text" name="opsi[]">

    <button>Simpan</button>
</form>
</div>

<?php include '../layouts/footer.php'; ?>