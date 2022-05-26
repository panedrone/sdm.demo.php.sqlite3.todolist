<?php

require "../bootstrap.php";

include_once '../dal/GroupsDao.php';
include_once '../dal/TasksDao.php';
include_once '../dal/Group.php';
include_once '../dal/Task.php';

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: OPTIONS,GET,POST,PUT,DELETE");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$uri = explode('/', $uri);

function handle_groups($uri)
{
    $method = filter_input(INPUT_SERVER, 'REQUEST_METHOD', FILTER_SANITIZE_SPECIAL_CHARS);
    if (isset($uri[4])) {
        $g_id = (int)$uri[4];
        if (isset($uri[5]) && $uri[5] === 'tasks') {
            handle_group_tasks($g_id);
            return;
        }
        handle_group($g_id);
        return;
    }
    $dao = new GroupsDao(ds());
    if ($method == "POST") {
        $data = json_decode(file_get_contents("php://input"));
        $gr = new Group();
        $gr->setGName($data->g_name);
        $dao->createGroup($gr);
    } else if ($method == "GET") {
        $groups = $dao->getGroups();
        $arr = array();
        foreach ($groups as $gr) {
            $item = array(
                "g_id" => $gr->getGId(),
                "g_name" => $gr->getGName(),
                // "g_name" => html_entity_decode($description),
                "tasks_count" => $gr->getTasksCount(),
            );
            array_push($arr, $item);
        }
        echo json_encode($arr);
    } else {
        http_response_code(400); // bad request
    }
}

function handle_group($g_id)
{
    $method = filter_input(INPUT_SERVER, 'REQUEST_METHOD', FILTER_SANITIZE_SPECIAL_CHARS);
    $dao = new GroupsDao(ds());
    if ($method == "GET") {
        $gr = $dao->readGroup($g_id);
        $item = array(
            "g_id" => $gr->getGId(),
            "g_name" => $gr->getGName(),
            // "g_name" => html_entity_decode($description),
            "tasks_count" => $gr->getTasksCount(),
        );
        echo json_encode($item);
    } else if ($method == "PUT") {
        $data = json_decode(file_get_contents("php://input"));
        $gr = new Group();
        $gr->setGId($g_id);
        $gr->setGName($data->g_name);
        $dao->updateGroup($gr);
        //http_response_code(200);
    } else if ($method == "DELETE") {
        $dao->deleteGroup($g_id);
    } else {
        http_response_code(400); // bad request
    }
}

function handle_group_tasks($g_id)
{
    $dao = new TasksDao(ds());
    $method = filter_input(INPUT_SERVER, 'REQUEST_METHOD', FILTER_SANITIZE_SPECIAL_CHARS);
    if ($method == "POST") {
        $data = json_decode(file_get_contents("php://input"));
        $t = new Task();
        $t->setGId($g_id);
        $t_date = date("Y-m-d H:i:s");
        $t->setTDate($t_date);
        $t->setTSubject($data->t_subject);
        $t->setTPriority(1);
        $t->setTComments("");
        $dao->createTask($t);
    } else if ($method == "GET") {
        $tasks = $dao->getGroupTasks($g_id);
        $arr = array();
        foreach ($tasks as $t) {
            $item = array(
                "t_id" => $t->getTId(),
                "t_subject" => $t->getTSubject(),
                // "tasks_count" => html_entity_decode($description),
                "t_date" => $t->getTDate(),
                "t_priority" => $t->getTPriority(),
                // "t_comments" => $t->getTComments(),  no comments on get task list
            );
            array_push($arr, $item);
        }
        echo json_encode($arr);
    } else {
        http_response_code(400); // bad request
    }
}

function handle_task($uri)
{
    if (isset($uri[4])) {
        $t_id = (int)$uri[4];
        $dao = new TasksDao(ds());
        $method = filter_input(INPUT_SERVER, 'REQUEST_METHOD', FILTER_SANITIZE_SPECIAL_CHARS);
        if ($method == "GET") {
            $t = $dao->readTask($t_id);
            $item = array(
                "t_id" => $t->getTId(),
                "t_subject" => $t->getTSubject(),
                // "t_subject" => html_entity_decode($description),
                "t_date" => $t->getTDate(),
                "t_priority" => $t->getTPriority(),
                "t_comments" => $t->getTComments(),
            );
            echo json_encode($item);
        } else if ($method == "PUT") {
            $data = json_decode(file_get_contents("php://input"));
            $t = $dao->readTask($t_id);
            $t->setTDate($data->t_date);
            $t->setTSubject($data->t_subject);
            $t->setTPriority($data->t_priority);
            $t->setTComments($data->t_comments);
            $dao->updateTask($t);
        } else if ($method == "DELETE") {
            $dao->deleteTask($t_id);
        } else {
            http_response_code(400); // bad request
        }
    } else {
        http_response_code(400); // bad request
    }
}

if (isset($uri[3])) {
    $api = $uri[3];
    if ($api === 'groups') {
        handle_groups($uri);
        return;
    } else if ($api === 'task') {
        handle_task($uri);
        return;
    }
}

header("HTTP/1.1 404 Not Found");
http_response_code(404);
