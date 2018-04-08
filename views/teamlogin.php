<!DOCTYPE html>
<html class="login" lang="<?= LANG ?>">
    <head>
        <meta charset="utf-8"/>
        <link href="./css/buzzer.css" rel="stylesheet" type="text/css"/>
        <meta name="viewport" content="width=device-width, user-scalable=no"/>
        <title><?= LANG_BUZZER ?></title>
    </head>
    <body>
        <form method="post">
            <label><span><?= LANG_PASSCODE ?>: </span><input type="password" name="passcode" required="required"/></label>
            <label><span><?= LANG_TEAM_NAME ?>: </span><input name="teamname" maxlength="30" required="required" spellcheck=true"/></label>
            <?php if (isset($_POST['login']) && !isset($_COOKIE['cookies-enabled'])) : ?>
                <strong>⚠ <?= LANG_COOKIES_MUST_BE_ENABLED ?></strong>
            <?php endif; ?>
            <button name="login"><?= LANG_CONTINUE ?></button>
        </form>
    </body>
</html>
