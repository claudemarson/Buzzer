<!DOCTYPE html>
<html class="buzzer2" lang="<?= LANG ?>">
    <head>
        <meta charset="utf-8"/>
        <link href="./css/buzzer.css" rel="stylesheet" type="text/css"/>
        <meta name="viewport" content="width=device-width, user-scalable=no"/>
        <title><?= LANG_BUZZER ?></title>
    </head>
    <body>
        <form method="post">
            <input type="hidden" name="buzzertype" value="2"/>
            <input type="hidden" name="team" value="<?= htmlspecialchars($teamID) ?>"/>
            <label><span><?= LANG_ANSWER ?>:</span> <input name="answer" maxlength="50"/></label>
            <button><?= LANG_OK ?></button>
        </form>
    </body>
</html>
