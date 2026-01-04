<?php
class Option {
    private $conn;
    public function __construct($db) {
        $this->conn = $db;
    }

    public function add($pollingId, $nama) {
        return $this->conn->prepare(
            "INSERT INTO options VALUES (NULL,?,?)"
        )->execute([$pollingId, $nama]);
    }

    public function byPolling($pollingId) {
        $stmt = $this->conn->prepare(
            "SELECT * FROM options WHERE polling_id=?"
        );
        $stmt->execute([$pollingId]);
        return $stmt;
    }
}
