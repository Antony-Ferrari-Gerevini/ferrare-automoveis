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



/** Stablishes a connection to a preset MySQL server and returns it */
function connectToDatabase() {
    $server   = "localhost";
    $user     = "antony";
    $password = 'adminTERRIBLEpa$$w0rd';
    $database = "ferrare_automoveis_db";

    $connection = mysqli_connect($server, $user, $password, $database);
    return $connection;
}

/** Performs a 'SELECT * FROM' query on the given table and returns the results */
function selectAllFromTable($connection, $table) {
    $selection = mysqli_query($connection, "SELECT * FROM {$table};");
    return $selection;
}

function prepareSelectQuery($table, $queryArgs=[]) {
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
        foreach ($innerJoin as $value) {
            $query .= " INNER JOIN {$value}";
        }
    }

    // end
    $query .= ";";
    return $query;
}

/** Performs SQL Injection on given query variable, executes it and then returns it (requires char for the type of the attribute and the new value) */
function sqlInjectionAndExecuteAndFetch($queryVariable, $typeChar, $newValue) {
    $queryVariable->bind_param($typeChar, $newValue);
    $queryVariable->execute();
    $queryVariable = $queryVariable->get_result();
    $queryVariable = $queryVariable->fetch_assoc();
    return $queryVariable;
}





class Database {
    private $server;
    private $user;
    private $password;
    private $database;
    private $connection;

    function __construct($server, $user, $password, $database) {
        $this->server     = $server;
        $this->user       = $user;
        $this->password   = $password;
        $this->database   = $database;
        $this->connection = $this->connectToDatabase($this->server, $this->user, $this->password, $this->database);
    }

    /** Stablishes a connection to a MySQL server and returns it */
    private function connectToDatabase($server, $user, $password, $database) {
        $connection = mysqli_connect($server, $user, $password, $database);
        return $connection;
    }



    /** Prepares a 'SELECT' query string and returns it. Obs.: 'queryArgs' is an associative array (queryArgs=[valuesSelected, orderBy, groupBy, innerJoin])*/
    public static function prepareSelectQuery($table, $queryArgs=[]) {
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
            foreach ($innerJoin as $value) {
                $query .= " INNER JOIN {$value}";
            }
        }

        // end
        $query .= ";";
        return $query;
    }

    /** Prepares a 'INSERT' query string and returns it*/
    public static function prepareInsertQuery($table, $values) { // $values["assoc" => "iative"]
        // TODO
    }

    /** Prepares a 'UPDATE' query string and returns it*/
    public static function prepareUpdateQuery($table, $id, $newValues) { // $newValues["assoc" => "iative"]
        // TODO
    }

    /** Prepares a 'DELETE' query string and returns it*/
    public static function prepareDeleteQuery($table, $id) {
        // TODO
    }

    /** Performs SQL Injection on given query variable and returns it (requires char for the type of the attribute and the new value) */
    public static function sqlInjection($queryVariable, $typeChar, $newValue) {
        $queryVariable->bind_param($typeChar, $newValue);
        return $queryVariable;
    }



    /** Performs a 'SELECT * FROM' query on the given table and returns the results */
    public function selectAllFromTable($table) {
        $selection = mysqli_query($this->connection, "SELECT * FROM {$table};");
        return $selection;
    }

    /** Executes SQL query (no return) */
    public function sqlExecute($queryVariable) {
        //$queryVariable->execute(); // apagar esta linha se a próxima funcionar
        $selection = mysqli_query($this->connection, $queryVariable);
    }

    /** Executes a 'SELECT' SQL query and returns the results */
    public function sqlExecuteSelection($queryVariable) {
        //$queryVariable->execute(); // apagar esta linha se a próxima funcionar
        $selection = mysqli_query($this->connection, $queryVariable);
        $query = $queryVariable->get_result();
        $query = $query->fetch_assoc();
        return $query;
    }
}