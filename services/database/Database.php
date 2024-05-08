<?php

/*
    Files that use this class:
    - index.php
    - templateBuilder.php
*/

/* 
    TODO: método 'prepareInsertQuery()'
    TODO: método 'prepareUpdateQuery()'
    TODO: método 'prepareDeleteQuery()'
*/

class SQLInjectionException extends Exception {}
class SQLExecutionException extends Exception {}
class SQLPreparationException extends Exception {}

class Database {
    private $server;
    private $user;
    private $password;
    private $database;
    private $connection;

    function __construct($server, $user, $password, $database) {
        $this->server   = $server;
        $this->user     = $user;
        $this->password = $password;
        $this->database = $database;
        
        try {
            $this->connection = $this->connectToDatabase($this->server, $this->user, $this->password, $this->database);
        } catch (Exception $e) {
            echo '<script>console.log("' . $e->getMessage() . '")</script>';
        }
    }

    /** Stablishes a connection to a MySQL server and returns it */
    private function connectToDatabase($server, $user, $password, $database) {
        $connection = mysqli_connect($server, $user, $password, $database);
        return $connection;
    }

    /** Returns connection object */
    public function getConnection() {
        return $this->connection;
    }



    /** Performs SQL Injection on given query variable and returns it (requires char for the type of the attribute and the new value) */
    public static function sqlInjection($queryVariable, $typeChar, $newValue) {
        try {
            $queryVariable->bind_param($typeChar, $newValue);
        } catch (Exception) {
            throw new SQLInjectionException("Failed to bind parameters: " . $queryVariable->error);
        }
        return $queryVariable;
    }



    /** Performs a 'SELECT * FROM' query on the given table and returns the results */
    public function selectAllFromTable($table) {
        try {
            $selection = mysqli_query($this->connection, "SELECT * FROM {$table};");
        } catch (Exception) {
            throw new SQLExecutionException("Failed to execute selection query: " . mysqli_error($this->connection));
        }
        return $selection;
    }

    /** Executes SQL query (no return) */
    public function sqlExecute($queryVariable) {
        try {
            $queryVariable->execute();
        } catch (Exception) {
            throw new SQLExecutionException("Failed to execute SQL query: " . mysqli_error($this->connection));
        }   
    }

    /** Executes a 'SELECT' SQL query and returns the results */
    public function sqlExecuteSelection($queryVariable) {
        try {
            $queryVariable->execute();
        } catch (Exception) {
            throw new SQLExecutionException("Failed to execute selection query: " . $queryVariable->error);
        }
        $selection = $queryVariable->get_result();
        //$query = $query->fetch_assoc();
        return $selection;
    }
    
    /** Prepares a 'SELECT' query string and returns it. Obs.: 'queryArgs' is an associative array (queryArgs=[valuesSelected, orderBy, groupBy, innerJoin])*/
    public function prepareSelectQuery($table, $queryArgs=[]) {
        $query = "SELECT";
        extract($queryArgs);

        // valuesSelected
        if (isset($valuesSelected)) {
            $valuesSelected = (array)$valuesSelected;
            $value = array_shift($valuesSelected);
            $query .= " {$value}";

            if (count($valuesSelected) > 0) {
                foreach ($valuesSelected as $value) {
                    $query .= ", {$value}";
                }
            }
        } else {
            $query .= " *";
        }
        $query .= " FROM {$table}";

        // order by
        if (isset($orderBy)) {
            $value = $orderBy;
            $query .= " ORDER BY {$value}";
        }
        
        // group by
        if (isset($groupBy)) {
            $value = $groupBy;
            $query .= " GROUP BY {$value}";
        }

        // inner join
        if (isset($innerJoin)) {
            $innerJoin = (array)$innerJoin;
            foreach ($innerJoin as $value) {
                $query .= " INNER JOIN {$value}";
            }
        }

        // end
        $query .= ";";
        
        try {
            $query = $this->connection->prepare($query);
        } catch (Exception) {
            throw new SQLPreparationException("Failed to prepare SQL query: " . mysqli_error($this->connection));
        };

        return $query;
    }

    /** Prepares a 'INSERT' query string and returns it*/
    public function prepareInsertQuery($table, $values) { // $values["assoc" => "iative"]
        // TODO
    }

    /** Prepares a 'UPDATE' query string and returns it*/
    public function prepareUpdateQuery($table, $id, $newValues) { // $newValues["assoc" => "iative"]
        // TODO
    }

    /** Prepares a 'DELETE' query string and returns it*/
    public function prepareDeleteQuery($table, $id) {
        // TODO
    }
}