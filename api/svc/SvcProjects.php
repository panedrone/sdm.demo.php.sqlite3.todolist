<?php

require_once "bootstrap.php";

require_once 'dal/ProjectsDao.php';
require_once 'dal/Project.php';

/**
 * @throws Exception
 */
function project_create($data)
{
    $dao = new ProjectsDao(ds());
    $gr = new Project();
    $gr->set_p_name($data->p_name);
    $dao->create_project($gr);
}

/**
 * @throws Exception
 */
function projects_read_all(): array
{
    $dao = new ProjectsDao(ds());
    $projects = $dao->get_projects();
    $arr = array();
    foreach ($projects as $gr) {
        $item = array(
            "p_id" => $gr->get_p_id(),
            "p_name" => $gr->get_p_name(),
            "p_tasks_count" => $gr->get_p_tasks_count(),
        );
        array_push($arr, $item);
    }
    return $arr;
}

/**
 * @throws Exception
 */
function project_read($p_id): array
{
    $dao = new ProjectsDao(ds());
    $gr = $dao->read_project($p_id);
    return array(
        "p_id" => $gr->get_p_id(),
        "p_name" => $gr->get_p_name(),
    );
}

/**
 * @throws Exception
 */
function project_update($p_id, $data)
{
    $dao = new ProjectsDao(ds());
    $gr = new Project();
    $gr->set_p_id($p_id);
    $gr->set_p_name($data->p_name);
    $dao->update_project($gr);
}

/**
 * @throws Exception
 */
function project_delete($p_id)
{
    $dao = new ProjectsDao(ds());
    $dao->delete_project($p_id);
}