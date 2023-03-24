# sdm_demo_php_todolist
Quick Demo of how to use [SQL DAL Maker](https://github.com/panedrone/sqldalmaker) + PHP/PDO.
Front-end is written in Vue.js, SQLite3 is used as database.

![demo-go.png](demo-go.png)

![erd.png](erd.png)


dto.xml
```xml
<dto-class name="Group" ref="groups"/>

<dto-class name="GroupEx" ref="get_groups.sql">
    <field column="g_id" type="int"/>
    <field column="g_name" type="string"/>
    <field column="g_tasks_count" type="int"/>
</dto-class>

<dto-class name="Task" ref="tasks"/>
```
GroupsDao.xml
```xml
<crud table="groups" dto="Group"/>
<query-dto-list dto="GroupEx" method="get_groups"/>
```
TasksDao.xml
```xml
<crud table="tasks" dto="Task"/>
<query-dto-list ref="get_project_tasks.sql" dto="Task" method="get_group_tasks(g_id)"/>
```
Generated code in action:
```php
<?php

require_once "../bootstrap.php";

require_once '../dal/GroupsDao.php';
require_once '../dal/Group.php';

class GroupsController
{
    public static function createGroup($data)
    {
        $dao = new GroupsDao(ds());
        $gr = new Group();
        if (strlen(trim($data->g_name)) == 0) {
            return "Group name not set";
        }
        $gr->set_g_name($data->g_name);
        $dao->create_group($gr);
        return null;
    }

    public static function readGroups(): array
    {
        $dao = new GroupsDao(ds());
        $groups = $dao->get_groups();
        $arr = array();
        foreach ($groups as $gr) {
            $item = array(
                "g_id" => $gr->get_g_id(),
                "g_name" => $gr->get_g_name(),
                "g_tasks_count" => $gr->get_g_tasks_count(),
            );
            array_push($arr, $item);
        }
        return $arr;
    }

    public static function readGroup($g_id): array
    {
        $dao = new GroupsDao(ds());
        $gr = $dao->read_group($g_id);
        return array(
            "g_id" => $gr->get_g_id(),
            "g_name" => $gr->get_g_name(),
        );
    }

    public static function updateGroup($g_id, $data)
    {
        $dao = new GroupsDao(ds());
        $gr = new Group();
        $gr->set_g_id($g_id);
        $gr->set_g_name($data->g_name);
        $dao->update_group($gr);
    }

    public static function deleteGroup($g_id)
    {
        $dao = new GroupsDao(ds());
        $dao->delete_group($g_id);
    }
}
```