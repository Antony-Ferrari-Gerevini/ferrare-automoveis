<?php

// Adds the "?page=home" query string to the URL if there's none
if (empty($_SERVER['QUERY_STRING'])) {
    header("Location: {$_SERVER['PHP_SELF']}?pagina=home");
    exit();
}

// Requires PHP file with functions
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
    $vehicle_list = mysqli_query($connection, "SELECT * FROM veiculo;");
    $brand_list = mysqli_query($connection, "SELECT * FROM marca;");
    $model_list = mysqli_query($connection, "SELECT * FROM linha;");
    $vehicle_photos_list = mysqli_query($connection, "SELECT * FROM fotos_veiculo;");
} catch (Exception) {
    echo "SQL ERROR " . mysqli_error($connection);
}



// Saving HTML contents to variables
$header = file_get_contents("static/header.html");
$footer = file_get_contents("static/footer.html");
$banner = file_get_contents("static/banner.html");
$stock_header = file_get_contents("static/estoque_header.html");
$location = file_get_contents("static/localizacao.html");

// Displaying content variables
echo $header;
echo $banner;

if ($_GET['pagina'] === "home") {
    echo $stock_header;
    create_filter_dropdown_menu($brand_list);
    create_vehicle_grid($vehicle_list, $connection);
}

if ($_GET['pagina'] === "localizacao") {
    echo $location;
}

echo "</div>";
echo $footer;
