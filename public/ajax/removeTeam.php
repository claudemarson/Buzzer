<?php

require_once '../../autoload.php';

use Buzzer\Database;

if (isset($_POST['teamid']) && is_numeric($_POST['teamid'])) {
    
    $teamID = filter_input(INPUT_POST, 'teamid', FILTER_VALIDATE_INT);
    
    $db = new Database();
    $db->removeTeam($teamID);
    $db->close();
    
    header('No Content', true, 204);
    
} else {
    
    header('Bad Request', true, 400);
    
}
