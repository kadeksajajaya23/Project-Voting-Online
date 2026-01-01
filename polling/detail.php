<?php
include '../layouts/header.php';
include '../config/Database.php';
include '../classes/Polling.php';
include '../classes/Option.php';
include '../classes/Vote.php';

$db = (new Database())->getConnection();
$polling = new Polling($db);
$option = new Option($db);
$vote = new Vote($db);

$data = $polling->find($_GET['id']);
$options = $option->byPolling($_GET['id']);
$closed = strtotime($data['end_date']) < time();
?>

<div class="container">
<h2><?= $data['judul']; ?></h2>
<p><?= $data['deskripsi']; ?></p>

<?php if (!$closed) { ?>
<form method="post" action="/voting-app/vote/store.php">
    <?php while ($o = $options->fetch(PDO::FETCH_ASSOC)) { ?>
        <label>
            <input type="radio" name="option_id" value="<?= $o['id']; ?>" required>
            <?= $o['nama_opsi']; ?>
        </label><br>
    <?php } ?>
    <input type="hidden" name="polling_id" value="<?= $data['id']; ?>">
    <br>
    <button>Vote</button>
</form>
<?php } else { ?>
<div class="alert">Polling sudah ditutup. Hasil voting:</div>

<table>
<tr><th>Opsi</th><th>Vote</th></tr>
<?php
$hasil = $vote->hasil($_GET['id']);
while ($h = $hasil->fetch(PDO::FETCH_ASSOC)) {
?>
<tr>
    <td><?= $h['nama_opsi']; ?></td>
    <td><?= $h['total']; ?></td>
</tr>
<?php } ?>
</table>

<br>
<a href="/voting-app/export/hasil_csv.php?polling_id=<?= $_GET['id']; ?>">
    <button>Export CSV</button>
</a>
<?php } ?>
</div>

<?php include '../layouts/footer.php'; ?>
