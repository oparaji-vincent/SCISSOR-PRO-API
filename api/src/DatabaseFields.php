<?php

class DatabaseFields extends Database
{
    /*
     * Returns an array of the columns in a table
     */
    public static function getColumns(string $table): array|bool
    {
        $query = "SHOW COLUMNS FROM $table";
        $prep = (new self)->connect()->query($query);
        return $prep->fetchAll();
    }


    /**
     * Fetches data from specified table based on custom fields.
     * 
     * This function takes four inputs and returns an array or false
     * @param array $data an associative array that contains the filter values
     * @param string $table The table the record is to be selected from
     * @param mixed $options optional parameter that accepts configuration options
     * 
     */
    public static function getByFilter(array $data, string $table, ?string $operator = "AND"): array|bool
    {
        $count = count($data);
        if ($count > 1) {
            $query = "SELECT * FROM $table WHERE ";
            $firstIndex = array_key_first($data);
            foreach ($data as $key => $value) {
                if ($key === $firstIndex) {
                    $query .= " $key like :$key";
                } else {
                    $query .= " $operator $key like :$key";
                }
            }
        } else {
            $query = "SELECT * FROM $table WHERE " . array_key_first($data) . " like :" . array_key_first($data);
        }
        $prep = (new self)->connect()->prepare($query);
        foreach ($data as $key => $value)  $prep->bindValue(":$key", "%" . $value . "%");
        $prep->execute();
        return $prep->fetchAll();
    }

    /**
     *  fetches columns from a table based on the fields specified by the user.
     * 
     * @param array $data includes all fields to be returned in result set.
     * @param string $table the table from which the recorded is seleted
     * @param int $id optional parameter used to select a single resource.
     */

    public static function getPartialResponse(array $data, string $table, ?int $id = null): array|bool
    {
        if ($id === null) {
            $query = "SELECT " . implode(", ", $data) . " FROM $table";
            $prep = (new self)->connect()->query($query);
            return $prep->fetchAll();
        } else {
            $query = "SELECT " . implode(", ", $data) . " FROM $table WHERE id=:id";
            $prep = (new self)->connect()->prepare($query);
            $prep->bindValue(":id", $id, PDO::PARAM_INT);
            $prep->execute();
            return $prep->fetch();
        }
    }
    /*
     * The function below fetches record from a table based on user-defined conditions and specified fields.
     */
    public static function getPartialFilteredResponse(array $fields, array $filters, string $table, int $entity): array|bool
    {
        $query = "SELECT " . implode(", ", $fields) . " FROM $table WHERE Entity = :entity And ";
        $count = count($filters[0]);
        if ($count > 1) {
            $firstIndex = array_key_first($filters[0]);
            foreach ($filters[0] as $key => $value) {
                if ($key === $firstIndex) {
                    $query .= " $key like :$key";
                } else {
                    $query .= " " . $filters[1] . " $key like :$key";
                }
            }
        } else {
            $query .= array_key_first($filters[0]) . " like :" . array_key_first($filters[0]);
        }
        $prep = (new self)->connect()->prepare($query);
        $prep->bindValue(":entity", $entity, PDO::PARAM_INT);
        foreach ($filters[0] as $key => $value) {
            $prep->bindValue(":$key", $value[0]);
        }
        $prep->execute();
        return $prep->fetchAll();
    }


    /* 
        Get total rows in table for entity
    */
    public static function totalRows(string $table, int $entity): int
    {
        $query = "SELECT COUNT(id) as Count FROM $table WHERE Entity=:entity";
        $prep = (new self)->connect()->prepare($query);
        $prep->bindValue(":entity", $entity, 1);
        $prep->execute();
        $result = $prep->fetch();
        return (int)$result["Count"];
    }
}
