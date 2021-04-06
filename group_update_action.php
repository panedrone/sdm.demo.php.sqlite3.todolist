<?php

define("ABS_PATH", dirname(__FILE__));

include_once ABS_PATH . '/dal/DataStore.php';
include_once ABS_PATH . '/dal/GroupsDao.php';

$g_id = filter_input(INPUT_GET, "g_id", FILTER_SANITIZE_NUMBER_INT);
if (!$g_id) {
    echo 'g_id not specified';
    exit(1);
}
$g_name = filter_input(INPUT_POST, "g_name", FILTER_SANITIZE_STRING);
if (!$t_subject) {
    echo 'g_name not specified';
    exit(1);
}
$gr = new \dto\Group();
$gr->setG_id($g_id);
$gr->setG_name($g_name);
$ds = new DataStore();
$ds->open();
$group_dao = new dao\GroupsDao($ds);
$group_dao->updateGroup($gr);
$ds->close();

