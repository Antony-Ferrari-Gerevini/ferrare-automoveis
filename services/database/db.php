<?php

/*
    This PHP file with functions shall one day become a database class with all necessary properties built-in.
*/



/**Stablishes a connection to a preset MySQL server and returns it */
function connectToDatabase() {
    $server   = "localhost";
    $user     = "antony";
    $password = 'adminTERRIBLEpa$$w0rd';
    $database = "ferrare_automoveis_db";

    $connection = mysqli_connect($server, $user, $password, $database);
    return $connection;
}

/**Performs a 'SELECT * FROM' query on the given table and returns the results */
function selectAllFromTable($connection, $tableName) {
    $selection = mysqli_query($connection, "SELECT * FROM $tableName;");
    return $selection;
}