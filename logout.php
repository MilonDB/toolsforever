<?php

//logout in aparte file zodat alles zeker weten kwijt is.

//continue de session
session_start();


unset($_SESSION);

//destroy alle data uit de session
session_destroy();

//beeindig sessie
session_write_close();
echo "U wordt nu naar de login page gebracht";

//redirect terug naar login
header("refresh:3;url=index.php");
die;

?>