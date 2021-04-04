<?php

define("ABS_PATH", dirname(__FILE__));

include_once ABS_PATH . '/dal/DataStore.php';
include_once ABS_PATH . '/dal/TasksDao.php';
include_once ABS_PATH . '/MyUtils.php';

$t_id = filter_input(INPUT_GET, "t_id", FILTER_SANITIZE_NUMBER_INT);
if (!$t_id) {
    echo 't_id not specified';
    exit(1);
}
$ds = new DataStore();
$ds->open();
$dao = new dao\TasksDao($ds);
$t = $dao->readTask($t_id);
$g_id = $t->getGId();
$dao->deleteTask($t_id);
$ds->close();
MyUtils::redirect('index.php?g_id=' . $g_id);

