<?php

?>

<html>

    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
        <title>Todo App</title>
    </head>

    <body class="bg-light">

        <nav class="nav bg-white border-bottom px-4 py-3">
            <div class="container-fluid">
                <a href="/" class="navbar-brand fw-bold fs-3 mb-0">
                    <span class="text-primary me-2">&#9745;</span>
                    TodoList
                </a>
            </div>
       </nav>

        <div class="container-fluid">
            <div class="row min-vh-100">

                <aside class="col-md-3 col-lg-2 border-end bg-white p-3">
                    <h2 class="h6 text-uppercase text-secondary mb-3">My Lists</h2>
                    <div class="d-flex align-items-center mb-3">
                        <input type="text" name="new-todolist-input" id="new-todolist-input" class="form-control me-2" placeholder="New list name">
                        <button class="btn btn-primary w-40 flex-shrink-0 text-nowrap">
                            + New List
                        </button>
                    </div>
                    

                    <div class="list-group list-group-flush">
                        <?php foreach ($todoLists as $todoList): ?>
                            <a
                                href="?list=<?= (int) $todoList['id'] ?>" 
                                class="mb-2 list-group-item list-group-item-action
                                <?= (int) $todoList['id'] === $selectedListID ? 'active' : '' ?>
                                rounded">
                                <?= (string) $todoList['name'] ?>
                            </a>
                        <?php endforeach ?>
                    </div>
                </aside>

                <main class="col-md-9 col-lg-10 p-4 p-lg-5">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h1 class="h2 fw-bold mb-1"><?= $selectedList['name'] ?></h1>
                        <p class="text-secondary mb-0" id="task-count"></p>
                    </div>

                    <form action="" id="new-task-form" class="mb-4">
                        <div class="input-group">
                            <input type="hidden" name="todoListID" value=<?= $selectedListID ?>>
                            <input type="text" class="form-control me-2" id="new-task-input" name="task" placeholder="Just do it!" required>
                            <button class="btn btn-primary rounded" type="submit">Add Task</button>
                        </div>
                    </form>

                    <ul class="list-group" id="task-list">

                        <?php foreach ($selectedTodoListTasks as $task): ?>
                            <li class="list-group-item d-flex align-items-center gap-3 py-3">
                            <input
                                type="checkbox" 
                                class="form-check-input mt-0"
                                id="<?= $task['id']?>" <?= $task['done'] ? 'checked' : '' ?>
                            >
                            <label
                                for="<?= $task['id']?>"
                                class="form-check-label flex-grow-1 <?= $task['done'] ? 'text-decoration-line-through text-secondary' : '' ?>">
                                <?= $task['task'] ?>
                            </label>
                            <button type="button" class="btn btn-sm btn-outline-secondary" data-btn-type="edit">Edit</button>
                            <button class="btn btn-sm btn-outline-danger" data-btn-type="delete" type="button">Delete</button>
                        </li>
                        <?php endforeach; ?>

                        <?php if (empty($selectedTodoListTasks)): ?>
                            <li class="list-group-item text-secondary text-center py-4">
                                No tasks to display.
                            </li>
                        <?php endif; ?>

                    </ul> 
                </main>

            </div>
        </div>   

        <script>
            const taskForm = document.getElementById('new-task-form');
            const taskInput = document.getElementById('new-task-input');
            const taskList = document.getElementById('task-list');

            const updateTaskCount = () => {

                const taskCount = document.getElementById('task-count');
                
                const total = document.querySelectorAll('input[type="checkbox"]').length;
                const completed = document.querySelectorAll('input[type="checkbox"]:checked').length;

                taskCount.textContent = `${total} task${total !== 1 ? 's' : ''} | ${completed} completed`;
            }

            const checkoff = async (checkbox) => {
                if (!checkbox.matches('input[type="checkbox"]')) return;

                const label = checkbox.nextElementSibling;

                label.classList.toggle(
                    'text-decoration-line-through',
                    checkbox.checked
                );

                label.classList.toggle(
                    'text-secondary',
                    checkbox.checked
                );

                const formData = new FormData()

                console.log(checkbox.checked);

                formData.append('done', checkbox.checked ? '1' : '0');
                formData.append('id', checkbox.id);


                try {
                    const res = await fetch('/api/tasks/checkoff', {
                        method: 'POST',
                        body: formData
                    });

                    const result = await res.json();
                    
                    console.log(result);

                    if (!res.ok) throw new Error(task.error || 'Failed to create task');
                } catch (e) {
                    console.log(e);
                }

                
                updateTaskCount();
            }

            document.addEventListener('DOMContentLoaded', updateTaskCount);

            // add task on create-task form submit
            taskForm.addEventListener('submit', async (e) => {
                e.preventDefault();

                const formData = new FormData(taskForm);

                try {
                    const res = await fetch('/api/tasks', {
                        method: 'POST',
                        body: formData
                    });

                    const task = await res.json();

                    if (!res.ok) throw new Error(task.error || 'Failed to create task');

                    const taskItem = document.createElement('li');
                    taskItem.classList.add('list-group-item', 'py-3', 'd-flex', 'align-items-center', 'gap-3');

                    const taskItemCheckbox = document.createElement('input');
                    taskItemCheckbox.classList.add('form-check-input', 'mt-0');
                    taskItemCheckbox.type = 'checkbox';
                    taskItemCheckbox.id = task.id;

                    const taskItemLabel = document.createElement('label');
                    taskItemLabel.classList.add('form-check-label', 'flex-grow-1');
                    taskItemLabel.htmlFor = taskItemCheckbox.id;
                    taskItemLabel.textContent = task.taskBody;

                    const editButton = document.createElement('button');
                    editButton.classList.add('btn', 'btn-sm', 'btn-outline-secondary');
                    editButton.textContent = 'Edit';
                    editButton.type = 'button';
                    editButton.setAttribute('data-btn-type', 'edit');

                    const deleteButton = document.createElement('button');
                    deleteButton.classList.add('btn', 'btn-sm', 'btn-outline-danger');
                    deleteButton.textContent = 'Delete';
                    deleteButton.type = 'button';
                    deleteButton.setAttribute('data-btn-type', 'delete');

                    taskItem.append(taskItemCheckbox, taskItemLabel, editButton, deleteButton);
                    taskList.appendChild(taskItem);

                    taskInput.value = '';

                    updateTaskCount();

                    console.log('Task created: ', task);
                } catch (e) {
                    console.log(e);
                    alert(e.message);
                }

            });

            // checking off a task
            taskList.addEventListener('change', (e) => {
                const checkbox = e.target;

                checkoff(checkbox);
            });


            let taskLabelContent = "";

            const toggleButtons = (htmlElement) => {
                const state = htmlElement.querySelector('[data-btn-type="edit"') ? 'normal' : 'edit';

                if (state === 'normal') {
                    const btnA = htmlElement.querySelector('[data-btn-type="edit"]');
                    const btnB = htmlElement.querySelector('[data-btn-type="delete"]');

                    btnA.textContent = 'Save';
                    btnA.classList.replace('btn-outline-secondary', 'btn-primary');
                    btnA.dataset.btnType = 'save';
                    btnB.textContent = 'Cancel';
                    btnB.classList.replace('btn-outline-danger', 'btn-outline-secondary');
                    btnB.dataset.btnType = 'cancel';

                } else {
                    const btnA = htmlElement.querySelector('[data-btn-type="save"]');
                    const btnB = htmlElement.querySelector('[data-btn-type="cancel"]');

                    btnA.textContent = 'Edit';
                    btnA.classList.replace('btn-primary', 'btn-outline-secondary');
                    btnA.dataset.btnType = 'edit';
                    btnB.textContent = 'Delete';
                    btnB.classList.replace('btn-outline-secondary', 'btn-outline-danger');
                    btnB.dataset.btnType = 'delete';
                }

            }

            const updateTask = async (updatedContent, editInput, updatedLabel) => {
                const task = updatedContent;
                const id = updatedLabel.htmlFor;

                try {
                    const formData = new FormData();
                    formData.append('id', id);
                    formData.append('task', task);

                    const res = await fetch(
                        'api/tasks/update',
                        {
                            method: 'POST',
                            body: formData
                        }
                    );

                    const result = await res.json();
                    const updatedTask = result.received;

                    updatedLabel.textContent = updatedTask.task;

                    editInput.replaceWith(updatedLabel);
                } catch (e) {
                    console.error(e);
                }
            }

            const deleteTask = async (taskItem) => {
                try {
                    const formData = new FormData();
                    const id = taskItem.querySelector('label').htmlFor;
                    formData.append('id', id);
                    await fetch('/api/tasks/delete',
                        {
                            method: 'POST',
                            body: formData
                        }
                    );

                    taskItem.remove();
                    updateTaskCount();
                } catch (e) {
                    console.error(e);
                }
            }

            taskList.addEventListener('click', (e) => {
                const button = e.target.closest('[data-btn-type]');

                if (!button || !taskList.contains(button)) return;

                const taskItem = button.closest('li');
                const taskLabel = taskItem.querySelector('label');

                const updatedLabel = document.createElement('label');
                updatedLabel.classList.add('form-check-label', 'flex-grow-1');
                updatedLabel.htmlFor = taskItem.querySelector('.form-check-input').id

                switch (button.dataset.btnType) {
                    case 'delete':
                        deleteTask(taskItem);
                        break;
                    
                    case 'edit': {
                        taskLabelContent = taskLabel.textContent.trim();
                        const editInput = document.createElement('input');
                        editInput.classList.add('form-control', 'flex-grow-1', 'edit-task-input');
                        editInput.type = 'text';
                        editInput.value = taskLabelContent;
                        taskLabel.replaceWith(editInput);
                        editInput.focus();

                        toggleButtons(taskItem);
                        break;

                    }

                    case 'save': {
                        const updatedContent = taskItem.querySelector('.edit-task-input').value;
                        const editInput = taskItem.querySelector('.edit-task-input');

                        updateTask(updatedContent, editInput, updatedLabel);

                        toggleButtons(taskItem);

                        break;
                    }
                    
                    case 'cancel': {
                        console.log('Cancel edit');
                        updatedLabel.textContent = taskLabelContent;
                        const editInput = taskItem.querySelector('.edit-task-input');
                        editInput.replaceWith(updatedLabel);
                        
                        toggleButtons(taskItem);
                        break;
                    }
                }
            })


        </script>

    </body>


<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>