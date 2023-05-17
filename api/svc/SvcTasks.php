<?php

require_once "bootstrap.php";

require_once 'dal/TasksDao.php';

/**
 * @throws Exception
 */
function task_read($t_id): array
{
    $dao = new TasksDao(ds());
    $t = $dao->read_task($t_id);
    $item = array(
        "t_id" => $t->get_t_id(),
        "t_subject" => $t->get_t_subject(),
        "t_date" => $t->get_t_date(),
        "t_priority" => $t->get_t_priority(),
        "t_comments" => $t->get_t_comments(),
    );
    return $item;
}

/**
 * @throws Exception
 */
function task_update($t_id, $data)
{
    $dao = new TasksDao(ds());
    $t = $dao->read_task($t_id);
    $t->set_t_date($data->t_date);
    $t->set_t_subject($data->t_subject);
    $t->set_t_priority($data->t_priority);
    $t->set_t_comments($data->t_comments);
    $dao->update_task($t);
}

/**
 * @throws Exception
 */
function task_delete($t_id)
{
    $dao = new TasksDao(ds());
    $dao->delete_task($t_id);
}
