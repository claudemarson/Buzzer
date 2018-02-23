<?php

require_once '../../autoload.php';

use Buzzer\Database;

$db = new Database();
$db->addTeam();
$db->close();

header('Created', true, 201);
