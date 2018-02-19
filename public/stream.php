<?php

require_once '../autoload.php';

use Buzzer\Database;
use Buzzer\ScreenEventStream;

header('Content-Type: text/event-stream');
header('Cache-Control: no-cache');

$db = new Database();

$clearScreen = $db->getClearScreen();
$answerVisibilityHasChanged = $db->getScreenAnswerVisibilityChanged();

if ($clearScreen) {
    // clear the screen
    $db->setClearScreen(false);
    ScreenEventStream::clearScreen();

} elseif ($answerVisibilityHasChanged) {
    // answer visibility has changed
    $answersVisible = $db->getScreenAnswerVisibility();
    
    if (!$answersVisible) {
        // hide all answers
        $db->setScreenAnswerVisibilityChanged(false);
        ScreenEventStream::hideAnswers();

    } else {
        // reveal all answers
        $db->setScreenAnswerVisibilityChanged(false);
        ScreenEventStream::revealAnswers();
    }

} else {
    // fetch new answers from the database
    $answers = $db->getAnswers();
    if (count($answers) > 0) {
        ScreenEventStream::addAnswers($answers);
    }
}

$db->close();
