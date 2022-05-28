<?php

require_once '../controllers/GroupsController.php';
require_once '../controllers/GroupTasksController.php';
require_once '../controllers/TaskController.php';

require_once 'utils.php';

$method = filter_input(INPUT_SERVER, 'REQUEST_METHOD', FILTER_SANITIZE_SPECIAL_CHARS);

// https://github.com/symfony/http-foundation/blob/5.4/Response.php

class Response
{
    const HTTP_CREATED = 201;
    const HTTP_NO_CONTENT = 204;

    const HTTP_BAD_REQUEST = 400;
    const HTTP_NOT_FOUND = 404;
    const HTTP_METHOD_NOT_ALLOWED = 405;
}


function handle_groups()
{
    global $method;
    if ($method == "POST") {
        $data = json_decode(file_get_contents("php://input"));
        GroupsController::createGroup($data);
        http_response_code(Response::HTTP_CREATED);
    } else if ($method == "GET") {
        $arr = GroupsController::getGroups();
        json_resp($arr);
    }
}

function handle_group($g_id)
{
    global $method;
    if ($method == "GET") {
        $item = GroupsController::readGroup($g_id);
        json_resp($item);
    } else if ($method == "PUT") {
        $data = json_decode(file_get_contents("php://input"));
        GroupsController::updateGroup($g_id, $data);
    } else if ($method == "DELETE") {
        GroupsController::deleteGroup($g_id);
        http_response_code(Response::HTTP_NO_CONTENT);
    }
}

function handle_group_tasks($g_id)
{
    global $method;
    if ($method == "POST") {
        $data = json_decode(file_get_contents("php://input"));
        GroupTasksController::createTask($g_id, $data);
        http_response_code(Response::HTTP_CREATED);
    } else if ($method == "GET") {
        $arr = GroupTasksController::getGroupTasks($g_id);
        json_resp($arr);
    }
}

function handle_task($t_id)
{
    global $method;
    if ($method == "GET") {
        $item = TaskController::readTask($t_id);
        json_resp($item);
    } else if ($method == "PUT") {
        $data = json_decode(file_get_contents("php://input"));
        TaskController::updateTask($t_id, $data);
    } else if ($method == "DELETE") {
        TaskController::deleteTask($t_id);
        http_response_code(Response::HTTP_NO_CONTENT);
    }
}

