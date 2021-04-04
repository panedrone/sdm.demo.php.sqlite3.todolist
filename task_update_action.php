<?php

define("ABS_PATH", dirname(__FILE__));

include_once ABS_PATH . '/dal/DataStore.php';
include_once ABS_PATH . '/dal/TasksDao.php';

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

if (empty($_POST)) {
    echo 'empty($_POST)';
    exit(1);
}
// keep track validation errors
$t_date_error = null;
$t_subject_error = null;
$t_priority_error = null;
$t_comments_error = null;
$g_id_error = null;

// keep track post values
$t_date = $_POST['t_date'];
$t_subject = $_POST['t_subject'];
$t_priority = $_POST['t_priority'];
$t_comments = $_POST['t_comments'];

$valid = true;

if (empty($t_date)) {
    $t_date_error = 'Please enter Date';
    $valid = false;
}

if (empty($t_subject)) {
    $t_subject_error = 'Please enter Subject';
    $valid = false;
}

if (empty($t_priority)) {
    $t_priority_error = 'Please enter Priority';
    $valid = false;
}

if (empty($t_comments)) {
    $t_comments_error = 'Please enter Comments';
    $valid = false;
}

// insert data
if ($valid) {
    $t = new \dto\Task();
    $t->setT_date($t_date);
    $t->setT_subject($t_subject);
    $t->setT_priority($t_priority);
    $t->setT_comments($t_comments);
    $t->setG_id($g_id);
    $t->setT_id($t_id);

    $ds = new DataStore();
    $ds->open();
    $task_dao = new dao\TasksDao($ds);
    $task_dao->updateTask($t);
    $ds->close();
}
