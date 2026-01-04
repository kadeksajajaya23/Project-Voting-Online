<?php
class User {
    private $conn;
    public function __construct($db) {
        $this->conn = $db;
    }

    public function register($username, $email, $password) {
        $hash = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $this->conn->prepare(
            "INSERT INTO users VALUES (NULL,?,?,?)"
        );
        return $stmt->execute([$username, $email, $hash]);
    }

    public function login($email, $password) {
        $stmt = $this->conn->prepare(
            "SELECT * FROM users WHERE email=?"
        );
        $stmt->execute([$email]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && password_verify($password, $user['password'])) {
            return $user;
        }
        return false;
    }
}
