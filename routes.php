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