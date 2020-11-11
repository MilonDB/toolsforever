<?php

include "database.php";
include "fieldval.php";
// start session en zorgt dat loggedin true is.
session_start();

$db = new database('localhost', 'root', '', 'toolsforever', 'utf8');

if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    echo "U heeft deze rechten niet!";
    header('refresh:3;index.php');
    exit;
}

$_SESSION['loggedin'] = true;
$username = $_SESSION['username'];
echo "Ingelogd als:     " . $username;

if (isset($_POST['submit'])) {
    $fields = ['voorletters', 'voorvoegsels', 'achternaam', 'username', 'password'];



    $validate = new fieldVal();
    $check =  $validate->filled_fields($fields);



    //Met deze if statement wordt gecheckt of de velden ingevuld zijn. Zo ja wordt de database connectie gemaakt en de data ingevoerd.
    if ($check) {

        $voorletters = trim(strtolower($_POST['voorletters']));
        $voorvoegsels = trim(strtolower($_POST['voorvoegsels']));
        $achternaam = trim(strtolower($_POST['achternaam']));
        $username = trim(strtolower($_POST['username']));
        $password = trim(strtolower($_POST['password']));
        $repassword = trim(strtolower($_POST['repassword']));


        $error = False;

        // als bevestigd is dat de velden ingevuld zijn, wordt nog een check gedaan om te kijken of password en repassword overeenkomen.
        if ($_POST['password'] === $_POST['repassword']) {

            // Maak database connectie aan en voer data in de tabellen.

            $aanmaken = $db->register($voorletters, $voorvoegsels, $achternaam, $username, $password);


            echo "Account aangemaakt! U wordt nu gerefreshed";
            header('refresh:4;url=view_delete_add_user.php');
            exit;
        } else {
            $error = true;
            echo 'wachtwoorden komen niet overeen! Probeer opnieuw!';
        }
    }
}


// Print de session_status. 0 = uit, 1 = none, en 2 = active
// print_r(session_status());





//data ophalen
$userData = $db->fetch_user_data_from_database(NULL);


//data splitten
$columns = array_keys($userData[0]);





// Als account_id en persoon_id geset zijn, worden variableen aangemaakt en met die variabelen wordt bepaald welke rij uit de tabel verwijderd moet worden
if (isset($_GET['medewerkerscode'])) {
    $medewerkerscode = $_GET['medewerkerscode'];
    $deleted = $db->delete_user_from_database($medewerkerscode);

    // redirect terug naar de tabel
    header("refresh:3;view_delete_add_user.php");
    echo "User verwijderd";
    exit;
}






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
                <li class="nav-item">
                    <a class="nav-link" href="voorraad.php">Home</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="view_delete_add_fabriek.php">view/delete/add fabriek</a>
                </li>
                <li class="nav-item ">
                    <a class="nav-link" href="view_delete_add_product.php">view/delete/add product</a>
                </li>
                <li class="nav-item active">
                    <a class="nav-link" href="view_delete_add_user.php">view/delete/add medewerker</a>
                </li>
                <li class="nav-item">
                <li><a href="logout.php">Logout</a></li>
            </ul>
        </div>
    </nav>

    <h6>ADD MEDEWERKER<h6>
    <form method="post" action="view_delete_add_user.php" id="register">
        <input type="text" name="voorletters" id="voorletters" value="<?php echo isset($_POST['voorletters']) ?>" placeholder="voorletters"><br>
        <input type="text" name="voorvoegsels" id="voorvoegsels" value="<?php echo isset($_POST['voorvoegsels']) ? htmlentities($_POST['voorvoegsels']) : ''; ?>" placeholder="voorvoegsel (optioneel)"><br>
        <input type="text" name="achternaam" id="achternaam" placeholder="achternaam" value="<?php echo isset($_POST['achternaam']) ? htmlentities($_POST['achternaam']) : ''; ?>"><br>
        <input type="text" name="username" id="username" placeholder="username" value="<?php echo isset($_POST['username']) ? htmlentities($_POST['username']) : ''; ?>" required><br>
        <input type="password" name="password" id="password" placeholder="password" value="<?php echo isset($_POST['password']) ? htmlentities($_POST['password']) : ''; ?>" required><br>
        <input type="password" name="repassword" id="repassword" placeholder="repeat password" required><br>
        <input type="submit" name='submit' value="add user"><br>
    </form>


    



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
            <?php foreach ($userData as $rows => $row) { ?>
                <?php $row_id = $row['medewerkerscode']; ?>

                <tr>
                    <?php foreach ($row as $user_data) { ?>
                        <td> <?php echo $user_data ?></td>
                    <?php } ?>


                    <td> <a href="view_delete_export_user.php?account_id=<?php echo $row_id; ?>&medewerkerscode=<?php echo $row['medewerkerscode'] ?>" class="del_btn">Delete</a> </td>


                </tr>
            <?php } ?>



        </tbody>
    </table>

    <!-- <form action='view_delete_export_user.php' method='POST'>
        <input type='submit' name='export' value='Export to excel file' />
    </form> -->




</body>


</html>