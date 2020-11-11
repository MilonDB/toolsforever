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
    echo "Ingelogd als: " . $username;


    // Print de session_status. 0 = uit, 1 = none, en 2 = active
    // print_r(session_status());
    //Met deze if statement wordt gecheckt of de velden ingevuld zijn. Zo ja wordt de database connectie gemaakt en de data ingevoerd.
    if (isset($_POST['submit'])) {
        $fields = ['fabriekscode', 'fabriek', 'telefoon'];



        $validate = new fieldVal();
        $check =  $validate->filled_fields($fields);



        //Met deze if statement wordt gecheckt of de velden ingevuld zijn. Zo ja wordt de database connectie gemaakt en de data ingevoerd.
        if ($check) {
            
            $fabriekscode = $_POST['fabriekscode'];
            $fabriek = trim(strtolower($_POST['fabriek']));
            $telefoon = trim(strtolower($_POST['telefoon']));

            $error = false;
            echo "check";

            $edit = $db->alter_fabriek_data($fabriekscode, $fabriek, $telefoon);

            header("refresh:3;view_delete_add_fabriek.php");
            echo "  fabriek altered";
        } else {
            $error = true;
        }
    }


    if (isset($_GET['fabriekscode'])) {
        $fabriekscode = $_GET['fabriekscode'];
        echo $fabriekscode;
        $deleted = $db->delete_fabriek_from_database($fabriekscode);

        // redirect terug naar de tabel
        header("refresh:3;view_delete_add_fabriek.php");
        echo "  fabriek verwijderd";
        exit;
    }




    $fabriekData = $db->fetch_fabriek_data_from_database(NULL);


    //data splitten
    $columns = array_keys($fabriekData[0]);

   






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
                     <a class="nav-link" href="voorraad.php">Home/voorraad</a>
                 </li>
                 <li class="nav-item">
                     <a class="nav-link" href="view_delete_add_user.php">view/delete/add medewerker</a>
                 </li>
                 <li class="nav-item active">
                     <a class="nav-link" href="view_delete_add_product.php">view/delete/add fabriek<span class="sr-only">(current)</span></a>
                 </li>
                 <li class="nav-item">
                     <a class="nav-link" href="view_delete_add_product.php">view/delete/add product</a>
                 </li>
                 <li class="nav-item">
                 <li><a href="logout.php">Logout</a></li>
             </ul>
         </div>
     </nav>


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
             <?php foreach ($fabriekData as $rows => $row) { ?>
                 <?php $row_id = $row['fabriekscode']; ?>

                 <tr>
                     <?php foreach ($row as $user_data) { ?>
                         <td> <?php echo $user_data ?></td>
                     <?php } ?>


                     <td> <a href="view_delete_add_fabriek.php?fabriekscode=<?php echo $row_id; ?>" class="del_btn">Delete</a> </td>


                 </tr>
             <?php } ?>



         </tbody>
     </table>


     <form method="post" action="view_delete_add_fabriek.php" id="edit">
         <input type="text" name="fabriek" id="fabriek" value="<?php echo isset($_POST['fabriek']) ? htmlentities($_POST['fabriek']) : '';  ?>" placeholder="fabrieknaam"><br>
         <input type="text" name="telefoon" id="telefoon" value="<?php echo isset($_POST['telefoon']) ? htmlentities($_POST['telefoon']) : ''; ?>" required placeholder="telefoon"><br>
         <label for="fabriek">kies fabriek om te wijzigen:</label>
         <select id="fabriekscode" name="fabriekscode">
             <option value=1>testa</option>
             <option value=2>Blackdecker</option>
             <option value=4>bosch</option>
             <option value=6>Hoezo</option>
             <option value=8>Karcher</option>
             <option value=12>VerwijderMij!</option>
         </select><br>
         <input type="submit" name='submit' value="submit"><br>
     </form>



     <!-- $product, $type, $fabriekscode, $inkoopprijs, $verkoopprijs -->

 </body>


 </html>