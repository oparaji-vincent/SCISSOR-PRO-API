<?php

declare(strict_types=1);

require __DIR__ . "/bootstrap.php";

$url = $_SERVER["REQUEST_URI"];
$parts = explode("/", $url);
if (!isset($parts[2]) || empty($parts[2])) {
    ResponseMessages::notFound();
    exit;
}

$id = isset($parts[3]) ? $parts[3]: null;
if($id){
    $fullID = parse_url($parts[3]);
    $id = isset($fullID["path"]) ? (int) $fullID["path"] : null;
}

$resource = parse_url(ucwords($parts[2]));

$path = isset($resource["path"]) ? (string) $resource["path"] : "";

if (!file_exists(__DIR__ . "/src/$path.php")) {
    http_response_code(404);
    exit;
}


$method = $_SERVER["REQUEST_METHOD"];

$controller = new $path;
$controller->processRequest($method,$id);
