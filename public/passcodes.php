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

?><!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8"/>
        <link href="./css/passcodes.css" rel="stylesheet" type="text/css"/>
        <title><?= LANG_PASSCODES_PRINTOUT ?></title>
    </head>
    <body>
        <h1><?= LANG_PASSCODES_PRINTOUT ?></h1>
        <?php foreach ($allTeamPasscodes as $teamPasscodes) : ?>
            <table>
                <tbody>
                    <tr>
                        <th><?= LANG_URL ?>:</th>
                        <td><?= $buzzerURL ?></td>
                    </tr>
                    <tr>
                        <th><?= LANG_PASSCODE ?>:</th>
                        <td><output><?= htmlspecialchars($teamPasscodes['passcode']) ?></output></td>
                    </tr>
                </tbody>
            </table>
        <?php endforeach; ?>
    </body>
</html>
