<?php
class Polling {
    private $conn;
    public function __construct($db) {
        $this->conn = $db;
    }

    public function create($user, $judul, $desk, $start, $end) {
        return $this->conn->prepare(
            "INSERT INTO pollings VALUES (NULL,?,?,?,?,?)"
        )->execute([$user, $judul, $desk, $start, $end]);
    }

    public function all() {
        return $this->conn->query("SELECT * FROM pollings");
    }

    public function find($id) {
        $stmt = $this->conn->prepare("SELECT * FROM pollings WHERE id=?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function isClosed($id) {
        $stmt = $this->conn->prepare(
            "SELECT end_date < NOW() FROM pollings WHERE id=?"
        );
        $stmt->execute([$id]);
        return $stmt->fetchColumn();
    }
}
