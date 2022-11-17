<?php
namespace Ranchero\Wpbot\Model;

use \Exception;
use \mysqli;

class DataBase
{
    protected $connection = null;

    public function __construct(array $datasource)
    {
        try {
            $this->connection = new mysqli(
                $datasource['host'],
                $datasource['user'],
                $datasource['pass'],
                $datasource['database'],
                $datasource['port']
            );

            if (mysqli_connect_errno()) {
                throw new Exception("Could not connect to database.");
            }
        } catch (Exception $e) {
            throw new Exception($e->getMessage());
        }
    }

    public function select($query = "", $params = [])
    {
        try {
            $stmt = $this->executeStatement($query, $params);
            $result = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
            $stmt->close();

            return $result;
        } catch (Exception $e) {
            throw new Exception($e->getMessage());
        }
        return false;
    }

    private function executeStatement($query = "", $params = [])

    {
        // var_dump($params);
        try {
            $stmt = $this->connection->prepare($query);

            if ($stmt === false) {
                throw new Exception("Unable to do prepared statement: " . $query);
            }
            
            if ($params) {
                // call_user_func_array([$stmt,"bind_param"],$params);
                $stmt->bind_param($params[0], ...$params[1]);
            }

            $stmt->execute();

            return $stmt;
        } catch (Exception $e) {
            throw new Exception($e->getMessage());
        }
    }
}
