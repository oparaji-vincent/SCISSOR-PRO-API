<?php


class ShortUrlGateway extends Database
{

    public static function create(array $data): int|false
    {
        $self = new self;
        $query = "INSERT INTO shorturls(Title,ActualUrl,UniqueIdentifier,created_at,user) VALUES(:title,:actual,:identifier,NOW(),:user)";
        $prep = $self->connect()->prepare($query);
        $prep->bindValue(":title", $data["Title"]);
        $prep->bindValue(":actual", $data["ActualUrl"]);
        $prep->bindValue(":identifier", $data["UniqueIdentifier"]);
        if(isset($data["user"]) && !empty($data["user"])){
        $prep->bindValue(":user", $data["user"]);
        }else {
            $prep->bindValue(":user",null);
        }
        $prep->execute();
        return $self->connect()->lastInsertId();
    }

    public static function getAll(string $user): array|false
    {
        $self = new self;
        $prep = $self->connect()->prepare("SELECT * FROM shorturls WHERE user = :user");
        $prep->bindValue(":user", $user);
        $prep->execute();
        return $prep->fetchAll();
    }

    public static function findOne(string $uniqueIdenditifier, string $user): array|false
    {
        $self = new self;
        $query = "SELECT * FROM shorturls WHERE UniqueIdentifier =:identifier and user = :user";
        $prep = $self->connect()->prepare($query);
        $prep->bindValue(":identifier", $uniqueIdenditifier);
        $prep->bindValue(":user", $user);
        $prep->execute();
        return $prep->fetch();
    }

    public static function getClicks(string $user): array|false
    {
        $self = new self;
        $query = "SELECT SUM(clicks) as TotalClicks FROM shorturls WHERE user = :user";
        $prep = $self->connect()->prepare($query);
        $prep->bindValue(":user", $user);
        $prep->execute();
        return $prep->fetch();
    }

    public static function getLinksByDateRange(string $startDate, string $endDate, string $user): array | false
    {
        $self = new self;
        $query = "SELECT * FROM shorturls WHERE created_at >= :startDate and created_at <= :endDate and user = :user";
        $prep = $self->connect()->prepare($query);
        $prep->bindValue(":user", $user);
        $prep->bindValue(":startDate", $startDate);
        $prep->bindValue(":endDate", $endDate);
        $prep->execute();
        return $prep->fetchAll();
    }

    public static function getRecentLinks(string $user): array|false
    {
        $self = new self;
        $prep = $self->connect()->prepare("SELECT * FROM shorturls WHERE user = :user ORDER BY created_at desc LIMIT 5");
        $prep->bindValue(":user", $user);
        $prep->execute();
        return $prep->fetchAll();
    }
}
