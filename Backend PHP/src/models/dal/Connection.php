<?php

namespace App\models\dal;

use PDO;
use PDOException;

class Connection
{
    private $connection;

    public function getConnection()
    {
        global $username, $password, $hostname, $port, $dbname;

        if ($this->connection === null) {
            try {
                $dsn = "mysql:host=$hostname;port=$port;dbname=$dbname;charset=utf8mb4";
                $this->connection = new PDO($dsn, $username, $password);
                $this->connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                $this->connection->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
            } catch (PDOException $e) {
                http_response_code(500);
                echo json_encode([
                    "msg"   => "Error de conexión a la base de datos",
                    "error" => $e->getMessage()
                ]);
                exit();
            }
        }
        return $this->connection;
    }

    public function query($csql)
    {
        return $this->getConnection()->query($csql);
    }

    public function queryWithParams($csql, $paramArray = [])
    {
        $stmt = $this->getConnection()->prepare($csql);
        $stmt->execute($paramArray);
        return $stmt;
    }

    public function getLastInsertedId(): int
    {
        return (int)$this->getConnection()->lastInsertId();
    }
}