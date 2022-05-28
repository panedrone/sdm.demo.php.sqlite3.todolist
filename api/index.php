<?php

require_once '../controllers/GroupsController.php';
require_once '../controllers/GroupTasksController.php';
require_once '../controllers/TaskController.php';

require_once 'utils.php';

require_once 'Route.php';

use Steampixel\Route;

$method = filter_input(INPUT_SERVER, 'REQUEST_METHOD', FILTER_SANITIZE_SPECIAL_CHARS);

Route::add('/api/groups', function () {
    handle_groups();
}, ['get', 'post']);

Route::add('/api/groups/([0-9]*)', function ($g_id) {
    handle_group($g_id);
}, ['get', 'put', 'delete']);

Route::add('/api/groups/([0-9]*)/tasks', function ($g_id) {
    handle_group_tasks($g_id);
}, ['get', 'post']);

Route::add('/api/task/([0-9]*)', function ($t_id) {
    handle_task($t_id);
}, ['get', 'put', 'delete']);

// https://github.com/symfony/http-foundation/blob/5.4/Response.php

class Response
{
    const HTTP_CREATED = 201;
    const HTTP_NO_CONTENT = 204;

    const HTTP_BAD_REQUEST = 400;
    const HTTP_NOT_FOUND = 404;
    const HTTP_METHOD_NOT_ALLOWED = 405;
}

Route::methodNotAllowed(function ($path, $method) {
    http_response_code(Response::HTTP_METHOD_NOT_ALLOWED);
});

Route::pathNotFound(function ($path, $method) {
    header("HTTP/1.1 404 Not Found");
    http_response_code(Response::HTTP_NOT_FOUND);
});

$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$uri_parts = explode('/api/', $uri);
if (count($uri_parts) > 0) {
    $base = $uri_parts[0];
} else {
    $base = "/";
}

Route::run($base);

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
    } else {
        http_response_code(Response::HTTP_BAD_REQUEST);
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
    } else {
        http_response_code(Response::HTTP_BAD_REQUEST);
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
    } else {
        http_response_code(Response::HTTP_BAD_REQUEST);
    }
}

