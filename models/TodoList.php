<?php

class TodoList extends Model {
    protected function table(): string {
        return  'todolists';
    }

    protected function creatableColumns () : array {
        return ['name'];
    }

    protected function updatableColumns () : array {
        return ['name', 'task_count'];
    }
}