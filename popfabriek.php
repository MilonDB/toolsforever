<?php

include "database.php";


//RUN ONCE
$db = new database('localhost', 'root', '', 'toolsforever', 'utf8');

$db->addFabriek('Worx','06123414521');
$db->addFabriek('BlackDecker','061223151421');
$db->addFabriek('EinHell', '0612231523241');
$db->addFabriek('Bosch', '06113413151421');
$db->addFabriek('Karcher', '0616423151421');
$db->addFabriek('Sencys', '065363151421');
?>