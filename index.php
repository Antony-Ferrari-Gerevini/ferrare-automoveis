<?php

/*
    TODO: console logs for when connection to database fails
    TODO: content preparation for vehicle CRUD pages
    TODO: transform 'templateBuilder' into a class (new name: ContentBuilder? TemplateBuilder? PageBuilder?)
    TODO: transform the vehicles into objects
    TODO: implement sessions
*/



// Adds the '?page=home' query string to the URL if there's none
if (empty($_SERVER['QUERY_STRING'])) {
    header("Location: {$_SERVER['PHP_SELF']}?pagina=home");
    exit();
}

// Includes
include "assets/php/templateBuilder.php";
include "config/development/dbConfig.php";
include "services/database/Database.php";

// Connects to DB
try {
    $db = new Database($dbServer, $dbUser, $dbPassword, $dbDatabase);
} catch (Exception) {
    // echo "<script>console.logNão foi possível conectar o banco de dados.</script> " . mysqli_connect_error();
    // TODO: JS code for a console.log above or something like it.
    // Obs.: shouldn't be an echo that shows up on screen.
}

// Database -> Variables
try {
    $bannerList         = $db->selectAllFromTable("banners");
    $vehicleList        = $db->selectAllFromTable("vehicles");
    $brandList          = $db->selectAllFromTable("brands");
    $modelList          = $db->selectAllFromTable("models");
    $vehiclePhotosList  = $db->selectAllFromTable("vehicle_photos");
} catch (Exception) {
    // echo "SQL ERROR " . mysqli_error($connection);
    // TODO: JS code for a console.log above or something like it.
    // Obs.: shouldn't be an echo that shows up on screen.
}

// Master layout
$layout = file_get_contents("templates/partials/layout.html");

// Partial templates -> Variables
$head              = file_get_contents("templates/partials/head.html");
$header            = file_get_contents("templates/partials/header.html");
$footer            = file_get_contents("templates/partials/footer.html");

$banner            = file_get_contents("templates/partials/banner.html");
$stockHeader       = file_get_contents("templates/partials/stockHeader.html");
$location          = file_get_contents("templates/partials/location.html");
$addVehicleForm    = file_get_contents("templates/partials/addVehicleForm.html");
$updateVehicleForm = file_get_contents("templates/partials/updateVehicleForm.html");
$deleteVehicleForm = file_get_contents("templates/partials/deleteVehicleForm.html");



// Content preparation
$content = '';

if ($_GET['pagina'] === "home") {
    $content .= $banner;
    $content .= $stockHeader;
    $content .= createFilterDropdownMenu($brandList);
    $content .= createVehicleGrid($connection, $vehicleList);
    $content .= "</div>";
}

if ($_GET['pagina'] === "localizacao") {
    $content .= $banner;
    $content .= $location;
}

if ($_GET['pagina'] === "adicionar-veiculo") {
}

if ($_GET['pagina'] === "atualizar-veiculo") {
}

if ($_GET['pagina'] === "remover-veiculo") {
}



// Layout replaces
$layout = str_replace("{HEAD}",    (string)$head,    $layout);
$layout = str_replace("{HEADER}",  (string)$header,  $layout);
$layout = str_replace("{CONTENT}", (string)$content, $layout);
$layout = str_replace("{FOOTER}",  (string)$footer,  $layout);



// Display
echo $layout;
