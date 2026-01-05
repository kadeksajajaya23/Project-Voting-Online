<?php
class Vote {
    private $conn;
    public function __construct($db) {
        $this->conn = $db;
    }

    public function hasVoted($user, $polling) {
        $stmt = $this->conn->prepare(
            "SELECT id FROM votes WHERE user_id=? AND polling_id=?"
        );
        $stmt->execute([$user, $polling]);
        return $stmt->rowCount() > 0;
    }

    public function vote($user, $polling, $option) {
        if ($this->hasVoted($user, $polling)) return false;
        return $this->conn->prepare(
            "INSERT INTO votes VALUES (NULL,?,?,?)"
        )->execute([$user, $polling, $option]);
    }

    public function hasil($polling) {
        $stmt = $this->conn->prepare(
            "SELECT o.nama_opsi, COUNT(v.id) total
             FROM options o
             LEFT JOIN votes v ON o.id=v.option_id
             WHERE o.polling_id=?
             GROUP BY o.id"
        );
        $stmt->execute([$polling]);
        return $stmt;
    }
}
