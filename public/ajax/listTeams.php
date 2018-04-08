<?php

require_once '../../autoload.php';

use Buzzer\Database;
use Buzzer\Locale;

$db = new Database();
$allTeams = $db->getAllTeams();
$db->close();

Locale::loadLanguage();

include '../../views/listTeams.php';
