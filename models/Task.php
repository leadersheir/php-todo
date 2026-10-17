<?php

class Task extends Model {
    protected function table(): string {
        return  'tasks';
    }

    protected function creatableColumns () : array {
        return ['task', 'todolist_id'];
    }

    protected function updatableColumns () : array {
        return ['task', 'done'];
        // add functionality to move a task to a different list
        // drag and drop, perhaps?
        // return ['task', 'done', 'todolist_id'];
    }

    public function getByTodoListID (int $todolistID) {
        $stmt = $this->pdo->prepare(
            "SELECT * FROM tasks WHERE todolist_id = ?"
        );
        $stmt->execute([$todolistID]);

        return $stmt->fetchAll();
    }
}