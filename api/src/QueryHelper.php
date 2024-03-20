<?php


class QueryHelper extends Database
{
    public static function update(int $id, string $table, array $data): int
    {
        $fields = [];
        foreach (DatabaseFields::getColumns($table) as $column) {
            if (array_key_exists($column["Field"], $data)) {
                $field = $column["Field"];
                $fields["$field"] = [
                    $data["$field"]
                ];
            }
        }

        if (!empty($fields)) {
            $sets = array_map(function ($value) {
                return "$value=:$value";
            }, array_keys($fields));

            $query = "UPDATE $table SET " . implode(", ", $sets) . " WHERE id=:id";
            $prep = (new self)->connect()->prepare($query);
            $prep->bindValue(":id", $id, 1);
            foreach ($fields as $key => $value) {
                $prep->bindValue(":$key", $value[0]);
            }
            $prep->execute();
            return $prep->rowCount() > 0 ?  $prep->rowCount() : false;
        } else {
            return false;
        }
    }


    /**
     * Gets a specific row from a table 
     * 
     * This function takes three inputs and returns an array or false
     * 
     * @param int $id the id of the row
     * @param string $table The table the record is to be selected from
     * @param mixed $options optional parameter that accepts configuration options
     * 
     */

    public static function getByID(int $id, string $table, ?array $options = [
        "secondary_columns" => "",
        "join_statement" => ""
    ]): array|false
    {
        if ($options && !empty($options["secondary_columns"]) && !empty($options["join_statement"])) {
            $secondaryColumns = $options["secondary_columns"];
            $joinStatement = $options["join_statement"];

            $query = "SELECT $table.*, $secondaryColumns FROM $table $joinStatement WHERE $table.id =:id";
        } else {
            $query = "SELECT $table.*  FROM $table WHERE id=:id";
        }
        $prep = (new self)->connect()->prepare($query);
        $prep->bindValue(":id", $id, 1);
        $prep->execute();
        return $prep->fetch();
    }

    public static function getAll(string $table, ?array $options = [
        "secondary_columns" => "",
        "join_statement" => ""
    ]): array|false
    {
        if ($options && !empty($options["secondary_columns"]) && !empty($options["join_statement"])) {
            $secondaryColumns = $options["secondary_columns"];
            $joinStatement = $options["join_statement"];

            $query = "SELECT $table.*, $secondaryColumns FROM $table $joinStatement";
        } else {
            $query = "SELECT $table.*  FROM $table";
        }
        $prep = (new self)->connect()->query($query);
        return $prep->fetchAll();
    }

    public static function delete(int $id, string $table): int | false
    {
        $query = "DELETE FROM $table WHERE id=:id";
        $prep = (new self)->connect()->prepare($query);
        $prep->bindValue(":id", $id, 1);
        $prep->execute();
        return $prep->rowCount() > 0 ?  $prep->rowCount() : false;
    }

    
}
