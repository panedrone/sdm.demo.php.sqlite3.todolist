<?php

define("ABS_PATH", dirname(__FILE__));

include_once ABS_PATH . '/dal/DataStore.php';
include_once ABS_PATH . '/dal/GroupsDao.php';

$g_id = NULL;

if (array_key_exists("g_id", $_GET)) {

    $g_id = $_GET ["g_id"];
}

if (!empty($_POST)) {
    // keep track validation errors
    $g_name_error = null;

    // keep track post values
    $g_name = $_POST['g_name'];

    // validate input
    $valid = true;

    if (empty($g_name)) {
        $g_name_error = 'Please enter Name';
        $valid = false;
    }

    // insert data
    if ($valid) {
        $gr = new \dto\Group();
        $gr->setG_id($g_id);
        $gr->setG_name($g_name);

        $ds = new DataStore();
        $ds->open();

        $group_dao = new dao\GroupsDao($ds);

        $group_dao->updateGroup($gr);

        $ds->close();
    }
}