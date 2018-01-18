<?php

namespace Buzzer;

/**
 * EventStream for the screen
 */
class ScreenEventStream
{
    /**
     * Stream a message
     * @param string $event the event name
     * @param string[] $message [optional] the message to be streamed
     */
    private static function sendMessage(string $event, array $message = [])
    {
        echo 'retry: 200' . PHP_EOL;
        echo "event: $event" . PHP_EOL;
        echo 'data: ' . json_encode($message, JSON_UNESCAPED_UNICODE) . PHP_EOL;
        echo PHP_EOL;
        ob_flush();
        flush();
    }
    
    /**
     * Add answers to the screen
     * @param string[] $answers array of array with keys 'teamName' and 'answer'
     */
    public static function addAnswers(array $answers)
    {
        static::sendMessage('answers', $answers);
    }
    
    /**
     * Send a message to clear the screen
     */
    public static function clearScreen()
    {
        static::sendMessage('clear');
    }
    
    /**
     * Send a message to hide all answers
     */
    public static function hideAnswers()
    {
        static::sendMessage('hide');
    }
    
    /**
     * Send a message to reveal all answers
     */
    public static function revealAnswers()
    {
        static::sendMessage('reveal');
    }
}
