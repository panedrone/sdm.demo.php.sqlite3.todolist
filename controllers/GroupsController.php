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
        $gr->set_g_name($data->g_name);
        $dao->create_group($gr);
    }

    public static function readGroups(): array
    {
        $dao = new GroupsDao(ds());
        $groups = $dao->get_groups();
        $arr = array();
        foreach ($groups as $gr) {
            $item = array(
                "g_id" => $gr->get_g_id(),
                "g_name" => $gr->get_g_name(),
                "tasks_count" => $gr->get_tasks_count(),
            );
            array_push($arr, $item);
        }
        return $arr;
    }

    public static function readGroup($g_id): array
    {
        $dao = new GroupsDao(ds());
        $gr = $dao->read_group($g_id);
        $item = array(
            "g_id" => $gr->get_g_id(),
            "g_name" => $gr->get_g_name(),
        );
        return $item;
    }

    public static function updateGroup($g_id, $data)
    {
        $dao = new GroupsDao(ds());
        $gr = new Group();
        $gr->set_g_id($g_id);
        $gr->set_g_name($data->g_name);
        $dao->update_group($gr);
    }

    public static function deleteGroup($g_id)
    {
        $dao = new GroupsDao(ds());
        $dao->delete_group($g_id);
    }
}