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
    $veiculo_list = mysqli_query($connection, "SELECT * FROM veiculo;");
    $marca_list = mysqli_query($connection, "SELECT * FROM marca;");
    $linha_list = mysqli_query($connection, "SELECT * FROM linha;");
    $fotos_veiculo_list = mysqli_query($connection, "SELECT * FROM fotos_veiculo;");
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
while ($veiculo = mysqli_fetch_assoc($veiculo_list)) { // Generating blocks for the vehicle grid
    $id = $veiculo['id'];

    // Doing SQL injection the safe way
    $marca = $connection->prepare("SELECT m.nome FROM veiculo AS v INNER JOIN linha AS l ON l.id = v.id_linha INNER JOIN marca AS m ON m.id = l.id_marca WHERE v.id = ?;");
    $marca->bind_param("i", $id);
    $marca->execute();
    $marca = $marca->get_result();
    $marca = $marca->fetch_assoc();

    $foto_principal = $connection->prepare("SELECT fv.diretorio_fotos, fv.foto_principal FROM veiculo AS v INNER JOIN fotos_veiculo AS fv ON fv.id = ?;");
    $foto_principal->bind_param("i", $id);
    $foto_principal->execute();
    $foto_principal = $foto_principal->get_result();
    $foto_principal = $foto_principal->fetch_assoc();

    create_vehicle_block($veiculo, $marca, $foto_principal);
}

echo "</div>";
echo "Teste";
