<?php

require_once '../autoload.php';

use Buzzer\Database;

$db = new Database();

if (isset($_POST['reset'])) {

    $db->resetGameTurn();

} elseif (isset($_POST['reveal']) && is_numeric($_POST['reveal'])) {

    $visibility = filter_input(INPUT_POST, 'reveal', FILTER_VALIDATE_INT);
    $db->setScreenAnswerVisibility($visibility);

} elseif (isset($_POST['buzzertype']) && is_numeric($_POST['buzzertype'])) {

    $buzzerType = filter_input(INPUT_POST, 'buzzertype',
        FILTER_VALIDATE_INT);
    $db->setCurrentBuzzerType($buzzerType);

}

$currentBuzzerType = $db->getCurrentBuzzerType();
$db->close();

?><!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8"/>
        <title>Buzzer administrion panel</title>
        <style>
            form{
                display: flex;
            }
            fieldset{
                flex: 1;
            }
            label{
                display: block;
            }
        </style>
    </head>
    <body>
        <form action="admin.php" method="post">
            <fieldset>
                <legend>Clear the screen</legend>
                <button type="submit" name="reset">Reset</button>
            </fieldset>
            <fieldset>
                <legend>Reveal or hide all answers</legend>
                <button name="reveal" value="1">Hide</button>
                <button name="reveal" value="2">Reveal</button>
            </fieldset>
            <fieldset>
                <legend>Buzzer type</legend>
                <label><input type="radio" name="buzzertype" value="1"<?= ($currentBuzzerType === 1) ? ' checked="checked"' : '' ?>/>Buzzer</label>
                <label><input type="radio" name="buzzertype" value="2"<?= ($currentBuzzerType === 2) ? ' checked="checked"' : '' ?>/>Text field</label>
                <label><input type="radio" name="buzzertype" value="3"<?= ($currentBuzzerType === 3) ? ' checked="checked"' : '' ?>/>3 options</label>
                <button type="submit">OK</button>
            </fieldset>
        </form>
    </body>
</html>
