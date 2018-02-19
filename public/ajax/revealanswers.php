<?php

require_once '../../autoload.php';

use Buzzer\Database;

if (isset($_POST['reveal']) && is_numeric($_POST['reveal'])) {

    $visibility = filter_input(INPUT_POST, 'reveal', FILTER_VALIDATE_BOOLEAN);
    
    $db = new Database();
    $db->setScreenAnswerVisibility($visibility);
    $db->close();
    
    header('No Content', true, 204);
    
} else {
    
    header('Bad Request', true, 400);
    
}
