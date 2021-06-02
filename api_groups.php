<?php

define("ABS_PATH", dirname(__FILE__));

include_once ABS_PATH . '/dal/DataStore.php';
include_once ABS_PATH . '/dal/GroupsDao.php';
include_once ABS_PATH . '/dal/Group.php';

$ds = new DataStore();
$ds->open();
$dao = new dao\GroupsDao($ds);

$method = filter_input(INPUT_SERVER, 'REQUEST_METHOD', FILTER_SANITIZE_SPECIAL_CHARS);

$g_id = filter_input(INPUT_GET, "g_id", FILTER_SANITIZE_NUMBER_INT);
if ($g_id) {
    if ($method == "GET") {
        $gr = $dao->readGroup($g_id);
        $item = array(
            "g_id" => $gr->getGId(),
            "g_name" => $gr->getGName(),
            // "tasks_count" => html_entity_decode($description),
            "tasks_count" => $gr->getTasksCount(),
        );
        // http_response_code(200);
        echo json_encode($item);
    } else if ($method == "PUT") {
        $data = json_decode(file_get_contents("php://input"));
        $gr = new dto\Group();
        $gr->setGId($g_id);
        $gr->setGName($data->g_name);
        $dao->updateGroup($gr);
        //http_response_code(200);
    } else if ($method == "DELETE") {
        $dao->deleteGroup($g_id);
        //http_response_code(200);
    } else {
        http_response_code(400); // bad request
    }
    return;
}
if ($method == "POST") {
    $data = json_decode(file_get_contents("php://input"));
    $gr = new dto\Group();
    $gr->setGName($data->g_name);
    $dao->createGroup($gr);
    //http_response_code(200);
    return;
}
$groups = $dao->getGroups();
$arr = array();
foreach ($groups as $gr) {
    $item = array(
        "g_id" => $gr->getGId(),
        "g_name" => $gr->getGName(),
        // "tasks_count" => html_entity_decode($description),
        "tasks_count" => $gr->getTasksCount(),
    );
    array_push($arr, $item);
}
//http_response_code(200);
echo json_encode($arr);
