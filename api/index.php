<?php

require_once 'Route.php';
require_once 'handlers.php';

use Steampixel\Route;

Route::add('/api/projects', function () {
    handle_projects();
}, ['get', 'post']);

Route::add('/api/projects/([0-9]*)', function ($p_id) {
    handle_project($p_id);
}, ['get', 'put', 'delete']);

Route::add('/api/projects/([0-9]*)/tasks', function ($p_id) {
    handle_project_tasks($p_id);
}, ['get', 'post']);

Route::add('/api/tasks/([0-9]*)', function ($t_id) {
    handle_task($t_id);
}, ['get', 'put', 'delete']);

Route::methodNotAllowed(function ($path, $method) {
    header("HTTP/1.1 405 Not Allowed $path $method");
    http_response_code(StatusCode::HTTP_METHOD_NOT_ALLOWED);
});

Route::pathNotFound(function ($path) {
    header("HTTP/1.1 404 Not Found $path");
    http_response_code(StatusCode::HTTP_NOT_FOUND);
});

// === panedrone:
//
// 1) while debugging on apache
//
//      - $uri is like "/<web-site-home>/api/projects",
//      - $base is "/<web-site-home>"
//      - the root is "/<web-site-home>" and there is nothing at "/api/projects",
//        so it must be fetch("api/projects"), not fetch("/api/projects").
//
// 2) while debugging on built-in web server
//
//      - $uri comes like "/api/projects", so the $base is ""
//      - "/api/projects" comes even with fetch("api/projects")

$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$uri_parts = explode('/api/', $uri);
if (count($uri_parts) > 1) {
    $base = $uri_parts[0];
} else {
    // $base = "/";
    $base = ""; // === panedrone: all my routes are started with "/api/", so the base is ""
}

Route::run($base);

