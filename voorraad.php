<?php

include "database.php";
// start session en zorgt dat loggedin true is.
session_start();

$db = new database('localhost', 'root', '', 'toolsforever', 'utf8');


// Kijkt of je loggedin bent en anders direct terug naar login om te voorkomen dat data zichtbaar wordt
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    echo "U heeft deze rechten niet!";
    header('refresh:3;index.php');
    exit;
}


$_SESSION['loggedin'] = true;
$username = $_SESSION['username'];

// Print de session_status. 0 = uit, 1 = none, en 2 = active
// print_r(session_status());

echo "Ingelogd als: " . $username;
echo "    Hier kunt u de voorraad bekijken.";







// $results = $db->fill_dropdown($productcode);
$voorraadData = $db->fetch_voorraad_data_from_database(NULL, NULL);


//data splitten
$columns = array_keys($voorraadData[0]);


?>

<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">

</head>




<body>

    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <a class="navbar-brand" href="#">Navbar</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav">
                <li class="nav-item active">
                    <a class="nav-link" href="voorraad.php">Home/Voorraad <span class="sr-only">(current)</span></a>
                </li>
                <li class="nav-item active">
                    <a class="nav-link" href="view_delete_add_fabriek.php">view/delete/add fabriek</a>
                </li>
                <li class="nav-item ">
                    <a class="nav-link" href="view_delete_add_product.php">view/delete/add product</a>
                </li>
                <li class="nav-item ">
                    <a class="nav-link" href="view_delete_add_user.php">view/delete/add medewerker</a>
                </li>
                <li class="nav-item">
                <li><a href="logout.php">Logout</a></li>
            </ul>
        </div>
    </nav>

<h4> Voorraad bekijken </h4>
    <table class="table">
        <thead>
            <tr>
                <?php
                // vult kolomnamen in
                foreach ($columns as $column) {
                    echo "<th> $column </th>";
                }
                ?>
            </tr>
        </thead>
        <tbody>

            <!-- Vult rijen in met de data -->
            <?php foreach ($voorraadData as $rows => $row) { ?>
                <?php $row_id = $row['productcode']; ?>

                <tr>
                    <?php foreach ($row as $user_data) { ?>
                        <td> <?php echo $user_data ?></td>
                    <?php } ?>



                </tr>
            <?php } ?>



        </tbody>
    </table>