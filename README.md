# sdm_demo_php_todolist

Quick Demo of how to use [SQL DAL Maker](https://github.com/panedrone/sqldalmaker) + PHP/PDO.

Front-end is written in Vue.js, SQLite3 is used as a database.

![demo-go.png](demo-go.png)

dto.xml

```xml

<dto-classes>

    <dto-class name="Project" ref="projects"/>

    <dto-class name="ProjectLi" ref="get_projects.sql">
        <field column="p_id" type="int"/>
        <field column="p_name" type="string"/>
        <field column="p_tasks_count" type="int"/>
    </dto-class>

    <dto-class name="Task" ref="tasks"/>

    <dto-class name="TaskLi" ref="get_project_tasks.sql">
        <field column="t_id" type="int"/>
        <field column="t_priority" type="int"/>
        <field column="t_date" type="string"/>
        <field column="t_subject" type="string"/>
    </dto-class>
    
</dto-classes>
```

ProjectsDao.xml

```xml

<dao-class>

    <crud dto="Project"/>

    <query-dto-list dto="ProjectLi" method="get_projects"/>

</dao-class>
```

TasksDao.xml

```xml

<dao-class>

    <crud dto="Task"/>

    <query-dto-list dto="TaskLi" method="get_project_tasks(p_id)"/>
    
</dao-class>
```

Generated code in action:

```php
<?php

require_once "bootstrap.php";

require_once 'dal/Project.php';

function project_create($data)
{
    $pr = new Project();
    $pr->set_p_name($data->p_name);
    projects_dao()->create_project($pr);
}

function projects_read_all(): array
{
    $projects = projects_dao()->get_projects();
    $arr = array();
    foreach ($projects as $pr) {
        $item = array(
            "p_id" => $pr->get_p_id(),
            "p_name" => $pr->get_p_name(),
            "p_tasks_count" => $pr->get_p_tasks_count(),
        );
        array_push($arr, $item);
    }
    return $arr;
}

function project_read($p_id): array
{
    $pr = projects_dao()->read_project($p_id);
    return array(
        "p_id" => $pr->get_p_id(),
        "p_name" => $pr->get_p_name(),
    );
}

function project_update($p_id, $data)
{
    $pr = new Project();
    $pr->set_p_id($p_id);
    $pr->set_p_name($data->p_name);
    projects_dao()->update_project($pr);
}

function project_delete($p_id)
{
    projects_dao()->delete_project($p_id);
}
```