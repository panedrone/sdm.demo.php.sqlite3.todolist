<?php

include_once 'bootstrap.php';
include_once './dal/GroupsDao.php';
include_once './dal/Group.php';

$dao = new GroupsDao(ds());

$method = filter_input(INPUT_SERVER, 'REQUEST_METHOD', FILTER_SANITIZE_SPECIAL_CHARS);

$g_id = filter_input(INPUT_GET, "g_id", FILTER_SANITIZE_NUMBER_INT);
if ($g_id) {
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
    return;
}
if ($method == "POST") {
    $data = json_decode(file_get_contents("php://input"));
    $gr = new Group();
    $gr->setGName($data->g_name);
    $dao->createGroup($gr);
    return;
}
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
