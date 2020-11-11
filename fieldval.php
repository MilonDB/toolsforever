<?php

// CLASS FOR FIELDVALIDATION

class fieldVal
{
    // functie om te checken of fields gevuld zijn
    public function filled_fields($fields)
    {

        // als $fields een array is, gaat de error die normaal TRUE is op FALSE. 
        if (is_array($fields)) {

            $error = False;

            // Loopt over de field heen om te kijken of velden ingevuld zijn. Zo nee, wordt $error true.
            foreach ($fields as $fieldname) {
                if (!isset($_POST[$fieldname]) || empty($_POST[$fieldname])) {
                    echo "Error gevonden, velden zijn niet correct ingevuld!";
                    $error = True;
                }
            }
            // als er geen error is dan return true.
            if (!$error) {
                return true;
            }
            return false;
        } else {
            echo "Geen array gevonden.";
        }
    }
}
