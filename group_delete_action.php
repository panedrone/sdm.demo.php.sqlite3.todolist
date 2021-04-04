<?php

define("ABS_PATH", dirname(__FILE__));

include_once ABS_PATH . '/dal/DataStore.php';
include_once ABS_PATH . '/dal/GroupsDao.php';
include_once ABS_PATH . '/MyUtils.php';

$g_id = filter_input(INPUT_GET, "g_id", FILTER_SANITIZE_NUMBER_INT);
if (!$g_id) {
    echo 'g_id not specified';
    exit(1);
}
$ds = new DataStore();
$ds->open();
$dao = new dao\GroupsDao($ds);
$dao->deleteTasks($g_id);
$dao->deleteGroup($g_id);
$ds->close();
MyUtils::redirect('index.php');

