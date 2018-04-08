<?php

require_once '../autoload.php';

use Buzzer\Database;
use Buzzer\Locale;

$db = null;
$loggedIn = false;

setcookie('cookies-enabled', '?');

if (isset($_COOKIE['team']) && is_numeric($_COOKIE['team'])) {
    
    $db = new Database();
    $teamID = filter_input(INPUT_COOKIE, 'team', FILTER_SANITIZE_NUMBER_INT);
    
    if ($db->getIsValidTeamID($teamID)) {
        $loggedIn = true;
    } else {
        setcookie('team', '', 1);
    }

} elseif (isset($_POST['login'], $_POST['teamname'], $_POST['passcode'],
    $_COOKIE['cookies-enabled'])) {
    
    $db = new Database();
    $teamName = trim(filter_input(INPUT_POST, 'teamname', FILTER_UNSAFE_RAW));
    $passcode = filter_input(INPUT_POST, 'passcode', FILTER_SANITIZE_STRING);
    
    $teamID = $db->setTeamName($teamName, $passcode);
    
    if ($teamID !== '') {
        $loggedIn = true;
        setcookie('team', $teamID);
        setcookie('cookies-enabled', '', 1);
    } else {
        $db->close();
    }

}

Locale::loadLanguage();

if ($loggedIn) {

    if ($db === null){
        $db = new Database();
    }
    
    // check the answer
    if (isset($_POST['team'], $_POST['answer']) && is_numeric($_POST['team'])
        && is_string($_POST['answer']) && (mb_strlen($_POST['answer']) <= 50)) {
        
        // insert answer in database
        $teamID = filter_input(INPUT_POST, 'team', FILTER_SANITIZE_NUMBER_INT);
        $answer = trim(filter_input(INPUT_POST, 'answer', FILTER_UNSAFE_RAW));
        
        $db->setAnswer($teamID, $answer);
    }
    
    $buzzerType = $db->getCurrentBuzzerType();
    
    $db->close();
    
    if (!isset($teamID)) {
        $teamID = filter_input(INPUT_COOKIE, 'team',
            FILTER_SANITIZE_NUMBER_INT);
    }
    
    header('Cache-Control: no-cache');
    
    // show the correct buzzer type
    include "../views/buzzer$buzzerType.php";

} else {

    // first visit or incorrect login
    include '../views/teamlogin.php';
}
