<?php

require_once "../bootstrap.php";

require_once '../dal/TasksDao.php';

class TaskController
{
    public static function readTask($t_id): array
    {
        $dao = new TasksDao(ds());
        $t = $dao->readTask($t_id);
        $item = array(
            "t_id" => $t->getTId(),
            "t_subject" => $t->getTSubject(),
            "t_date" => $t->getTDate(),
            "t_priority" => $t->getTPriority(),
            "t_comments" => $t->getTComments(),
        );
        return $item;
    }

    public static function updateTask($t_id, $data)
    {
        $dao = new TasksDao(ds());
        $t = $dao->readTask($t_id);
        $t->setTDate($data->t_date);
        $t->setTSubject($data->t_subject);
        $t->setTPriority($data->t_priority);
        $t->setTComments($data->t_comments);
        $dao->updateTask($t);
    }

    public static function deleteTask($t_id)
    {
        $dao = new TasksDao(ds());
        $dao->deleteTask($t_id);
    }
}