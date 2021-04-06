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
$g_name = filter_input(INPUT_POST, "g_name", FILTER_SANITIZE_STRING);
if (!$g_name) {
    echo 'g_name not specified';
    exit(1);
}
$gr = new \dto\Group();
$gr->setGId($g_id);
$gr->setGName($g_name);
$ds = new DataStore();
$ds->open();
$group_dao = new dao\GroupsDao($ds);
$group_dao->updateGroup($gr);
$ds->close();
MyUtils::redirect("index.php?g_id=$g_id");
