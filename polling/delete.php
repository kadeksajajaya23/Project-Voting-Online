<?php
session_start();
require_once '../config/Database.php';

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
$polling_id = $_GET['id'];
$user_id = $_SESSION['user_id'];

/* CEK KEPEMILIKAN POLLING */
$stmt = $db->prepare(
    "SELECT id FROM pollings WHERE id = ? AND user_id = ?"
);
$stmt->execute([$polling_id, $user_id]);

if ($stmt->rowCount() == 0) {
    die("Akses ditolak");
}

/* HAPUS DATA TERKAIT (URUTAN PENTING) */
$db->prepare("DELETE FROM votes WHERE polling_id = ?")
   ->execute([$polling_id]);

$db->prepare("DELETE FROM options WHERE polling_id = ?")
   ->execute([$polling_id]);

$db->prepare("DELETE FROM pollings WHERE id = ?")
   ->execute([$polling_id]);

header("Location: index.php");
exit;
