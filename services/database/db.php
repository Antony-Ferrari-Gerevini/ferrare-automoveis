<?php

// Establishing a connection to MySQL Server
$server = "localhost";
$user = "antony";
$password = 'adminTERRIBLEpa$$w0rd';

try {
    $connection = mysqli_connect($server, $user, $password, "ferrare_automoveis_db");
} catch (Exception) {
    echo "<script>console.logNão foi possível conectar o banco de dados.</script> " . mysqli_connect_error();
}

// faz um código em JS para o console.log acima