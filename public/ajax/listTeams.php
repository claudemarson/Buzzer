<?php

require_once '../../autoload.php';

use Buzzer\Database;
use Buzzer\Locale;

$db = new Database();
$allTeams = $db->getAllTeams();
$db->close();

Locale::loadLanguage();

foreach ($allTeams as $team) : ?>
    <tr data-team-id="<?= $team['ID'] ?>">
        <td<?= (is_null($team['teamName'])) ? ' class="undefined"' : '' ?>><?= (!is_null($team['teamName'])) ? ($team['teamName']) : LANG_UNDEFINED ?></td>
        <td><button type="button" class="remove-team" title="<?= LANG_REMOVE_TEAM ?>">❌︎</button></td>
    </tr>
<?php endforeach;
