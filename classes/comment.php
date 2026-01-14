<?php
class Comment {
    private $conn;
    public function __construct($db) {
        $this->conn = $db;
    }

    public function add($polling, $user, $isi) {
        return $this->conn->prepare(
            "INSERT INTO comments VALUES (NULL,?,?,?, 'pending')"
        )->execute([$polling, $user, $isi]);
    }

    public function approve($id) {
        return $this->conn->prepare(
            "UPDATE comments SET status='approved' WHERE id=?"
        )->execute([$id]);
    }

    public function approvedByPolling($polling) {
        $stmt = $this->conn->prepare(
            "SELECT * FROM comments WHERE polling_id=? AND status='approved'"
        );
        $stmt->execute([$polling]);
        return $stmt;
    }
    public function getApprovedByPolling($polling_id) {
    $stmt = $this->conn->prepare(
        "SELECT c.*, u.nama 
         FROM comments c 
         JOIN users u ON c.user_id = u.id
         WHERE c.polling_id = ? AND status='approved'
         ORDER BY c.id DESC"
    );
    $stmt->execute([$polling_id]);
    return $stmt;
}

public function getPendingByPollingOwner($polling_id, $owner_id) {
    $stmt = $this->conn->prepare("
        SELECT c.* FROM comments c
        JOIN pollings p ON c.polling_id = p.id
        WHERE p.user_id = ? AND c.status = 'pending'
    ");
    $stmt->execute([$owner_id]);
    return $stmt;
}

}