<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: ../auth/login.php");
    exit;
}
require_once '../config/Database.php';
require_once '../layouts/header.php';

$database = new Database();
$conn = $database->getConnection();

/* Ambil semua polling */
$stmtPolling = $conn->prepare("SELECT * FROM pollings");
$stmtPolling->execute();
$pollings = $stmtPolling->fetchAll(PDO::FETCH_ASSOC);
?>

<div class="container">
    <div class="card">
        <h2>Hasil Voting</h2>

        <?php if (count($pollings) === 0): ?>
            <p>Belum ada polling.</p>
        <?php endif; ?>

        <?php foreach ($pollings as $poll): ?>

            <?php
            /* JIKA POLLING BELUM SELESAI */
            if (strtotime($poll['end_date']) > time()):
            ?>
                <div class="poll-result">
                    <h3><?= htmlspecialchars($poll['judul']) ?></h3>
                    <p><?= htmlspecialchars($poll['deskripsi']) ?></p>

                    <p class="text-muted">
                        ⏳ Polling masih berlangsung.<br>
                        Hasil akan ditampilkan setelah polling berakhir
                        (<?= date('d M Y H:i', strtotime($poll['end_date'])) ?>)
                    </p>
                    <hr>
                </div>
                <?php continue; ?>
            <?php endif; ?>

            <!-- JIKA POLLING SUDAH SELESAI -->
            <div class="poll-result">
                <h3><?= htmlspecialchars($poll['judul']) ?></h3>
                <p><?= htmlspecialchars($poll['deskripsi']) ?></p>

                <?php
                /* Ambil opsi + jumlah vote */
                $stmtOpt = $conn->prepare("
                    SELECT o.nama_opsi, COUNT(v.id) AS total_vote
                    FROM options o
                    LEFT JOIN votes v ON o.id = v.option_id
                    WHERE o.polling_id = ?
                    GROUP BY o.id
                ");
                $stmtOpt->execute([$poll['id']]);
                $options = $stmtOpt->fetchAll(PDO::FETCH_ASSOC);

                $totalVotes = array_sum(array_column($options, 'total_vote'));
                ?>

                <ul class="result-list">
                    <?php foreach ($options as $opt):
                        $percent = ($totalVotes > 0)
                            ? round(($opt['total_vote'] / $totalVotes) * 100, 1)
                            : 0;
                    ?>
                        <li>
                            <strong><?= htmlspecialchars($opt['nama_opsi']) ?></strong>
                            — <?= $opt['total_vote'] ?> suara
                            (<?= $percent ?>%)
                        </li>
                    <?php endforeach; ?>
                </ul>

                <!-- =========================
                     KOMENTAR (SUDAH FIX)
                ========================== -->
                <?php
                $stmtKomentar = $conn->prepare("
                    SELECT isi
                    FROM comments
                    WHERE polling_id = ?
                ");

                $stmtKomentar->execute([$poll['id']]);
                $komentar = $stmtKomentar->fetchAll(PDO::FETCH_ASSOC);
                ?>

                <h4>Komentar</h4>

                <?php if (count($komentar) === 0): ?>
                    <p>Belum ada komentar.</p>
                <?php else: ?>
                    <?php foreach ($komentar as $k): ?>
                        <div class="comment-box">
                            <p><?= nl2br(htmlspecialchars($k['isi'])) ?></p>
                        </div>
                        <hr>
                    <?php endforeach; ?>
                <?php endif; ?>

                <div style="margin-top:10px;">
                    <a href="export/hasil_csv.php?polling_id=<?= $poll['id'] ?>">CSV</a>
                    |
                    <a href="export/print_hasil_pdf.php?id=<?= $poll['id'] ?>">PDF</a>
                </div>

                <hr>
            </div>

        <?php endforeach; ?>
    </div>
</div>

<?php require_once '../layouts/footer.php'; ?>
