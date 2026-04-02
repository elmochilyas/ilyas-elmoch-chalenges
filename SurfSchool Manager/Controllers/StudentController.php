<?php

require_once 'config/database.php';

class StudentModel {
    private PDO $pdo;

    public function __construct() {
        $this->pdo = Database::getConnection();
    }

    public function getAll(): array {
        $stmt = $this->pdo->query("SELECT * FROM students");
        return $stmt->fetchAll();
    }


}