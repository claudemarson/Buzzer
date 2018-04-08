<!DOCTYPE html>
<html class="buzzer1" lang="<?= LANG ?>">
    <head>
        <meta charset="utf-8"/>
        <link href="./css/buzzer.css" rel="stylesheet" type="text/css"/>
        <meta name="viewport" content="width=device-width, user-scalable=no"/>
        <title><?= LANG_BUZZER ?></title>
    </head>
    <body>
        <form method="post">
            <input type="hidden" name="buzzertype" value="1"/>
            <input type="hidden" name="team" value="<?= htmlspecialchars($teamID) ?>"/>
            <button name="answer" value="X">BAZZAAA</button>
        </form>
    </body>
</html>
