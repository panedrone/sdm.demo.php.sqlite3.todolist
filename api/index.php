<?php

require_once '../controllers/GroupsController.php';
require_once '../controllers/GroupTasksController.php';
require_once '../controllers/TaskController.php';

require_once 'utils.php';

require_once 'Route.php';

$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$uri = explode('/', $uri);

if (isset($uri[3])) {
    $method = filter_input(INPUT_SERVER, 'REQUEST_METHOD', FILTER_SANITIZE_SPECIAL_CHARS);
    $api = $uri[3];
    if ($api === 'groups') {
        handle_groups($uri, $method);
        return;
    } else if ($api === 'task') {
        handle_task($uri, $method);
        return;
    }
}

// https://github.com/symfony/http-foundation/blob/5.4/Response.php

class Response
{
    const HTTP_CREATED = 201;
    const HTTP_NO_CONTENT = 204;

    const HTTP_BAD_REQUEST = 400;
    const HTTP_NOT_FOUND = 404;
}

header("HTTP/1.1 404 Not Found");
http_response_code(Response::HTTP_NOT_FOUND);

function handle_groups($uri, $method)
{
    if (isset($uri[4])) {
        $g_id = (int)$uri[4];
        if (isset($uri[5]) && $uri[5] === 'tasks') {
            handle_group_tasks($g_id, $method);
        } else {
            handle_group($g_id, $method);
        }
        return;
    }
    if ($method == "POST") {
        $data = json_decode(file_get_contents("php://input"));
        GroupsController::createGroup($data);
        http_response_code(Response::HTTP_CREATED);
    } else if ($method == "GET") {
        $arr = GroupsController::getGroups();
        json_resp($arr);
    } else {
        http_response_code(Response::HTTP_BAD_REQUEST);
    }
}

function handle_group($g_id, $method)
{
    if ($method == "GET") {
        $item = GroupsController::readGroup($g_id);
        json_resp($item);
    } else if ($method == "PUT") {
        $data = json_decode(file_get_contents("php://input"));
        GroupsController::updateGroup($g_id, $data);
    } else if ($method == "DELETE") {
        GroupsController::deleteGroup($g_id);
        http_response_code(Response::HTTP_NO_CONTENT);
    } else {
        http_response_code(Response::HTTP_BAD_REQUEST);
    }
}

function handle_group_tasks($g_id, $method)
{
    if ($method == "POST") {
        $data = json_decode(file_get_contents("php://input"));
        GroupTasksController::createTask($g_id, $data);
        http_response_code(Response::HTTP_CREATED);
    } else if ($method == "GET") {
        $arr = GroupTasksController::getGroupTasks($g_id);
        json_resp($arr);
    } else {
        http_response_code(Response::HTTP_BAD_REQUEST);
    }
}

function handle_task($uri, $method)
{
    if (isset($uri[4])) {
        $t_id = (int)$uri[4];
        if ($method == "GET") {
            $item = TaskController::readTask($t_id);
            json_resp($item);
        } else if ($method == "PUT") {
            $data = json_decode(file_get_contents("php://input"));
            TaskController::updateTask($t_id, $data);
        } else if ($method == "DELETE") {
            TaskController::deleteTask($t_id);
            http_response_code(Response::HTTP_NO_CONTENT);
        } else {
            http_response_code(Response::HTTP_BAD_REQUEST);
        }
    } else {
        http_response_code(Response::HTTP_BAD_REQUEST);
    }
}

