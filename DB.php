<?php

class Database {
    private $host = 'localhost';
    private $db = 'todoApp';
    private $dbuser = 'todoadmin';
    private $dbpass = 'password';
    private $chrset = 'utf8mb4';

    private $opts = [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES => False,
    ];

    public function connect(): PDO {
        try {
            $pdo = new PDO(
                "mysql:host={$this->host};dbname={$this->db};charset={$this->chrset}",
                $this->dbuser,
                $this->dbpass,
                $this->opts
            );

            return $pdo;
        } catch (\PDOException $e) {
            echo "Catch block ran";
            die("DB connection failed: " . $e->getMessage());
        }
    }
}