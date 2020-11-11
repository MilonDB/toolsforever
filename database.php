<?php

// Class aangemaakt
class database
{


    //instantieer de database
    private $host;
    private $username;
    private $password;
    private $database;
    private $charset;
    private $db;




    // Constructor zodat met deze data gewerkt kan worden.
    function __construct($host, $username, $password, $database, $charset)
    {
        $this->host = $host;
        $this->username = $username;
        $this->password = $password;
        $this->database = $database;
        $this->charset = $charset;

        // Try and catch voor poging om database te verbinden
        try {
            // dsn = data source name
            $conn = "mysql:host=$this->host;dbname=$this->database;charset=$this->charset";
            $this->db = new PDO($conn, $this->username, $this->password);
            // echo "Database connectie gemaakt.";
        } catch (PDOException $e) {
            echo $e->getMessage();
            exit("Error");
        }
    }

    // Functie om te checken of account al bestaat.
    private function existing_username_check($username)
    {

        $stmt = $this->db->prepare('SELECT * FROM medewerker WHERE username=:username');
        $stmt->execute(['username' => $username]);
        $result = $stmt->fetch();

        if (is_array($result) && count($result) > 0) {
            return false;
        }

        return true;
    }

    //Register functie
    public function register($voorletters, $voorvoegsels, $achternaam, $username, $password)
    {


        try {
            //Initiates a transaction to the database
            $this->db->beginTransaction();

            $sqlSignup = "INSERT INTO medewerker VALUES(NULL, :voorletters, :voorvoegsels, :achternaam, :username, :password)";
            //  Als username al bestaat dan moet er een andere gekozen worden.
            if (!$this->existing_username_check($username)) {
                echo "username bestaat al! probeer opnieuw";
                header('refresh:5;url=signup.php');
                exit;
            }

            $stmt = $this->db->prepare($sqlSignup);

            //sla hashed password op in een variabele
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);

            $stmt->execute(['voorletters' => $voorletters, 'voorvoegsels' => $voorvoegsels, 'achternaam' => $achternaam, 'username' => $username, 'password' => $hashed_password]);

            //Commits a transaction to the database
            $this->db->commit();
        } catch (PDOException $e) {
            $this->db->rollback();
            echo "Signup mislukt, er klopt iets niet,: " . $e->getMessage();
        }
    }


    //Fabriektoevoegen voor het popscript
    public function addFabriek($fabriek, $telefoon)
    {
        try {

            $this->db->beginTransaction();

            $sqlAddFabriek = "INSERT INTO fabriek VALUES (NULL, :fabriek, :telefoon)";

            $stmt = $this->db->prepare($sqlAddFabriek);

            $stmt->execute(['fabriek' => $fabriek, 'telefoon' => $telefoon]);

            $this->db->commit();
        } catch (PDOException $e) {
            $this->db->rollback();
            echo "Add fabriek werkt niet";
        }
    }


    //locaties toevoegen voor het popscript
    public function populate_locatie($locatie)
    {
        try {

            $this->db->beginTransaction();

            $sqlAddLocatie = "INSERT INTO locatie VALUES (NULL, :locatie)";

            $stmt = $this->db->prepare($sqlAddLocatie);

            $stmt->execute(['locatie' => $locatie]);

            $this->db->commit();
        } catch (PDOException $e) {
            $this->db->rollback();
            echo "Add locatie werkt niet";
        }
    }





    //product toevoegen
    public function add_product($product, $type, $fabriekscode, $inkoopprijs, $verkoopprijs)
    {

        try {
            $this->db->beginTransaction();


            $sqlAddProduct = "INSERT INTO artikel VALUES(NULL, :product, :type, :fabriekscode, :inkoopprijs, :verkoopprijs)";

            $stmt = $this->db->prepare($sqlAddProduct);

            $stmt->execute(['product' => $product, 'type' => $type, 'fabriekscode' => $fabriekscode, 'inkoopprijs' => $inkoopprijs, 'verkoopprijs' => $verkoopprijs]);


            $this->db->commit();


            echo "Product added to database! This page will refresh";
            header('refresh:4;url=view_delete_add_product.php');
        } catch (PDOException $e) {
            $this->db->rollback();
            echo "Signup mislukt, er klopt iets niet,: " . $e->getMessage();
        }
    }


    public function fetch_voorraad_data_from_database($locatiecode, $productcode)
    {

        $sqlFetchVoorraad = "SELECT * FROM voorraad";

        $stmt = $this->db->prepare($sqlFetchVoorraad);

        $stmt->execute();

        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $results;
        print_r($results);
    }


    public function fetch_locatie_data_from_database($locatiecode)
    {

        $sqlFetchLocatie = "SELECT locatiecode FROM locatie";

        $stmt = $this->db->prepare($sqlFetchLocatie);

        $stmt->execute();

        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $results;
        print_r($results);
    }



    public function fetch_artikel_data_from_database($productcode)
    {

        $sqlFetchArtikel = "SELECT * FROM artikel";

        $stmt = $this->db->prepare($sqlFetchArtikel);

        $stmt->execute();

        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $results;
        print_r($results);
    }

    public function delete_voorraad_from_database($productcode)
    {

        try {

            $stmt = $this->db->prepare("DELETE FROM voorraad WHERE productcode=:productcode");
            $stmt->execute(['productcode' => $productcode]);
            print_r($stmt);
        } catch (PDOException $e) {
            $this->db->rollback();
            echo 'Error: ' . $e->getMessage();
        }
    }

    public function delete_voorraad_from_database2($fabriekscode)
    {

        try {

            $stmt = $this->db->prepare("DELETE FROM voorraad WHERE artikel.fabriekscode=:fabriekscode");
            $stmt->execute(['fabriekscode' => $fabriekscode]);
            print_r($stmt);
        } catch (PDOException $e) {
            $this->db->rollback();
            echo 'Error: ' . $e->getMessage();
        }
    }
    

    public function delete_artikel_from_database($productcode)
    {

        try {
            $this->db->beginTransaction();

            $this->delete_voorraad_from_database($productcode);

            $stmt = $this->db->prepare("DELETE FROM artikel WHERE productcode=:productcode");
            $stmt->execute(['productcode' => $productcode]);
            print_r($stmt);

            $this->db->commit();
        } catch (PDOException $e) {
            $this->db->rollback();
            echo 'Error: ' . $e->getMessage();
        }
    }

    public function delete_fabriek_from_database($fabriekscode)
    {

        try {
            $this->db->beginTransaction();

            $this->delete_voorraad_from_database2($fabriekscode);

            $stmt = $this->db->prepare("DELETE FROM fabriek WHERE fabriekscode=:fabriekscode");
            $stmt->execute(['fabriekscode' => $fabriekscode]);
            print_r($stmt);

            $this->db->commit();
        } catch (PDOException $e) {
            $this->db->rollback();
            echo 'Error: ' . $e->getMessage();
        }
    }


    public function delete_user_from_database($medewerkerscode)
    {

        try {
            $this->db->beginTransaction();

            $stmt = $this->db->prepare("DELETE FROM medewerker WHERE medewerkerscode=:medewerkerscode");
            $stmt->execute(['medewerkerscode' => $medewerkerscode]);
            print_r($stmt);

            $this->db->commit();
        } catch (PDOException $e) {
            $this->db->rollback();
            echo 'Error: ' . $e->getMessage();
        }
    }

    public function fetch_user_data_from_database($medewerkerscode)
    {

        $sqlFetchuser = "SELECT medewerkerscode, voorletters, voorvoegsels, achternaam, username FROM medewerker";

        $stmt = $this->db->prepare($sqlFetchuser);

        $stmt->execute();

        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $results;
        print_r($results);
    }


    public function fetch_fabriek_data_from_database($fabriekscode)
    {

        $sqlFetchFabriek = "SELECT * FROM fabriek";

        $stmt = $this->db->prepare($sqlFetchFabriek);

        $stmt->execute();

        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $results;
        print_r($results);
    }


    public function alter_fabriek_data($fabriekscode, $fabriek, $telefoon)
    {
        try {

            $this->db->beginTransaction();

            $sqlAlterFabriek = "UPDATE fabriek SET fabriek = :fabriek, telefoon = :telefoon WHERE fabriekscode = :fabriekscode";

            $stmt = $this->db->prepare($sqlAlterFabriek);

            $stmt->execute(['fabriekscode' => $fabriekscode, 'fabriek' => $fabriek, 'telefoon' => $telefoon]);

            $this->db->commit();

            echo "Fabriek altered";
        } catch (PDOException $e) {
            $this->db->rollback();
            echo 'Error: ' . $e->getMessage();
        }
    }


    public function alter_product_data($product, $type, $fabriekscode, $inkoopprijs, $verkoopprijs, $productcode)
    {
        try {

            $this->db->beginTransaction();

            $sqlAlterArtikel = "UPDATE artikel SET product = :product, type = :type, fabriekscode = :fabriekscode, inkoopprijs = :inkoopprijs, verkoopprijs = :verkoopprijs WHERE productcode = :productcode";

            $stmt = $this->db->prepare($sqlAlterArtikel);

            $stmt->execute(['product' => $product, 'type' => $type, 'fabriekscode' => $fabriekscode, 'inkoopprijs' => $inkoopprijs, 'verkoopprijs' => $verkoopprijs, 'productcode'=> $productcode]);

            $this->db->commit();

            echo "product altered";
        } catch (PDOException $e) {
            $this->db->rollback();
            echo 'Error: ' . $e->getMessage();
        }
    }




    public function logIn($username, $password)
    {
        // user_check is nu niets meer dan een string, wij preparen hem om uitgevoerd te worden. De echo is een pure check.
        $user_check = "SELECT password FROM medewerker WHERE username = :username";
        echo $user_check . "<br>" . "<br>";


        // user_check wordt geprepared om uitgevoerd te worden, en is nu een query.
        $stmt = $this->db->prepare($user_check);


        // voer user_check uit met de ingevoerde username value
        $stmt->execute(['username' => $username]);

        // haal uit de database 
        $res = $stmt->fetch();


        $hpwd = $res['password'];
        $user_exists = false;

        if ($username && password_verify($password, $hpwd)) {
            $user_exists = true;
            echo "welkom: " . $username;
            session_start();
            // $_SESSION['id'] = $res['id'];
            $_SESSION['username'] = $username;
            $_SESSION['loggedin'] = true;
            header('refresh:3;url=voorraad.php');
        }
        // als de username en wachtwoordcombinatie overeenkomen met data uit de database, en de user dus bestaat, wordt deze code uitgevoerd
        else {
            echo "Invalid username and/or password, or user does not exist" . '<br>';
        }
    }
}
