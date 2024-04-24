<?php

require "php/functions.php";

// Establishing a connection to MySQL Server
$server = "localhost";
$user = "antony";
$password = 'adminTERRIBLEpa$$w0rd';

try {
    $connection = mysqli_connect($server, $user, $password, "ferrare_automoveis_db");
} catch (Exception) {
    echo "<h3>Não foi possível conectar o banco de dados.</h2> " . mysqli_connect_error();
}

// Creating variables with data from the database
try {
    $banners_list = mysqli_query($connection, "SELECT * FROM banners;");
    $vehicle_list = mysqli_query($connection, "SELECT * FROM veiculos;");
    $marca_list = mysqli_query($connection, "SELECT DISTINCT marca FROM veiculos;");
    $linha_list = mysqli_query($connection, "SELECT DISTINCT linha FROM veiculos;");
} catch (Exception) {
    echo "SQL ERROR " . mysqli_error($connection);
}



// Saving HTML contents to variables
$header = file_get_contents("html/header.html");
$banner = file_get_contents("html/banner.html");
$estoque_header = file_get_contents("html/estoque_header.html");

// Displaying content variables
echo $header;
echo $banner;
echo $estoque_header;
create_filter_dropdown_menu($marca_list);

echo '<div class="grade-ofertas">';
while ($veiculo = mysqli_fetch_assoc($vehicle_list)) { // Generating blocks for the vehicle grid
    create_vehicle_block($veiculo);
}

echo "</div>";
echo "Teste";
