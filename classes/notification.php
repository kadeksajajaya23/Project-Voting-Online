<?php
class Notification {
    private $conn;
    public function __construct($db) {
        $this->conn = $db;
    }

    public function send($user_id, $msg) {
        return $this->conn->prepare(
            "INSERT INTO notifications(user_id,message) VALUES (?,?)"
        )->execute([$user_id, $msg]);
    }

    public function get($user_id) {
        $stmt = $this->conn->prepare(
            "SELECT * FROM notifications WHERE user_id=? ORDER BY id DESC"
        );
        $stmt->execute([$user_id]);
        return $stmt;
    }
}

?>