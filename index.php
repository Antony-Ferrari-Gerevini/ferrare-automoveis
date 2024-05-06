<?php

/*
    TODO: update functions (and function calls) for homepage.
    TODO: console logs for when connection to database fails.
    TODO: template importing from vehicle CRUD files.
    TODO: implement sessions.
*/



// Includes.
include "assets/php/templateBuilder.php";
include "services/database/db.php";

// Adds the '?page=home' query string to the URL if there's none.
if (empty($_SERVER['QUERY_STRING'])) {
    header("Location: {$_SERVER['PHP_SELF']}?pagina=home");
    exit();
}

// Connects to DB.
try {
    $connection = connectToDatabase();
} catch (Exception) {
    // echo "<script>console.logNão foi possível conectar o banco de dados.</script> " . mysqli_connect_error();
    // TODO: JS code for a console.log above or something like it.
    // Obs.: shouldn't be an echo that shows up on screen.
}

// Database -> Variables.
try {
    $bannerList         = selectAllFromTable($connection, "banners");
    $vehicleList        = selectAllFromTable($connection, "vehicles");
    $brandList          = selectAllFromTable($connection, "brands");
    $modelList          = selectAllFromTable($connection, "models");
    $vehiclePhotosList  = selectAllFromTable($connection, "vehicle_photos");
} catch (Exception) {
    // echo "SQL ERROR " . mysqli_error($connection);
    // TODO: JS code for a console.log above or something like it.
    // Obs.: shouldn't be an echo that shows up on screen.
}

// Partial templates -> Variables.
$headerSection            = file_get_contents("templates/partials/header.html");
$footerSection            = file_get_contents("templates/partials/footer.html");
$bannerSection            = file_get_contents("templates/partials/banner.html");
$stockHeaderSection       = file_get_contents("templates/partials/stockHeader.html");
$locationSection          = file_get_contents("templates/partials/location.html");
$addVehicleFormSection    = file_get_contents("templates/partials/addVehicleForm.html");
// $updateVehicleFormSection = file_get_contents("templates/partials/updateVehicleForm.html");
// $deleteVehicleFormSection = file_get_contents("templates/partials/deleteVehicleForm.html");



// Display preparation.
$content = '';

$content .= $headerSection;
$content .= $bannerSection;

if ($_GET['pagina'] === "home") {
    $content .= $stock_header;
    //create_filter_dropdown_menu($brand_list);
    //create_vehicle_grid($vehicle_list, $connection);
}

if ($_GET['pagina'] === "localizacao") {
    $content .= $locationSection;
}

$content .= "</div>";
$content .= $footerSection;



// Display.
echo $content;
