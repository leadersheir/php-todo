<?php

$router->get(
    '/',
    fn () => $pageController->index()
);

$router->post(
    '/api/tasks',
    fn () => $pageController->createTask()
);

$router->post(
    '/api/tasks/checkoff',
    fn () => $pageController->checkoff()
);

$router->post(
    '/api/tasks/update',
    fn() => $pageController->update()
);

$router->post(
    '/api/tasks/delete',
    fn() => $pageController->deleteTask()
);

$router->post(
    '/api/todolists/create',
    fn() => $pageController->createTodoList()
);

$router->post(
    '/api/todolists/update',
    fn() => $pageController->updateTodoList()
);

$router->post(
    '/api/todolists/delete',
    fn() => $pageController->deleteTodoList()
);