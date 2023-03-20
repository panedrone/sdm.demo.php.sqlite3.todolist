<?php

require_once '../controllers/GroupsController.php';
require_once '../controllers/GroupTasksController.php';
require_once '../controllers/TaskController.php';

require_once './utils.php';
require_once './validators.php';

$method = filter_input(INPUT_SERVER, 'REQUEST_METHOD', FILTER_SANITIZE_SPECIAL_CHARS);

function handle_groups()
{
    try {
        global $method;
        if ($method == "POST") {
            $data = json_decode(file_get_contents("php://input"));
            if (json_last_error() != JSON_ERROR_NONE) {
                http_response_code(Response::HTTP_BAD_REQUEST);
                return;
            }
            $err = validate_group($data);
            if ($err != null) {
                json_resp($err);
                http_response_code(Response::HTTP_BAD_REQUEST);
                return;
            }
            GroupsController::createGroup($data);
            http_response_code(Response::HTTP_CREATED);
        } else if ($method == "GET") {
            $arr = GroupsController::readGroups();
            json_resp($arr);
        }
    } catch (Exception $e) {
        http_response_code(Response::HTTP_INTERNAL_SERVER_ERROR);
    }
}

function handle_group($g_id)
{
    try {
        global $method;
        if ($method == "GET") {
            $item = GroupsController::readGroup($g_id);
            json_resp($item);
        } else if ($method == "PUT") {
            $data = json_decode(file_get_contents("php://input"));
            if (json_last_error() != JSON_ERROR_NONE) {
                http_response_code(Response::HTTP_BAD_REQUEST);
                return;
            }
            $err = validate_group($data);
            if ($err != null) {
                json_resp($err);
                http_response_code(Response::HTTP_BAD_REQUEST);
                return;
            }
            GroupsController::updateGroup($g_id, $data);
        } else if ($method == "DELETE") {
            GroupsController::deleteGroup($g_id);
            http_response_code(Response::HTTP_NO_CONTENT);
        }
    } catch (Exception $e) {
        http_response_code(Response::HTTP_INTERNAL_SERVER_ERROR);
    }
}

function handle_group_tasks($g_id)
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
            GroupTasksController::createTask($g_id, $data);
            http_response_code(Response::HTTP_CREATED);
        } else if ($method == "GET") {
            $arr = GroupTasksController::readGroupTasks($g_id);
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
