<?php

require_once '../autoload.php';

use Buzzer\Database;
use Buzzer\ScreenEventStream;

header('Content-Type: text/event-stream');
header('Cache-Control: no-cache');

$db = new Database();

$clearScreen = $db->getClearScreen();
$answerVisibility = $db->getScreenAnswerVisibility();

if ($clearScreen) {
    // clear the screen
    $db->setClearScreen(false);
    ScreenEventStream::clearScreen();

} elseif ($answerVisibility === 1) {
    // hide all answers
    $db->setScreenAnswerVisibility(0);
    ScreenEventStream::hideAnswers();

} elseif ($answerVisibility === 2) {
    // reveal all answers
    $db->setScreenAnswerVisibility(0);
    ScreenEventStream::revealAnswers();

} else {
    // fetch new answers from the database
    $answers = $db->getAnswers();
    if (count($answers) > 0) {
        ScreenEventStream::addAnswers($answers);
    }
}

$db->close();
