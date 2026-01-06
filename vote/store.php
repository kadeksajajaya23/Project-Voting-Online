<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: ../auth/login.php");
    exit;
}

// Perbaikan: Gunakan nama file yang benar (lowercase)
include '../config/database.php';
include '../classes/vote.php';

$db = (new Database())->getConnection();
$vote = new Vote($db);

$userId    = $_SESSION['user_id'];
$pollingId = $_POST['polling_id'];
$optionId  = $_POST['option_id'];

// Validasi input
if (!is_numeric($pollingId) || !is_numeric($optionId)) {
    die("Data tidak valid.");
}

if ($vote->vote($userId, $pollingId, $optionId)) {
    header("Location: ../polling/detail.php?id=$pollingId");
    exit; // Penting!
} else {
    echo "<script>alert('❌ Anda sudah melakukan voting.'); window.location.href='../polling/detail.php?id=$pollingId';</script>";
    exit;
}