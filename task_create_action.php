<?php

define("ABS_PATH", dirname(__FILE__));

include_once ABS_PATH . '/dal/DataStore.php';
include_once ABS_PATH . '/dal/TasksDao.php';
include_once ABS_PATH . '/MyUtils.php';

$g_id = filter_input(INPUT_GET, "g_id", FILTER_SANITIZE_NUMBER_INT);
if (!$g_id) {
    echo 'g_id not specified';
    exit(1);
}
$t_subject = filter_input(INPUT_POST, "t_subject_1", FILTER_SANITIZE_STRING);
if (!$t_subject) {
    echo 't_subject not specified';
    exit(1);
}
$t_date = date("Y-m-d H:i:s");
$t_priority = 1;

$t = new \dto\Task();
$t->setTDate($t_date);
$t->setTSubject($t_subject);
$t->setTPriority($t_priority);
$t->setTComments("");
$t->setGId($g_id);
$ds = new DataStore();
$ds->open();
$task_dao = new dao\TasksDao($ds);
$task_dao->createTask($t);
$ds->close();
MyUtils::redirect('index.php?g_id=' . $g_id);
