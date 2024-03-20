<?php

class ResponseMessages
{
    public static  function methodNotAllowed(string $allowedMethods): void
    {
        http_response_code(405);
        header("Allow: " . $allowedMethods);
    }

    public static function respondNotFound(string $resource, string $id): void
    {
        http_response_code(404);
        echo json_encode(["message" => "$resource $id does not exist", "status" => false]);
    }

    public static function notFound(): void
    {
        http_response_code(404);
        echo json_encode(["message" => "Resource not found", "status" => false]);
    }

    public static function unauthorized(): void
    {
        http_response_code(401);
        echo json_encode(["message" => "This user is not authorized to perform this action.", "status" => false]);
    }

    public static function invalidInput(): void
    {
        http_response_code(400);
        echo json_encode(["message" => "Invalid input", "status" => false]);
    }

    public static function invalidrequest(string $message): void
    {
        http_response_code(400);
        echo json_encode(["status" => false, "message" => "$message"]);
    }

    public static function respondCreated(string $resource, int $id): void
    {

        http_response_code(201);
        echo json_encode([
            "id" => $id,
            "message" => "$resource created",
            "status" => true
        ]);
    }

    public static function bodyNotFound(): void
    {
        http_response_code(400);
        echo json_encode([
            "message" => "Request body not found",
            "status" => false
        ]);
    }

    public static function respondUnprocessableEntity(array $errors): void
    {
        http_response_code(422);
        echo json_encode(["errors" => $errors, "status" => false]);
    }

    public static function updatedRows($rows): void
    {
        echo json_encode([
            "rows" => "$rows",
            "message" => ($rows > 1 ? "$rows rows " : "$rows row") . " updated",
            "status" => true
        ]);
    }

    public static function deletedRows($rows): void
    {
        echo json_encode([
            "rows" => "$rows",
            "message" => ($rows > 1 ? "$rows rows " : "$rows row") . " deleted",
            "status" => true
        ]);
    }

    public static function respondOk(array $data): void
    {
        echo json_encode($data);
    }

    public static function processFailed(string $action): void
    {
        http_response_code(422);
        echo json_encode([
            "message" => "$action failed. Please try again.",
            "status" => false
        ]);
    }

    public static function showResult(array $data, bool $status): void
    {
        echo json_encode(["data" => $data, "status" => $status]);
    }
}
