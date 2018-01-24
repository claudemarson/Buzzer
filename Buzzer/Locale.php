<?php

namespace Buzzer;

require_once dirname(__DIR__) . '/config.php';

/**
 * Class for loading the translations
 */
class Locale
{
    /**
     * Load all translations in the configured language
     */
    public static function loadLanguage()
    {
        if (in_array(LANG, ['de', 'en', 'fr', 'lb'])) {
            require_once dirname(__DIR__) . '/lang/' . LANG . '.php';
        } else {
            require_once dirname(__DIR__) . '/lang/en.php';
        }
    }
}
