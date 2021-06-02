<?php

define("ABS_PATH", dirname(__FILE__));

include_once './dal/DataStore.php';
//include_once ABS_PATH . '/dal/DataStore.php';
include_once ABS_PATH . '/dal/TasksDao.php';
include_once ABS_PATH . '/dal/Task.php';

$ds = new DataStore();
$ds->open();
$dao = new dao\TasksDao($ds);

$method = filter_input(INPUT_SERVER, 'REQUEST_METHOD', FILTER_SANITIZE_SPECIAL_CHARS);
$t_id = filter_input(INPUT_GET, "t_id", FILTER_SANITIZE_NUMBER_INT);
if ($t_id) {
    if ($method == "GET") {
        $t = $dao->readTask($t_id);
        $item = array(
            "t_id" => $t->getTId(),
            "t_subject" => $t->getTSubject(),
            // "tasks_count" => html_entity_decode($description),
            "t_date" => $t->getTDate(),
            "t_priority" => $t->getTPriority(),
            "t_comments" => $t->getTComments(),
        );
        //http_response_code(200);
        echo json_encode($item);
    } else if ($method == "PUT") {
        $data = json_decode(file_get_contents("php://input"));
        $t = $dao->readTask($t_id);
        $t->setTDate($data->t_date);
        $t->setTSubject($data->t_subject);
        $t->setTPriority($data->t_priority);
        $t->setTComments($data->t_comments);
        $dao->updateTask($t);
        //http_response_code(200);
    } else if ($method == "DELETE") {
        $dao->deleteTask($t_id);
        //http_response_code(200);
    } else {
        http_response_code(400); // bad request
    }
    return;
}
$g_id = filter_input(INPUT_GET, "g_id", FILTER_SANITIZE_NUMBER_INT);
if (!$g_id) {
    http_response_code(400);
    return;
}
if ($method == "POST") {
    $data = json_decode(file_get_contents("php://input"));
    $t = new dto\Task();
    $t->setGId($g_id);
    $t_date = date("Y-m-d H:i:s");
    $t->setTDate($t_date);
    $t->setTSubject($data->t_subject);
    $t->setTPriority(1);
    $t->setTComments("");
    $dao->createTask($t);
    return;
}
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
// http_response_code(200);
echo json_encode($arr);
