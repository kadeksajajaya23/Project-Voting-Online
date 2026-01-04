<?php
include '../layouts/header.php';
include '../config/Database.php';
include '../classes/Polling.php';

$db = (new Database())->getConnection();
$polling = new Polling($db);
$data = $polling->all();
?>

<div class="container">
<h2>Daftar Polling</h2>
<a href="create.php"><button>Buat Polling</button></a>

<table>
<tr>
    <th>Judul</th>
    <th>Status</th>
    <th>Aksi</th>
</tr>

<?php while ($row = $data->fetch(PDO::FETCH_ASSOC)) { ?>
<tr>
    <td><?= $row['judul']; ?></td>
    <td>
        <?= (strtotime($row['end_date']) < time()) ? "Ditutup" : "Aktif"; ?>
    </td>
    <td>
        <a href="detail.php?id=<?= $row['id']; ?>">Detail</a>
    </td>
</tr>
<?php } ?>
</table>
</div>

<?php include '../layouts/footer.php'; ?>
