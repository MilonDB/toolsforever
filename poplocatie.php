<?php

include "database.php";


$db = new database('localhost', 'root', '', 'toolsforever', 'utf8');


// RUN ONCE
$db->populate_locatie('Eindhoven');
$db->populate_locatie('Rotterdam');
$db->populate_locatie('Almere');


?>