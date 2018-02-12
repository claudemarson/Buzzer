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
    switch ($buzzerType) {
        case 1:
            // Buzzer
            ?><!DOCTYPE html>
            <html class="buzzer1">
                <head>
                    <meta charset="utf-8"/>
                    <link href="./buzzer.css" rel="stylesheet" type="text/css"/>
                    <meta name="viewport" content="width=device-width, user-scalable=no"/>
                    <title><?= LANG_BUZZER ?></title>
                </head>
                <body>
                    <form action="./" method="post">
                        <input type="hidden" name="buzzertype" value="1"/>
                        <input type="hidden" name="team" value="<?= htmlspecialchars($teamID) ?>"/>
                        <button type="submit" name="answer" value="X">BAZZAAA</button>
                    </form>
                </body>
            </html><?php

            break;

        case 2:
            // Text field
            ?><!DOCTYPE html>
            <html class="buzzer2">
                <head>
                    <meta charset="utf-8"/>
                    <link href="./buzzer.css" rel="stylesheet" type="text/css"/>
                    <meta name="viewport" content="width=device-width, user-scalable=no"/>
                    <title><?= LANG_BUZZER ?></title>
                </head>
                <body>
                    <form action="./" method="post">
                        <input type="hidden" name="buzzertype" value="2"/>
                        <input type="hidden" name="team" value="<?= htmlspecialchars($teamID) ?>"/>
                        <label><span><?= LANG_ANSWER ?>:</span> <input name="answer" maxlength="50"/></label>
                        <button type="submit"><?= LANG_OK ?></button>
                    </form>
                </body>
            </html><?php

            break;

        case 3:
            // 3 options
            ?><!DOCTYPE html>
            <html class="buzzer3">
                <head>
                    <meta charset="utf-8"/>
                    <link href="./buzzer.css" rel="stylesheet" type="text/css"/>
                    <meta name="viewport" content="width=device-width, user-scalable=no"/>
                    <title><?= LANG_BUZZER ?></title>
                </head>
                <body>
                    <form action="./" method="post">
                        <input type="hidden" name="buzzertype" value="3"/>
                        <input type="hidden" name="team" value="<?= htmlspecialchars($teamID) ?>"/>
                        <button type="submit" name="answer" value="A" class="a">A</button>
                        <button type="submit" name="answer" value="B" class="b">B</button>
                        <button type="submit" name="answer" value="C" class="c">C</button>
                    </form>
                </body>
            </html><?php

            break;

    }
} else {

    // first visit or incorrect login
    ?><!DOCTYPE html>
    <html class="login">
        <head>
            <meta charset="utf-8"/>
            <link href="./buzzer.css" rel="stylesheet" type="text/css"/>
            <meta name="viewport" content="width=device-width, user-scalable=no"/>
            <title><?= LANG_BUZZER ?></title>
        </head>
        <body>
            <form action="./" method="post">
                <label><span><?= LANG_PASSCODE ?>: </span><input type="password" name="passcode" required="required"/></label>
                <label><span><?= LANG_TEAM_NAME ?>: </span><input name="teamname" maxlength="30" required="required" spellcheck=true"/></label>
                <?php if (isset($_POST['login']) && !isset($_COOKIE['cookies-enabled'])) : ?>
                    <strong>⚠ <?= LANG_COOKIES_MUST_BE_ENABLED ?></strong>
                <?php endif; ?>
                <button name="login"><?= LANG_CONTINUE ?></button>
            </form>
        </body>
    </html><?php
}
