<?php

function json_resp($data)
{
    header("Content-Type: application/json; charset=UTF-8");
    echo json_encode($data);
}