<?php

class Shorturl
{

    public array $params;
    public array $data;

    public function __construct()
    {
        $this->params = [];
        $url_components = parse_url($_SERVER["REQUEST_URI"]);
        if (isset($url_components['query'])) {
            parse_str($url_components['query'], $this->params);
        }
        $this->data = (array)json_decode(file_get_contents("php://input"), true);
    }

    public function processRequest(
        string $method,
        ?int $id = null
    ) {

        if (!array_key_exists("key", $this->params) || empty($this->params["key"])) {
            ResponseMessages::unauthorized();
            return;
        }

        $key = $this->params["key"];


        if ($id === null) {
            if ($method === "GET") {
                $urls = ShortUrlGateway::getAll($key);
                if (!$urls) {
                    echo json_encode(["status" => false, "urls" => []]);
                    return;
                }
                $modifiedUrls = [];
                foreach ($urls as $url) {
                    $shortenedUrl = $url["UniqueIdentifier"];
                    $url["ShortUrl"] = BASE_URL . "$shortenedUrl";
                    $modifiedUrls[] = $url;
                }
                echo json_encode(["status" => true, "urls" => $modifiedUrls]);
            } elseif ($method === "POST") {
                $this->post($key);
            } else {
                ResponseMessages::methodNotAllowed("GET,POST");
            }

            return;
        }

        switch ($method) {
            case "PUT":
                if (empty($this->data)) {
                    ResponseMessages::bodyNotFound();
                    return;
                }

                if (!array_key_exists("ActualUrl", $this->data)) {
                    ResponseMessages::respondUnprocessableEntity(["ActualUrl" => "ActrualUrl is required"]);
                    return;
                }

                $rows = QueryHelper::update($id, "shorturls", $this->data);
                if ($rows > 0) {
                    $result = QueryHelper::getByID($id, "shorturls");
                    ResponseMessages::showResult([...$result, "message" => "link updated"], true);
                } else {
                    ResponseMessages::processFailed("Link update");
                }
                break;
            case "DELETE":
                $row = QueryHelper::delete($id, "shorturls");
                $row > 0 ? ResponseMessages::deletedRows($row) : ResponseMessages::processFailed("Removing link");
                break;
            default:
                ResponseMessages::methodNotAllowed("PUT,DELETE");
        }
    }

    private function post($key): void
    {
        if (empty($this->data)) {
            ResponseMessages::bodyNotFound();
            return;
        }

        $errors = [];

        if (!array_key_exists("ActualUrl", $this->data)) $error["ActualUrl"] = "ActualUrl is required";

        if (!array_key_exists("Title", $this->data) || empty($this->data["Title"])) $error["Title"] = "Title is required";

        if ($errors) {
            ResponseMessages::respondUnprocessableEntity($errors);
            return;
        }


        $this->data["user"] = ($key !== "undefined") ? $key : null;

        if (array_key_exists("custom_alias", $this->data) && !empty($this->data["custom_alias"])) {
            $url = ShortUrlGateway::findOne($this->data["custom_alias"], $key);
            if ($url) {
                ResponseMessages::invalidrequest("The custom alias provided is no longer available");
                return;
            }
            $shortenedUrl = $this->data["custom_alias"];
            unset($this->data["custom_alias"]);
        } else {
            $code = random_bytes(4);
            $shortenedUrl = bin2hex($code);
            $link = ShortUrlGateway::findOne($shortenedUrl, $key);
            if ($link) return;
        }


        $this->data["UniqueIdentifier"] = $shortenedUrl;
        $id = ShortUrlGateway::create($this->data);
        $this->data["ShortUrl"] = BASE_URL.$shortenedUrl;
        $this->data["id"] = $id;
        $this->data["clicks"] = 0;
        $this->data["created_at"] = date("Y-m-d H:i:s");

        echo  $id > 0 ? json_encode(["status" => true, "url" => $this->data]) : ResponseMessages::processFailed("Shortened link creation");
    }
}
