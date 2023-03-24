<?php

require_once "../bootstrap.php";

require_once '../dal/ProjectsDao.php';
require_once '../dal/TasksDao.php';
require_once '../dal/Task.php';


class ProjectTasksController
{
    /**
     * @throws Exception
     */
    public static function createTask($p_id, $data)
    {
        $dao = new TasksDao(ds());
        $t = new Task();
        $t->set_p_id($p_id);
        $t_date = date("Y-m-d H:i:s");
        $t->set_t_date($t_date);
        $t->set_t_subject($data->t_subject);
        $t->set_t_priority(1);
        $t->set_t_comments("");
        $dao->create_task($t);
    }

    /**
     * @throws Exception
     */
    public static function readProjectTasks($p_id): array
    {
        $dao = new TasksDao(ds());
        $tasks = $dao->get_project_tasks($p_id);
        $arr = array();
        foreach ($tasks as $t) {
            $item = array(
                "t_id" => $t->get_t_id(),
                "t_subject" => $t->get_t_subject(),
                "t_date" => $t->get_t_date(),
                "t_priority" => $t->get_t_priority(),
            );
            array_push($arr, $item);
        }
        return $arr;
    }
}