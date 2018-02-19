<?php

require_once '../../autoload.php';

use Buzzer\Database;

$db = new Database();
$db->resetGameTurn();
$db->close();

header('No Content', true, 204);
