<?php

namespace Application\Service;

use Laminas\Session\Container;
use Locale;

class LocaleService
{
    const LOCALE_STORAGE_KEY = 'user_locale';
    const LOCALE_STORAGE_SESSION_ID = 'application_session';
    const RU_RU_LOCALE = 'ru_RU';
    const EN_US_LOCALE = 'en_US';

    private static ?Container $session = null;

    public static function verifyLocale(?string $locale): string
    {
        if (!$locale || !preg_match('#^(?P<locale>[a-z]{2,3}([-_][a-zA-Z]{2}|))#', $locale, $matches)) {
            return self::RU_RU_LOCALE;
        }

        if (!in_array($locale, [self::RU_RU_LOCALE, self::EN_US_LOCALE])) {
            return self::RU_RU_LOCALE;
        }

        return $locale;
    }

    public static function setLocale(?string $locale): void
    {
        $locale = self::verifyLocale($locale);
        self::set($locale);
    }

    public static function getLocale()
    {
        $locale = self::RU_RU_LOCALE;

        if (empty(self::$session))
            self::$session = new Container(self::LOCALE_STORAGE_SESSION_ID);

        if (self::$session->offsetExists(self::LOCALE_STORAGE_KEY))
            $locale = self::$session->offsetGet(self::LOCALE_STORAGE_KEY);

        return $locale;
    }

    private static function set(string $locale)
    {
        Locale::setDefault(Locale::canonicalize($locale));
    }
}