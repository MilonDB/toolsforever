<?php

include "fieldval.php";
include "database.php";

//Na de button press wordt de array aangemaakt met de fieldnames
if (isset($_POST['submit'])) {
    $fields = ['voorletters','voorvoegsels','achternaam','username','password'];



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
            $db = new database('localhost', 'root', '', 'toolsforever', 'utf8');
            $aanmaken = $db->register($voorletters, $voorvoegsels, $achternaam, $username, $password);
            

            echo "Account aangemaakt! U wordt nu geredirect naar login pagina";
            header('refresh:5;url=index.php');
            exit;
        } else {
            $error = true;
            echo 'wachtwoorden komen niet overeen! Probeer opnieuw!';
        }
    }
}



?>


<html>

<head>
    <title>Register</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">

</head>

<body>

    <form method="post" action="signup.php" id="register">
        <input type="text" name="voorletters" id="voorletters" value="<?php echo isset($_POST['voorletters']) ?>" placeholder="voorletters"><br>
        <input type="text" name="voorvoegsels" id="voorvoegsels" value="<?php echo isset($_POST['voorvoegsels']) ? htmlentities($_POST['voorvoegsels']) : ''; ?>" placeholder="voorvoegsel (optioneel)"><br>
        <input type="text" name="achternaam" id="achternaam" placeholder="achternaam" value="<?php echo isset($_POST['achternaam']) ? htmlentities($_POST['achternaam']) : ''; ?>"><br>
        <input type="text" name="username" id="username" placeholder="username" value="<?php echo isset($_POST['username']) ? htmlentities($_POST['username']) : ''; ?>" required><br>
        <input type="password" name="password" id="password" placeholder="password" value="<?php echo isset($_POST['password']) ? htmlentities($_POST['password']) : ''; ?>" required><br>
        <input type="password" name="repassword" id="repassword" placeholder="repeat password" required><br>
        <input type="submit" name='submit' value="register"><br>
        <a href="index.php">Heb je al een account? Ga terug naar login.</a>
    </form>

</body>

</html>