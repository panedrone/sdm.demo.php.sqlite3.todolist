<?php

require_once '../controllers/ProjectsController.php';
require_once '../controllers/ProjectTasksController.php';
require_once '../controllers/TaskController.php';

require_once './utils.php';
require_once './validators.php';

$method = filter_input(INPUT_SERVER, 'REQUEST_METHOD', FILTER_SANITIZE_SPECIAL_CHARS);

function handle_projects()
{
    try {
        global $method;
        if ($method == "POST") {
            $data = json_decode(file_get_contents("php://input"));
            if (json_last_error() != JSON_ERROR_NONE) {
                http_response_code(Response::HTTP_BAD_REQUEST);
                return;
            }
            $err = validate_project($data);
            if ($err != null) {
                json_resp($err);
                http_response_code(Response::HTTP_BAD_REQUEST);
                return;
            }
            ProjectsController::createProject($data);
            http_response_code(Response::HTTP_CREATED);
        } else if ($method == "GET") {
            $arr = ProjectsController::readProjects();
            json_resp($arr);
        }
    } catch (Exception $e) {
        http_response_code(Response::HTTP_INTERNAL_SERVER_ERROR);
    }
}

function handle_project($p_id)
{
    try {
        global $method;
        if ($method == "GET") {
            $item = ProjectsController::readProject($p_id);
            json_resp($item);
        } else if ($method == "PUT") {
            $data = json_decode(file_get_contents("php://input"));
            if (json_last_error() != JSON_ERROR_NONE) {
                http_response_code(Response::HTTP_BAD_REQUEST);
                return;
            }
            $err = validate_project($data);
            if ($err != null) {
                json_resp($err);
                http_response_code(Response::HTTP_BAD_REQUEST);
                return;
            }
            ProjectsController::updateProject($p_id, $data);
        } else if ($method == "DELETE") {
            ProjectsController::deleteProject($p_id);
            http_response_code(Response::HTTP_NO_CONTENT);
        }
    } catch (Exception $e) {
        http_response_code(Response::HTTP_INTERNAL_SERVER_ERROR);
    }
}

function handle_project_tasks($p_id)
{
    try {
        global $method;
        if ($method == "POST") {
            $data = json_decode(file_get_contents("php://input"));
            if (json_last_error() != JSON_ERROR_NONE) {
                http_response_code(Response::HTTP_BAD_REQUEST);
                return;
            }
            $err = validate_task($data, false);
            if ($err != null) {
                json_resp($err);
                http_response_code(Response::HTTP_BAD_REQUEST);
                return;
            }
            ProjectTasksController::createTask($p_id, $data);
            http_response_code(Response::HTTP_CREATED);
        } else if ($method == "GET") {
            $arr = ProjectTasksController::readProjectTasks($p_id);
            json_resp($arr);
        }
    } catch (Exception $e) {
        http_response_code(Response::HTTP_INTERNAL_SERVER_ERROR);
    }
}

function handle_task($t_id)
{
    try {
        global $method;
        if ($method == "GET") {
            $item = TaskController::readTask($t_id);
            json_resp($item);
        } else if ($method == "PUT") {
            $data = json_decode(file_get_contents("php://input"));
            if (json_last_error() != JSON_ERROR_NONE) {
                http_response_code(Response::HTTP_BAD_REQUEST);
                return;
            }
            $err = validate_task($data, true);
            if ($err != null) {
                json_resp($err);
                http_response_code(Response::HTTP_BAD_REQUEST);
                return;
            }
            TaskController::updateTask($t_id, $data);
        } else if ($method == "DELETE") {
            TaskController::deleteTask($t_id);
            http_response_code(Response::HTTP_NO_CONTENT);
        }
    } catch (Exception $e) {
        http_response_code(Response::HTTP_INTERNAL_SERVER_ERROR);
    }
}
