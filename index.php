<?php

require_once 'DB.php';
require_once 'Router.php';

$db = new Database();
$pdo = $db->connect();

require_once 'Model.php';
require_once './models/Task.php';
require_once './models/TodoList.php';

$todoList = new TodoList($pdo);
$task = new Task($pdo);

require_once './controllers/PageController.php';

$pageController = new PageController($todoList, $task);

$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$method = $_SERVER['REQUEST_METHOD'];

$router = new Router();

require_once 'routes.php';

$router->dispatch($uri, $method);

?>