<!DOCTYPE html>
<html lang="<?= LANG ?>">
    <head>
        <meta charset="utf-8"/>
        <link href="./css/passcodes.css?v2" rel="stylesheet" type="text/css"/>
        <title><?= LANG_PASSCODES_PRINTOUT ?></title>
    </head>
    <body>
        <h1><?= LANG_PASSCODES_PRINTOUT ?></h1>
        <?php foreach ($allTeamPasscodes as $teamPasscodes) : ?>
            <table>
                <tbody>
                    <tr>
                        <th><?= LANG_URL ?>:</th>
                        <td><?= htmlspecialchars($buzzerURL) ?></td>
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
