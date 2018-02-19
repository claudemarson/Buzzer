<?php

require_once '../../autoload.php';

use Buzzer\Database;

if (isset($_POST['buzzertype']) && is_numeric($_POST['buzzertype'])) {

    $buzzerType = filter_input(INPUT_POST, 'buzzertype', FILTER_VALIDATE_INT);
    
    $db = new Database();
    $db->setCurrentBuzzerType($buzzerType);
    $db->close();
    
    header('No Content', true, 204);
    
} else {
    
    header('Bad Request', true, 400);
    
}
