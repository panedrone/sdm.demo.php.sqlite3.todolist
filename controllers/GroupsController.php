<?php

require_once "../bootstrap.php";

require_once '../dal/GroupsDao.php';
require_once '../dal/Group.php';

class GroupsController
{
    public static function createGroup($data)
    {
        $dao = new GroupsDao(ds());
        $gr = new Group();
        $gr->setGName($data->g_name);
        $dao->createGroup($gr);
    }

    public static function readGroups(): array
    {
        $dao = new GroupsDao(ds());
        $groups = $dao->getGroups();
        $arr = array();
        foreach ($groups as $gr) {
            $item = array(
                "g_id" => $gr->getGId(),
                "g_name" => $gr->getGName(),
                "tasks_count" => $gr->getTasksCount(),
            );
            array_push($arr, $item);
        }
        return $arr;
    }

    public static function readGroup($g_id): array
    {
        $dao = new GroupsDao(ds());
        $gr = $dao->readGroup($g_id);
        $item = array(
            "g_id" => $gr->getGId(),
            "g_name" => $gr->getGName(),
            "tasks_count" => $gr->getTasksCount(),
        );
        return $item;
    }

    public static function updateGroup($g_id, $data)
    {
        $dao = new GroupsDao(ds());
        $gr = new Group();
        $gr->setGId($g_id);
        $gr->setGName($data->g_name);
        $dao->updateGroup($gr);
    }

    public static function deleteGroup($g_id)
    {
        $dao = new GroupsDao(ds());
        $dao->deleteGroup($g_id);
    }
}