<?php

class Users
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
        $this->data = (array) json_decode(file_get_contents("php://input"), true);
    }

    public function processRequest(string $method, ?int $id = null): void
    {
        
        switch ($method) {
            case "GET":
                if (!array_key_exists("key", $this->params) || empty($this->params["key"])) {
                    ResponseMessages::unauthorized();
                    return;
                }
                
                $key = $this->params["key"];
                $user = DatabaseFields::getByFilter(["uuid" => $key], "users");
                ($user)
                    ? ResponseMessages::showResult($user, true)
                    : ResponseMessages::notFound();
                break;
            case "POST":
                if (empty($this->data)) {
                    ResponseMessages::bodyNotFound();
                    return;
                }

                $errors = [];

                if (!array_key_exists("uuid", $this->data) || empty($this->data["uuid"])) {
                    $errors["uuid"] = "uuid is required";
                }
                if (!array_key_exists("Name", $this->data) || empty($this->data["Name"])) {
                    $errors["Name"] = "Name is required";
                }
                if (!array_key_exists("Email", $this->data) || empty($this->data["Email"])) {
                    $errors["Email"] = "Email is required";
                }

                if ($errors) {
                    ResponseMessages::respondUnprocessableEntity($errors);
                    return;
                }

                $id = UsersGateway::create($this->data);

                $id > 0
                    ? ResponseMessages::respondCreated("User", $id)
                    : ResponseMessages::processFailed("User creation");

                break;
            case "DELETE":
                if (!array_key_exists("key", $this->params) || empty($this->params["key"])) {
                    ResponseMessages::unauthorized();
                    return;
                }
            case "PUT":
                if (!array_key_exists("key", $this->params) || empty($this->params["key"])) {
                    ResponseMessages::unauthorized();
                    return;
                }
                break;
            default:
                ResponseMessages::methodNotAllowed("GET,POST,DELETE,PUT");
        }
    }
}
