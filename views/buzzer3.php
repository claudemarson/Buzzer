<!DOCTYPE html>
<html class="buzzer3" lang="<?= LANG ?>">
    <head>
        <meta charset="utf-8"/>
        <link href="./css/buzzer.css" rel="stylesheet" type="text/css"/>
        <meta name="viewport" content="width=device-width, user-scalable=no"/>
        <title><?= LANG_BUZZER ?></title>
    </head>
    <body>
        <form method="post">
            <input type="hidden" name="buzzertype" value="3"/>
            <input type="hidden" name="team" value="<?= htmlspecialchars($teamID) ?>"/>
            <button name="answer" value="A" class="a">A</button>
            <button name="answer" value="B" class="b">B</button>
            <button name="answer" value="C" class="c">C</button>
        </form>
    </body>
</html>
