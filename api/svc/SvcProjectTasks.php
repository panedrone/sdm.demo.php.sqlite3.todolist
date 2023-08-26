<?php

require_once "bootstrap.php";

require_once 'dal/Task.php';


/**
 * @throws Exception
 */
function project_task_create($p_id, $data)
{
    $t = new Task();
    $t->set_p_id($p_id);
    $t_date = date("Y-m-d H:i:s");
    $t->set_t_date($t_date);
    $t->set_t_subject($data->t_subject);
    $t->set_t_priority(1);
    $t->set_t_comments("");
    tasks_dao()->create_task($t);
}

/**
 * @throws Exception
 */
function project_tasks_read_all($p_id): array
{
    $tasks = tasks_dao()->get_project_tasks($p_id);
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
