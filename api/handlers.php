<?php

require_once './svc/SvcProjects.php';
require_once './svc/SvcProjectTasks.php';
require_once './svc/SvcTasks.php';

require_once './utils.php';
require_once './validators.php';

function handle_projects()
{
    try {
        $method = get_request_method();
        if ($method == "POST") {
            $data = json_decode(file_get_contents("php://input"));
            if (json_last_error() != JSON_ERROR_NONE) {
                http_response_code(StatusCode::HTTP_BAD_REQUEST);
                return;
            }
            $err = validate_project($data);
            if ($err != null) {
                json_resp($err);
                http_response_code(StatusCode::HTTP_BAD_REQUEST);
                return;
            }
            project_create($data);
            http_response_code(StatusCode::HTTP_CREATED);
        } else if ($method == "GET") {
            $arr = projects_read_all();
            json_resp($arr);
        }
    } catch (Exception $e) {
        http_response_code(StatusCode::HTTP_INTERNAL_SERVER_ERROR);
    }
}

function handle_project($p_id)
{
    try {
        $method = get_request_method();
        if ($method == "GET") {
            $item = project_read($p_id);
            json_resp($item);
        } else if ($method == "PUT") {
            $data = json_decode(file_get_contents("php://input"));
            if (json_last_error() != JSON_ERROR_NONE) {
                http_response_code(StatusCode::HTTP_BAD_REQUEST);
                return;
            }
            $err = validate_project($data);
            if ($err != null) {
                json_resp($err);
                http_response_code(StatusCode::HTTP_BAD_REQUEST);
                return;
            }
            project_update($p_id, $data);
        } else if ($method == "DELETE") {
            project_delete($p_id);
            http_response_code(StatusCode::HTTP_NO_CONTENT);
        }
    } catch (Exception $e) {
        http_response_code(StatusCode::HTTP_INTERNAL_SERVER_ERROR);
    }
}

function handle_project_tasks($p_id)
{
    try {
        $method = get_request_method();
        if ($method == "POST") {
            $data = json_decode(file_get_contents("php://input"));
            if (json_last_error() != JSON_ERROR_NONE) {
                http_response_code(StatusCode::HTTP_BAD_REQUEST);
                return;
            }
            $err = validate_task($data, false);
            if ($err != null) {
                json_resp($err);
                http_response_code(StatusCode::HTTP_BAD_REQUEST);
                return;
            }
            project_task_create($p_id, $data);
            http_response_code(StatusCode::HTTP_CREATED);
        } else if ($method == "GET") {
            $arr = project_tasks_read_all($p_id);
            json_resp($arr);
        }
    } catch (Exception $e) {
        http_response_code(StatusCode::HTTP_INTERNAL_SERVER_ERROR);
    }
}

function handle_task($t_id)
{
    try {
        $method = get_request_method();
        if ($method == "GET") {
            $item = task_read($t_id);
            json_resp($item);
        } else if ($method == "PUT") {
            $data = json_decode(file_get_contents("php://input"));
            if (json_last_error() != JSON_ERROR_NONE) {
                http_response_code(StatusCode::HTTP_BAD_REQUEST);
                return;
            }
            $err = validate_task($data, true);
            if ($err != null) {
                json_resp($err);
                http_response_code(StatusCode::HTTP_BAD_REQUEST);
                return;
            }
            task_update($t_id, $data);
        } else if ($method == "DELETE") {
            task_delete($t_id);
            http_response_code(StatusCode::HTTP_NO_CONTENT);
        }
    } catch (Exception $e) {
        http_response_code(StatusCode::HTTP_INTERNAL_SERVER_ERROR);
    }
}
