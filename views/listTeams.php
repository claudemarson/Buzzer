<?php foreach ($allTeams as $team) :
    $teamNameIsNull = is_null($team['teamName']); ?>
    <tr data-team-id="<?= htmlspecialchars($team['ID']) ?>">
        <td<?= ($teamNameIsNull) ? ' class="undefined"' : '' ?>><?= (!$teamNameIsNull) ? htmlspecialchars($team['teamName']) : LANG_UNDEFINED ?></td>
        <td><button type="button" class="remove-team" title="<?= LANG_REMOVE_TEAM ?>">❌︎</button></td>
    </tr>
<?php endforeach;
