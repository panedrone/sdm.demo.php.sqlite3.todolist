<?php
// bootstrap.php

require_once('./dal/DataStore.php');

function ds() {
    static $dataStore = null;
    if ($dataStore !== null) {
        return $dataStore;
    }
    $dataStore = new DataStore();
    $dataStore->open();
    return $dataStore;
}
