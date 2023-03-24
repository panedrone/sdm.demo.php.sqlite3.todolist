# sdm_demo_php_todolist
Quick Demo of how to use [SQL DAL Maker](https://github.com/panedrone/sqldalmaker) + PHP/PDO.
Front-end is written in Vue.js, SQLite3 is used as database.

![demo-go.png](demo-go.png)

![erd.png](erd.png)


dto.xml
```xml
<dto-class name="Project" ref="projects"/>

<dto-class name="ProjectEx" ref="get_projects.sql">
    <field column="p_id" type="int"/>
    <field column="p_name" type="string"/>
    <field column="p_tasks_count" type="int"/>
</dto-class>

<dto-class name="Task" ref="tasks"/>
```
ProjectsDao.xml
```xml
<crud table="projects" dto="Project"/>

<query-dto-list dto="ProjectEx" method="get_projects"/>
```
TasksDao.xml
```xml
<crud table="tasks" dto="Task"/>

<query-dto-list ref="get_project_tasks.sql" dto="Task" method="get_project_tasks(p_id)"/>
```
Generated code in action:
```php
<?php

require_once "../bootstrap.php";

require_once '../dal/ProjectsDao.php';
require_once '../dal/Project.php';

class ProjectsController
{
    public static function createProject($data)
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

    public static function readProject($p_id): array
    {
        $dao = new ProjectsDao(ds());
        $gr = $dao->read_project($p_id);
        return array(
            "p_id" => $gr->get_p_id(),
            "p_name" => $gr->get_p_name(),
        );
    }

    public static function updateProject($p_id, $data)
    {
        $dao = new ProjectsDao(ds());
        $gr = new Project();
        $gr->set_p_id($p_id);
        $gr->set_p_name($data->p_name);
        $dao->update_project($gr);
    }

    public static function deleteProject($p_id)
    {
        $dao = new ProjectsDao(ds());
        $dao->delete_project($p_id);
    }
}
```