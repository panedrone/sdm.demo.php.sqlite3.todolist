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
$t_id = filter_input(INPUT_GET, "t_id", FILTER_SANITIZE_NUMBER_INT);
if (!$t_id) {
    echo 't_id not specified';
    exit(1);
}
$t_subject = filter_input(INPUT_POST, "t_subject", FILTER_SANITIZE_STRING);
if (!$t_subject) {
    echo 't_subject not specified';
    exit(1);
}
$t_date = filter_input(INPUT_POST, "t_date", FILTER_SANITIZE_STRING);
if (!$t_date) {
    echo 't_date not specified';
    exit(1);
}
$t_priority = filter_input(INPUT_POST, "t_priority", FILTER_SANITIZE_NUMBER_INT);
if (!$t_priority) {
    echo 't_priority not specified';
    exit(1);
}
$t_comments = filter_input(INPUT_POST, "t_comments", FILTER_SANITIZE_STRING);
if (!$t_date) {
    echo 't_comments not specified';
    exit(1);
}
$t = new \dto\Task();
$t->setTDate($t_date);
$t->setTSubject($t_subject);
$t->setTPriority($t_priority);
$t->setTComments($t_comments);
$t->setGId($g_id);
$t->setTId($t_id);
$ds = new DataStore();
$ds->open();
$task_dao = new dao\TasksDao($ds);
$task_dao->updateTask($t);
$ds->close();
MyUtils::redirect("index.php?g_id=$g_id&t_id=$t_id");
