<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: ../auth/login.php");
    exit;
}
include '../layouts/header.php';
include '../config/Database.php';
include '../classes/Polling.php';

$db = (new Database())->getConnection();
$polling = new Polling($db);
$data = $polling->all();
?>

<div class="container">
    <h2>Daftar Polling</h2>

    <div class="action-buttons">
        <a href="create.php" class="btn btn-primary">Buat Polling</a>
        <a href="hasil.php" class="btn btn-secondary">Hasil Vote</a>
    </div>

    <table>
        <tr>
            <th>Judul</th>
            <th>Status</th>
            <th>Aksi</th>
        </tr>

        <?php while ($row = $data->fetch(PDO::FETCH_ASSOC)) { ?>
            <tr>
                <td><?= htmlspecialchars($row['judul']); ?></td>
                <td>
                    <?= (strtotime($row['end_date']) < time()) ? "Ditutup" : "Aktif"; ?>
                </td>
                <td class="action-cell">
                    <a href="detail.php?id=<?= $row['id']; ?>" class="btn btn-detail">Detail</a>

                    <?php if (isset($_SESSION['user_id']) && $_SESSION['user_id'] == $row['user_id']) { ?>
                        <a href="edit.php?id=<?= $row['id']; ?>" class="btn btn-edit">Edit</a>

                        <a href="delete.php?id=<?= $row['id']; ?>"
                           class="btn btn-danger"
                           onclick="return confirm('Yakin ingin menghapus polling ini?')">
                           Hapus
                        </a>
                    <?php } ?>
                </td>
            </tr>
        <?php } ?>
    </table>
</div>

<?php include '../layouts/footer.php'; ?>
