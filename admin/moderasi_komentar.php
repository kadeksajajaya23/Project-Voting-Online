<?php
$comments = $comment->getPendingByPollingOwner($polling_id, $_SESSION['user_id']);

while ($c = $comments->fetch()) {
    echo $c['isi'];
    echo "<a href='approve.php?id={$c['id']}'>Setujui</a>";
}
?>
