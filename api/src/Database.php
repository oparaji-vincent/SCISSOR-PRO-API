<?php

class Database
{
    private pdo|null  $pdo = null;
    private string $host = "HOST";
    private string $db = "DATABASE_NAME";
    private string $password = 'PASSWORD';
    private string $user = "USER";

    protected function connect(): PDO
    {
        if ($this->pdo === null) {
            $dsn = "mysql:host=$this->host;dbname=$this->db";
            $this->pdo = new PDO($dsn, $this->user, $this->password, [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_EMULATE_PREPARES => false,
                PDO::ATTR_STRINGIFY_FETCHES => false,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
            ]);
        }
        return $this->pdo;
    }
}
