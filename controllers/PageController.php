<?php

class PageController {
    public function __construct (
        private TodoList $todolist,
        private Task $task
    ) {}

    public function index() {
        $todoLists = $this->todolist->getAll();

        $selectedListID = isset($_GET['list']) ? (int) $_GET['list'] : (isset($todoLists[0]) ? (int) $todoLists[0]['id']: null);

        $selectedList = $this->todolist->getOneByID($selectedListID);

        $selectedTodoListTasks = $this->task->getByTodoListID($selectedListID);

        require './views/home.php';
    }

    public function createTask () {
        $taskBody = $_POST['task'] ?? null;
        $todoListID = $_POST['todoListID'] ?? null;

        if (
            $todoListID === false ||
            $todoListID === null ||
            $todoListID <= 0 ||
            !is_string($taskBody) ||
            trim($taskBody) === ''
        ) {
            http_response_code(422);

            echo json_encode([
                'error' =>  'Invalid ID or task content'
            ]);

            return;
        }

        $todoListID = (int) $todoListID;
        $taskBody = trim($taskBody);

        $new_task_id = $this->task->createOne([
            'task' => $taskBody,
            'todolist_id' => $todoListID
        ]);

        echo json_encode([
                'validated' => true,
                'todoListID' => $todoListID,
                'taskBody' => $taskBody,
                'id' => $new_task_id
            ]
        );
    }

    public function checkoff () {
        $id = $_POST['id'] ?? null;
        $column = 'done';
        $checked = (int) $_POST['done'] ?? null;

        $this->task->updateOneById($id, $column, $checked);

        echo json_encode([
            'received' => [
                'checked' => $checked,
                'id' => $id
            ]
        ]);
    }

    public function update() {
        $id = $_POST['id'] ?? null;
        $column = 'task';
        $task = $_POST['task'] ?? null;

        $this->task->updateOneById($id, $column, $task);

        echo json_encode(
            [
                'received' => [
                    'id' => $id,
                    'task' => $task
                ]
            ]
        );
    }

    public function deleteTask() {
        $id = $_POST['id'] ?? null;
        $this->task->deleteOneById($id);

        http_response_code(204);
    }

    public function createTodoList () {
        $todoListName = $_POST['todoListName'] ?? null;

        if (trim($todoListName) === '') {
            http_response_code(422);

            echo json_encode(
                ["error" => "Invalid todolist name"]
            );

            return;
        }

        $newTodoListId = $this->todolist->createOne ([
            'name' => $todoListName
        ]);

        http_response_code(200);

        echo json_encode([
            'validated' => true,
            'todoListId' => $newTodoListId,
            'todoListName' => $todoListName
        ]);
    }

    public function updateTodoList () {
        $todoListId = $_POST['todoListId'] ?? null;
        $updatedName = $_POST['updatedTodoListName'] ?? null;

        $this->todolist->updateOneById(
            $todoListId,
            'name',
            $updatedName
        );
    }

    public function deleteTodoList () {
        $todoListId = $_POST['todoListId'] ?? null;
        $this->todolist->deleteOneById($todoListId);

        http_response_code(204);
    }
}