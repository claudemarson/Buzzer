<?php

require_once '../autoload.php';

use Buzzer\Database;
use Buzzer\Locale;

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

Locale::loadLanguage();

?><!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8"/>
        <title><?= LANG_BUZZER_ADMINISTRATION ?></title>
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
                <legend><?= LANG_CLEAR_THE_SCREEN ?></legend>
                <button type="submit" name="reset"><?= LANG_RESET ?></button>
            </fieldset>
            <fieldset>
                <legend><?= LANG_REVEAL_ALL_ANSWERS ?></legend>
                <button name="reveal" value="1"><?= LANG_HIDE ?></button>
                <button name="reveal" value="2"><?= LANG_REVEAL ?></button>
            </fieldset>
            <fieldset>
                <legend><?= LANG_BUZZER_TYPE ?></legend>
                <label><input type="radio" name="buzzertype" value="1"<?= ($currentBuzzerType === 1) ? ' checked="checked"' : '' ?>/><?= LANG_BUZZER ?></label>
                <label><input type="radio" name="buzzertype" value="2"<?= ($currentBuzzerType === 2) ? ' checked="checked"' : '' ?>/><?= LANG_TEXT_FIELD ?></label>
                <label><input type="radio" name="buzzertype" value="3"<?= ($currentBuzzerType === 3) ? ' checked="checked"' : '' ?>/><?= LANG_3_OPTIONS ?></label>
                <button type="submit"><?= LANG_OK ?></button>
            </fieldset>
        </form>
    </body>
</html>
