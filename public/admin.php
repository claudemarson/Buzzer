<?php

require_once '../autoload.php';

use Buzzer\Database;
use Buzzer\Locale;

$db = new Database();

$currentBuzzerType = $db->getCurrentBuzzerType();
$currentAnswerVisibility = $db->getScreenAnswerVisibility();

$db->close();

Locale::loadLanguage();

?><!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8"/>
        <link href="./css/admin.css" rel="stylesheet" type="text/css"/>
        <script src="./js/admin.js" defer="defer"></script>
        <title><?= LANG_BUZZER_ADMINISTRATION ?></title>
    </head>
    <body>
        <h1><?= LANG_BUZZER_ADMINISTRATION ?></h1>
        <div class="flex-container">
            <section>
                <h2><?= LANG_CLEAR_THE_SCREEN ?></h2>
                <div>
                    <form>
                        <button type="button" id="reset_screen"><?= LANG_RESET ?></button>
                    </form>
                </div>
            </section>
            <section>
                <h2><?= LANG_REVEAL_ALL_ANSWERS ?></h2>
                <div>
                    <form id="reveal_answers_form">
                        <button type="button" id="hide_answers" value="0"<?= (!$currentAnswerVisibility) ? ' class="active"' : '' ?>><?= LANG_HIDE ?></button>
                        <button type="button" id="reveal_answers" value="1"<?= ($currentAnswerVisibility) ? ' class="active"' : '' ?>><?= LANG_REVEAL ?></button>
                    </form>
                </div>
            </section>
            <section>
                <h2><?= LANG_BUZZER_TYPE ?></h2>
                <div>
                    <form id="buzzer_type_form">
                        <label<?= ($currentBuzzerType === 1) ? ' class="active"' : '' ?>><input type="radio" name="buzzertype" value="1"<?= ($currentBuzzerType === 1) ? ' checked="checked"' : '' ?>/><?= LANG_BUZZER ?></label>
                        <label<?= ($currentBuzzerType === 2) ? ' class="active"' : '' ?>><input type="radio" name="buzzertype" value="2"<?= ($currentBuzzerType === 2) ? ' checked="checked"' : '' ?>/><?= LANG_TEXT_FIELD ?></label>
                        <label<?= ($currentBuzzerType === 3) ? ' class="active"' : '' ?>><input type="radio" name="buzzertype" value="3"<?= ($currentBuzzerType === 3) ? ' checked="checked"' : '' ?>/><?= LANG_3_OPTIONS ?></label>
                        <button type="button" id="change_buzzer_type"><?= LANG_OK ?></button>
                    </form>
                </div>
            </section>
        </div>
    </body>
</html>
