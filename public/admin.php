<?php

require_once '../autoload.php';

use Buzzer\Database;
use Buzzer\Locale;

$db = new Database();

$currentBuzzerType = $db->getCurrentBuzzerType();
$currentAnswerVisibility = $db->getScreenAnswerVisibility();
$allTeams = $db->getAllTeams();

$db->close();

Locale::loadLanguage();

include '../views/admin.php';
