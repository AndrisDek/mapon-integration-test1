<?php
include_once 'Database_params.php';

class Database extends DatabaseParams
{
    private $conn;

    public function connect()
    {
        $this->conn = null;

        try {
            $this->conn = new PDO(
                'mysql:host=' . $this->db_params['host'] .
                    ';dbname=' . $this->db_params['db_name'],
                $this->db_params['username'],
                $this->db_params['password']
            );

            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            echo 'Connection error: ' . $e->getMessage();
        }
        return $this->conn;
    }
}
