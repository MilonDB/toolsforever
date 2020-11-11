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

    if (isset($_POST['submit'])) {
        $fields = ['product', 'type', 'fabriekscode', 'inkoopprijs', 'verkoopprijs'];



        $validate = new fieldVal();
        $check =  $validate->filled_fields($fields);



        //Met deze if statement wordt gecheckt of de velden ingevuld zijn. Zo ja wordt de database connectie gemaakt en de data ingevoerd.
        if ($check) {

            $product = trim(strtolower($_POST['product']));
            $type = trim(strtolower($_POST['type']));
            $fabriekscode = $_POST['fabriekscode'];
            $inkoopprijs = trim(strtolower($_POST['inkoopprijs']));
            $verkoopprijs = trim(strtolower($_POST['verkoopprijs']));

            $error = false;


            $aanmaken = $db->add_product($product, $type, $fabriekscode, $inkoopprijs, $verkoopprijs);
        } else {
            $error = true;
        }
    }


    if (isset($_POST['edit'])) {
        $fields = ['product', 'type', 'fabriekscode', 'inkoopprijs', 'verkoopprijs', 'productcode'];



        $validate = new fieldVal();
        $check =  $validate->filled_fields($fields);



        //Met deze if statement wordt gecheckt of de velden ingevuld zijn. Zo ja wordt de database connectie gemaakt en de data ingevoerd.
        if ($check) {

            $product = trim(strtolower($_POST['product']));
            $type = trim(strtolower($_POST['type']));
            $fabriekscode = $_POST['fabriekscode'];
            $inkoopprijs = trim(strtolower($_POST['inkoopprijs']));
            $verkoopprijs = trim(strtolower($_POST['verkoopprijs']));
            $productcode = $_POST['productcode'];

            $error = false;


            $aanmaken = $db->alter_product_data($product, $type, $fabriekscode, $inkoopprijs, $verkoopprijs, $productcode);
        } else {
            $error = true;
        }
    }

    if (isset($_GET['productcode'])) {
        $productcode = $_GET['productcode'];
        echo $productcode;
        $deleted = $db->delete_artikel_from_database($productcode, $fabriekscode);

        // redirect terug naar de tabel
        header("refresh:3;view_delete_add_product.php");
        echo "Product verwijderd";
        exit;
    }




    $artikelData = $db->fetch_artikel_data_from_database(NULL);


    //data splitten
    $columns = array_keys($artikelData[0]);








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
                     <a class="nav-link" href="view_delete_add_fabriek.php">view/delete/add/edit fabriek</a>
                 </li>
                 <li class="nav-item active">
                     <a class="nav-link" href="view_delete_add_product.php">view/delete/add/edit product</a>
                 </li>
                 <li class="nav-item ">
                     <a class="nav-link" href="view_delete_add_user.php">view/delete/add medewerker</a>
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
             <?php foreach ($artikelData as $rows => $row) { ?>
                 <?php $row_id = $row['productcode']; ?>

                 <tr>
                     <?php foreach ($row as $user_data) { ?>
                         <td> <?php echo $user_data ?></td>
                     <?php } ?>


                     <td> <a href="view_delete_add_product.php?productcode=<?php echo $row_id; ?>" class="del_btn">Delete</a> </td>


                 </tr>
             <?php } ?>



         </tbody>
     </table>



     <!-- $product, $type, $fabriekscode, $inkoopprijs, $verkoopprijs -->
     <h5>Add<h5>
             <form method="post" action="view_delete_add_product.php" id="add">
                 <input type="text" name="product" id="product" value="<?php echo isset($_POST['product']) ? htmlentities($_POST['product']) : '';  ?>" placeholder="product"><br>
                 <input type="text" name="type" id="type" value="<?php echo isset($_POST['type']) ? htmlentities($_POST['type']) : ''; ?>" required placeholder="type"><br>
                 <label for="fabriek">kies fabriek:</label>
                 <select id="fabriekscode" name="fabriekscode">
                     <option value=1>worx</option>
                     <option value=2>Blackdecker</option>
                     <option value=3>EinHell</option>
                     <option value=4>Bosch</option>
                     <option value=8>Karcher</option>
                     <option value=6>Sencys</option>
                 </select><br>
                 <input type="text" name="inkoopprijs" id="inkoopprijs" placeholder="inkoopprijs" value="<?php echo isset($_POST['inkoopprijs']) ? htmlentities($_POST['inkoopprijs']) : ''; ?>" required><br>
                 <input type="text" name="verkoopprijs" id="verkoopprijs" placeholder="verkoopprijs" value="<?php echo isset($_POST['verkoopprijs']) ? htmlentities($_POST['verkoopprijs']) : ''; ?>" required><br>
                 <input type="submit" name='submit' value="submit"><br>
             </form>
             <br><br><br>

             <h5>Edit<h5>
                     <form method="post" action="view_delete_add_product.php" id="add">
                         <input type="text" name="product" id="product" value="<?php echo isset($_POST['product']) ? htmlentities($_POST['product']) : '';  ?>" placeholder="product"><br>
                         <input type="text" name="type" id="type" value="<?php echo isset($_POST['type']) ? htmlentities($_POST['type']) : ''; ?>" required placeholder="type"><br>
                         <label for="fabriek">kies fabriek:</label>
                         <select id="fabriekscode" name="fabriekscode">
                             <option value=1>worx</option>
                             <option value=2>Blackdecker</option>
                             <option value=3>EinHell</option>
                             <option value=4>Bosch</option>
                             <option value=5>Karcher</option>
                             <option value=6>Sencys</option>
                         </select><br>
                         <input type="text" name="inkoopprijs" id="inkoopprijs" placeholder="inkoopprijs" value="<?php echo isset($_POST['inkoopprijs']) ? htmlentities($_POST['inkoopprijs']) : ''; ?>" required><br>
                         <input type="text" name="verkoopprijs" id="verkoopprijs" placeholder="verkoopprijs" value="<?php echo isset($_POST['verkoopprijs']) ? htmlentities($_POST['verkoopprijs']) : ''; ?>" required><br>
                         <label for="fabriek">kies product om te wijzigen:</label>
                         <select id="productcode" name="productcode">
                             <option value=10>lego</option>
                             <option value=11>neba</option>
                             <option value=12>play</option>
                             <option value=13>xbox</option>
                             <option value=17>nintendo</option>
                         </select><br>
                         <input type="submit" name='edit' value="edit"><br>
                     </form>
 </body>


 </html>