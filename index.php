<?php


require __DIR__ . "/api/bootstrap.php";


if (!isset($_GET['url'])) {
    http_response_code(400);
    return;
}


$identifier = $_GET['url'];
if ($identifier === "") {
    http_response_code(404);
    return;
}

$urlGateway = new ShortUrlGateway();
$data = DatabaseFields::getByFilter(["UniqueIdentifier"=>$identifier],"shorturls");

// $data = $urlGateway->findOne($identifier);

if (!$data) {
    http_response_code(404);
    header("Location: https://scissor-shorts.netlify.app/$identifier");
    return;
}

$clicks = (int)$data[0]["clicks"] + 1;

QueryHelper::update($data[0]["id"], "shorturls", ["clicks" => $clicks]);

$actualLink =  $data[0]["ActualUrl"];
header("Location: $actualLink");
