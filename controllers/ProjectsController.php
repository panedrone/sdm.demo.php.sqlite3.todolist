<?php

require_once "../bootstrap.php";

require_once '../dal/ProjectsDao.php';
require_once '../dal/Project.php';

class ProjectsController
{
    /**
     * @throws Exception
     */
    public static function createProject($data): ?string
    {
        $dao = new ProjectsDao(ds());
        $gr = new Project();
        if (strlen(trim($data->p_name)) == 0) {
            return "Project name not set";
        }
        $gr->set_p_name($data->p_name);
        $dao->create_project($gr);
        return null;
    }

    /**
     * @throws Exception
     */
    public static function readProjects(): array
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
    public static function readProject($p_id): array
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
    public static function updateProject($p_id, $data)
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
    public static function deleteProject($p_id)
    {
        $dao = new ProjectsDao(ds());
        $dao->delete_project($p_id);
    }
}