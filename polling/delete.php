<?php
session_start();
include '../config/Database.php';

$db = (new Database())->getConnection();

$stmt = $db->prepare("DELETE FROM pollings WHERE id=? AND user_id=?");
$stmt->execute([$_GET['id'], $_SESSION['user_id']]);

header("Location: index.php");
