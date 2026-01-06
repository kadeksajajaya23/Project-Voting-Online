<?php
include '../config/Database.php';
include '../classes/Vote.php';

$db = (new Database())->getConnection();
$vote = new Vote($db);

header("Content-Type: text/csv");
header("Content-Disposition: attachment; filename=hasil.csv");

$output = fopen("php://output", "w");
fputcsv($output, ["Opsi", "Jumlah Vote"]);

$data = $vote->hasil($_GET['polling_id']);
while ($row = $data->fetch(PDO::FETCH_ASSOC)) {
    fputcsv($output, $row);
}
fclose($output);
?>

<div class="container">
    <h2>Terima kasih 🙏</h2>
    <p>Vote Anda berhasil disimpan.</p>

    <a href="index.php">Kembali ke Polling</a>
</div>

<?php require_once '../layouts/footer.php'; ?>