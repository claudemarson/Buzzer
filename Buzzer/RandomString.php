<?php

namespace Buzzer;

/**
 * Class to generate a random string
 */
class RandomString
{
    /**
     * Generate a random string
     * @param int $length length of the generated string
     * @return string
     */
    public static function randomString(int $length): string
    {
        $pool = '23456789ABCDEFGHIJKLMNPQRSTUVWXYZabcdefghijkmnopqrstuvwxyz';
        $pool_length = mb_strlen($pool);
        $result = '';
        
        for ($n = 0; $n < $length; $n++) {
            $result .= $pool[random_int(0, $pool_length - 1)];
        }
        
        return $result;
    }
}
