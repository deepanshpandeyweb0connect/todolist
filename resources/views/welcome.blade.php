<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Task App</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/sweetalert2/11.7.4/sweetalert2.min.css">
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
</head>

<body>
    <div class="container box mt-5">
        <div class="pt-4 pb-3 border-bottom">
            <h2>PHP - Simple To Do List App</h2>
        </div>

        <div class="mt-4 text-center" id="show-all-todo-btn">
            <button id="showAllTasks" class="btn btn-info mb-4">Show All Tasks</button>
        </div>

        <div id="todo-tasks" class="d-none">
            <form id="taskForm" class="text-center my-4 d-flex justify-content-center">
                <input type="text" id="taskInput" placeholder="Enter your task" required class="px-3">
                <button type="submit" class="btn btn-primary ms-2">Add Task</button>
            </form>
    
            <table id="taskTable" class="table">
                <thead>
                    <tr>
                        <th>Task</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert2/11.7.4/sweetalert2.min.js"></script>
    <script>
        $(document).ready(function() {
            let tasks = []; 

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            $('#taskForm').on('submit', function(event) {
                event.preventDefault();
                var task = $('#taskInput').val().trim();

                if (tasks.includes(task)) {
                    Swal.fire({
                        title: 'Error!',
                        text: 'Task already exists!',
                        icon: 'error',
                        confirmButtonText: 'OK'
                    });
                    return;
                }

                if (task) {
                    $.ajax({
                        url: "{{ route('tasks.store') }}",
                        method: 'POST',
                        data: {
                            name: task
                        },
                        success: function(response) {
                            tasks.push(response.name); 
                            $('#taskTable tbody').append(`
                                <tr data-id="${response.id}" class="${response.completed ? 'completed' : ''}">
                                <td>${response.name}</td>
                                <td>${response.completed ? 'Completed' : ''}</td>
                                <td>
                                    ${task.completed ? '' : `
                                        <input type="checkbox" class="complete-task d-none" ${task.completed ? 'checked' : ''}> 
                                        <button class="btn btn-success btn-sm mark-complete">
                                            <svg width="25px" height="25px" viewBox="0 0 24 24" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">
                                                <g id="🔍-Product-Icons" stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                                    <g id="ic_fluent_checkbox_checked_24_regular" fill="#ffffff" fill-rule="nonzero">
                                                        <path d="M18.25,3 C19.7687831,3 21,4.23121694 21,5.75 L21,18.25 C21,19.7687831 19.7687831,21 18.25,21 L5.75,21 C4.23121694,21 3,19.7687831 3,18.25 L3,5.75 C3,4.23121694 4.23121694,3 5.75,3 L18.25,3 Z M18.25,4.5 L5.75,4.5 C5.05964406,4.5 4.5,5.05964406 4.5,5.75 L4.5,18.25 C4.5,18.9403559 5.05964406,19.5 5.75,19.5 L18.25,19.5 C18.9403559,19.5 19.5,18.9403559 19.5,18.25 L19.5,5.75 C19.5,5.05964406 18.9403559,4.5 18.25,4.5 Z M10,14.4393398 L16.4696699,7.96966991 C16.7625631,7.6767767 17.2374369,7.6767767 17.5303301,7.96966991 C17.7965966,8.23593648 17.8208027,8.65260016 17.6029482,8.94621165 L17.5303301,9.03033009 L10.5303301,16.0303301 C10.2640635,16.2965966 9.84739984,16.3208027 9.55378835,16.1029482 L9.46966991,16.0303301 L6.46966991,13.0303301 C6.1767767,12.7374369 6.1767767,12.2625631 6.46966991,11.9696699 C6.73593648,11.7034034 7.15260016,11.6791973 7.44621165,11.8970518 L7.53033009,11.9696699 L10,14.4393398 L16.4696699,7.96966991 L10,14.4393398 Z" id="🎨Color">

                                                        </path>
                                                    </g>
                                                </g>
                                            </svg>
                                        </button>
                                    `}
                                    <button class="btn btn-danger btn-sm delete-task">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="25" height="25" fill="currentColor" class="bi bi-x" viewBox="0 0 16 16">
                                            <path d="M4.646 4.646a.5.5 0 0 1 .708 0L8 7.293l2.646-2.647a.5.5 0 0 1 .708.708L8.707 8l2.647 2.646a.5.5 0 0 1-.708.708L8 8.707l-2.646 2.647a.5.5 0 0 1-.708-.708L7.293 8 4.646 5.354a.5.5 0 0 1 0-.708"/>
                                        </svg>
                                    </button>
                                </td>
                                </tr>
                            `);
                            $('#taskInput').val('');

                            Swal.fire({
                                title: 'Success!',
                                text: 'Your task was added successfully.',
                                icon: 'success',
                                confirmButtonText: 'OK'
                            });
                        },
                        error: function(response) {
                            console.error('Error:', response);

                            Swal.fire({
                                title: 'Error!',
                                text: 'Something went wrong. Please try again.',
                                icon: 'error',
                                confirmButtonText: 'OK'
                            });
                        }
                    });
                }
            });

            $(document).on('change', '.complete-task', function() {

                var tr = $(this).closest('tr');
                var taskId = tr.data('id');
                var isChecked = $(this).is(':checked');

                $.ajax({
                    url: `/tasks/${taskId}/complete`,
                    method: 'PUT',
                    success: function(response) {
                        if (response.success) {
                            tr.toggleClass('completed', isChecked);
                            tr.find('td:eq(1)').text(isChecked ? 'Completed' : '');
                            tr.find('.mark-complete').remove();
                        }

                        Swal.fire({
                            title: 'Success!',
                            text: 'Your task was completed successfully.',
                            icon: 'success',
                            confirmButtonText: 'OK'
                        });
                    },
                    error: function(response) {
                        console.error('Error:', response);

                        Swal.fire({
                            title: 'Error!',
                            text: 'Something went wrong. Please try again.',
                            icon: 'error',
                            confirmButtonText: 'OK'
                        });
                    }
                });
            });

            $(document).on('click', '.delete-task', function() {
                var tr = $(this).closest('tr');
                var taskId = tr.data('id');

                Swal.fire({
                    title: 'Are you sure?',
                    text: 'Do you really want to delete this task?',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Yes, delete it!',
                    cancelButtonText: 'No, cancel!',
                    reverseButtons: true
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: `/tasks/${taskId}`,
                            method: 'DELETE',
                            success: function() {
                                tr.remove(); 

                                Swal.fire({
                                    title: 'Success!',
                                    text: 'Your task was deleted successfully.',
                                    icon: 'success',
                                    confirmButtonText: 'OK'
                                });
                            },
                            error: function(response) {
                                console.error('Error:', response);

                                Swal.fire({
                                    title: 'Error!',
                                    text: 'Something went wrong. Please try again.',
                                    icon: 'error',
                                    confirmButtonText: 'OK'
                                });
                            }
                        });
                    }
                });


            });

            $('#showAllTasks').on('click', function() {

                if ($('#todo-tasks').hasClass('d-none')) {
                    $('#todo-tasks').removeClass('d-none');
                    $('#show-all-todo-btn').addClass('d-none');
                }

                $.ajax({
                    url: "{{ route('tasks.index') }}",
                    method: 'GET',
                    success: function(response) {
                        $('#taskTable tbody').empty();

                        response.forEach(function(task) {
                            $('#taskTable tbody').append(`
                            <tr data-id="${task.id}" class="${task.completed ? 'completed' : ''}">
                            <td>${task.name}</td>
                            <td>${task.completed ? 'Completed' : ''}</td>
                            <td>
                                ${task.completed ? '' : `
                                    <input type="checkbox" class="complete-task d-none" ${task.completed ? 'checked' : ''}> 
                                    <button class="btn btn-success btn-sm mark-complete">
                                        <svg width="25px" height="25px" viewBox="0 0 24 24" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">
                                            <g id="🔍-Product-Icons" stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                                <g id="ic_fluent_checkbox_checked_24_regular" fill="#ffffff" fill-rule="nonzero">
                                                    <path d="M18.25,3 C19.7687831,3 21,4.23121694 21,5.75 L21,18.25 C21,19.7687831 19.7687831,21 18.25,21 L5.75,21 C4.23121694,21 3,19.7687831 3,18.25 L3,5.75 C3,4.23121694 4.23121694,3 5.75,3 L18.25,3 Z M18.25,4.5 L5.75,4.5 C5.05964406,4.5 4.5,5.05964406 4.5,5.75 L4.5,18.25 C4.5,18.9403559 5.05964406,19.5 5.75,19.5 L18.25,19.5 C18.9403559,19.5 19.5,18.9403559 19.5,18.25 L19.5,5.75 C19.5,5.05964406 18.9403559,4.5 18.25,4.5 Z M10,14.4393398 L16.4696699,7.96966991 C16.7625631,7.6767767 17.2374369,7.6767767 17.5303301,7.96966991 C17.7965966,8.23593648 17.8208027,8.65260016 17.6029482,8.94621165 L17.5303301,9.03033009 L10.5303301,16.0303301 C10.2640635,16.2965966 9.84739984,16.3208027 9.55378835,16.1029482 L9.46966991,16.0303301 L6.46966991,13.0303301 C6.1767767,12.7374369 6.1767767,12.2625631 6.46966991,11.9696699 C6.73593648,11.7034034 7.15260016,11.6791973 7.44621165,11.8970518 L7.53033009,11.9696699 L10,14.4393398 L16.4696699,7.96966991 L10,14.4393398 Z" id="🎨Color">

                                                    </path>
                                                </g>
                                            </g>
                                        </svg>
                                    </button>
                                `}
                                <button class="btn btn-danger btn-sm delete-task">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="25" height="25" fill="currentColor" class="bi bi-x" viewBox="0 0 16 16">
                                        <path d="M4.646 4.646a.5.5 0 0 1 .708 0L8 7.293l2.646-2.647a.5.5 0 0 1 .708.708L8.707 8l2.647 2.646a.5.5 0 0 1-.708.708L8 8.707l-2.646 2.647a.5.5 0 0 1-.708-.708L7.293 8 4.646 5.354a.5.5 0 0 1 0-.708"/>
                                    </svg>
                                </button>
                            </td>
                            </tr>
                        `);
                        });
                    },
                    error: function(response) {
                        console.error('Error:', response);
                    }
                });
            });

            $(document).on('click', '.mark-complete', function() {
                var tr = $(this).closest('tr');
                var checkbox = tr.find('.complete-task');
                var taskId = tr.data('id');
                checkbox.prop('checked', true).trigger('change'); 
            });
        });
    </script>
</body>

</html>