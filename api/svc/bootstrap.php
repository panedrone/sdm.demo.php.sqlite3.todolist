<?php

require_once('dal/DataStore.php');

class _DS
{
    /**
     * @var DataStore
     */
    static $ds;
}

/**
 * @throws Exception
 */
function ds()
{
    return _DS::$ds;
}

function init_ds()
{
    _DS::$ds = new DataStore();
    _DS::$ds->open();
}