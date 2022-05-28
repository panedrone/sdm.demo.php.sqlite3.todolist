<?php

require_once "../bootstrap.php";

require_once '../dal/GroupsDao.php';
require_once '../dal/TasksDao.php';
require_once '../dal/Task.php';


class GroupTasksController
{
    public static function createTask($g_id, $data)
    {
        $dao = new TasksDao(ds());
        $t = new Task();
        $t->setGId($g_id);
        $t_date = date("Y-m-d H:i:s");
        $t->setTDate($t_date);
        $t->setTSubject($data->t_subject);
        $t->setTPriority(1);
        $t->setTComments("");
        $dao->createTask($t);
    }

    public static function readGroupTasks($g_id): array
    {
        $dao = new TasksDao(ds());
        $tasks = $dao->getGroupTasks($g_id);
        $arr = array();
        foreach ($tasks as $t) {
            $item = array(
                "t_id" => $t->getTId(),
                "t_subject" => $t->getTSubject(),
                "t_date" => $t->getTDate(),
                "t_priority" => $t->getTPriority(),
            );
            array_push($arr, $item);
        }
        return $arr;
    }
}