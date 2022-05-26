<?php

require_once '../controllers/GroupsController.php';
require_once '../controllers/GroupTasksController.php';
require_once '../controllers/TaskController.php';

// https://developer.okta.com/blog/2019/03/08/simple-rest-api-php

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: OPTIONS,GET,POST,PUT,DELETE");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

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

header("HTTP/1.1 404 Not Found");
http_response_code(404);

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
    } else if ($method == "GET") {
        $arr = GroupsController::getGroups();
        echo json_encode($arr);
    } else {
        http_response_code(400); // bad request
    }
}

function handle_group($g_id, $method)
{
    if ($method == "GET") {
        $item = GroupsController::readGroup($g_id);
        echo json_encode($item);
    } else if ($method == "PUT") {
        $data = json_decode(file_get_contents("php://input"));
        GroupsController::updateGroup($g_id, $data);
    } else if ($method == "DELETE") {
        GroupsController::deleteGroup($g_id);
    } else {
        http_response_code(400); // bad request
    }
}

function handle_group_tasks($g_id, $method)
{
    if ($method == "POST") {
        $data = json_decode(file_get_contents("php://input"));
        GroupTasksController::createTask($g_id, $data);
    } else if ($method == "GET") {
        $arr = GroupTasksController::getGroupTasks($g_id);
        echo json_encode($arr);
    } else {
        http_response_code(400); // bad request
    }
}

function handle_task($uri, $method)
{
    if (isset($uri[4])) {
        $t_id = (int)$uri[4];
        if ($method == "GET") {
            $item = TaskController::readTask($t_id);
            echo json_encode($item);
        } else if ($method == "PUT") {
            $data = json_decode(file_get_contents("php://input"));
            TaskController::updateTask($t_id, $data);
        } else if ($method == "DELETE") {
            TaskController::deleteTask($t_id);
        } else {
            http_response_code(400); // bad request
        }
    } else {
        http_response_code(400); // bad request
    }
}

