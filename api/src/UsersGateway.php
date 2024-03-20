<?php

class UsersGateway extends Database
{

    public static function create(array $data): int |false
    {
        $self = new self;
        $query = "INSERT INTO users(uuid,Name,Email,photo_url) VALUES(:uuid,:name,:email,:photo)";
        $prep = $self->connect()->prepare($query);
        $prep->bindValue(":uuid", $data["uuid"]);
        $prep->bindValue(":name", $data["Name"]);
        $prep->bindValue(":email", $data["Email"]);
        if (array_key_exists("photo_url", $data) && !empty($data["photo_url"])) {
            $prep->bindValue(":photo", $data["photo_url"]);
        } else {
            $prep->bindValue(":photo", NULL, PDO::PARAM_NULL);
        }

        $prep->execute();
        return $self->connect()->lastInsertId();
    }
}
