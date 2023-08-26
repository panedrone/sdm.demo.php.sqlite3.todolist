<?php

require_once __DIR__ . '/dal/DataStore.php';

require_once __DIR__ . '/dal/ProjectsDao.php';
require_once __DIR__ . '/dal/TasksDao.php';

class bootstrap
{
    static $ds;

    static $projectsDao;

    static $tasksDao;
}

/**
 * @throws Exception
 */
function init_ds()
{
    bootstrap::$ds = new DataStore();
    bootstrap::$ds->open();

    bootstrap::$projectsDao = new ProjectsDao(bootstrap::$ds);

    bootstrap::$tasksDao = new TasksDao(bootstrap::$ds);
}

function ds(): DataStore
{
    return bootstrap::$ds;
}

function projects_dao(): ProjectsDao
{
    return bootstrap::$projectsDao;
}

function tasks_dao(): TasksDao
{
    return bootstrap::$tasksDao;
}