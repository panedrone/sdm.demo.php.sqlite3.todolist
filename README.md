# sdm_demo_php_todolist

Quick Demo of how to use [SQL DAL Maker](https://github.com/panedrone/sqldalmaker) + PHP/PDO.

Front-end is written in Vue.js, SQLite3 is used as a database.

![demo-go.png](demo-go.png)

dto.xml

```xml

<dto-classes>

    <dto-class name="Project" ref="projects"/>

    <dto-class name="ProjectEx" ref="get_projects.sql">
        <field column="p_id" type="int"/>
        <field column="p_name" type="string"/>
        <field column="p_tasks_count" type="int"/>
    </dto-class>

    <dto-class name="Task" ref="tasks"/>

</dto-classes>
```

ProjectsDao.xml

```xml

<dao-class>

    <crud dto="Project"/>

    <query-dto-list dto="ProjectEx" method="get_projects"/>

</dao-class>
```

TasksDao.xml

```xml

<dao-class>

    <crud dto="Task"/>

    <query-dto-list ref="get_project_tasks.sql" dto="Task" method="get_project_tasks(p_id)"/>

</dao-class>
```

Generated code in action:

```php
<?php

require_once "./bootstrap.php";

require_once './dal/ProjectsDao.php';
require_once './dal/Project.php';

/**
 * @throws Exception
 */
function project_create($data): ?string
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
```