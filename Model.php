<?php

abstract class Model {

    abstract protected function table(): string;
    abstract protected function updatableColumns(): array;
    abstract protected function creatableColumns(): array;

    protected PDO $pdo;

    public function __construct(PDO $pdo) {
        $this->pdo = $pdo;
    }

    public function createOne (array $data): int {
        $columns = array_keys($data);

        foreach ($columns as $column) {
            if(!in_array($column, $this->creatableColumns(), true)) {
                throw new InvalidArgumentException(
                    "Column '$column' cannot be created."
                );
            }
        }

        $column_names = implode(', ', $columns);
        $column_values = implode(', ', array_fill(0, count($columns), '?'));

        $stmt = $this->pdo->prepare(
            "INSERT INTO " . $this->table() . " ($column_names) VALUES ($column_values)"
        );

        $stmt->execute(array_values($data));
        return $this->pdo->lastInsertId();
    }

    public function getAll (): array {
        $stmt = $this->pdo->query(
            "SELECT * FROM " . $this->table()
        );
        return $stmt->fetchAll();
    }

    public function getOneByID (int $id) {
        $stmt = $this->pdo->prepare(
            "SELECT * FROM " . $this->table() . " WHERE id=?"
        );
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    public function updateOneById (int $id, string $column, mixed $content): void {
    
        if (!in_array($column, $this->updatableColumns(), true)) {
            throw new InvalidArgumentException(
                "Column '$column' cannot be updated."
            );
        }

        $stmt = $this->pdo->prepare(
            "UPDATE " . $this->table() . " SET $column=? WHERE id=?"
        );
        $stmt->execute([$content, $id]);
    }

    public function deleteOneById (int $id): void {
        $stmt = $this->pdo->prepare(
            "DELETE FROM " . $this->table() . " WHERE id=?"
        );
        $stmt->execute([$id]);
    }

}