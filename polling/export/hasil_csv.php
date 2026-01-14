<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: ../../auth/login.php");
    exit;
}

require_once '../../config/Database.php';

$polling_id = $_GET['polling_id'] ?? null;
if (!$polling_id || !is_numeric($polling_id)) {
    die("Polling ID tidak valid.");
}

$database = new Database();
$conn = $database->getConnection();

// Ambil data polling
$stmt = $conn->prepare("SELECT judul FROM pollings WHERE id = ?");
$stmt->execute([$polling_id]);
$poll = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$poll) {
    die("Polling tidak ditemukan.");
}

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

// Set header untuk download file CSV
header('Content-Type: text/csv');
header('Content-Disposition: attachment; filename="hasil_polling_' . $polling_id . '.csv"');

$output = fopen('php://output', 'w');

// Judul polling
fputcsv($output, ['Hasil Polling: ' . $poll['judul']]);
fputcsv($output, []); // baris kosong

// Header kolom
fputcsv($output, ['Opsi', 'Jumlah Suara', 'Persentase (%)']);

// Data
foreach ($options as $opt) {
    $percent = ($totalVotes > 0) ? round(($opt['total_vote'] / $totalVotes) * 100, 2) : 0;
    fputcsv($output, [
        $opt['nama_opsi'],
        $opt['total_vote'],
        $percent
    ]);
}

fclose($output);
exit;
?>