<?php

require_once '../autoload.php';

use Buzzer\Database;
use Buzzer\Locale;

$db = new Database();

$allTeamPasscodes = $db->getAllTeamPasscodes();

$db->close();

$buzzerURL = filter_input(INPUT_SERVER, 'REQUEST_SCHEME') . '://'
    . filter_input(INPUT_SERVER, 'SERVER_NAME')
    . dirname(filter_input(INPUT_SERVER, 'REQUEST_URI')) . '/';

Locale::loadLanguage();

include '../views/passcodes.php';
