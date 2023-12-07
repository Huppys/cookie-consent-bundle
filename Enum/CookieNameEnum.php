<?php

declare(strict_types=1);



namespace huppys\CookieConsentBundle\Enum;

class CookieNameEnum
{
    const COOKIE_CONSENT_NAME = 'consent';

    const COOKIE_CONSENT_KEY_NAME = 'consent_key';

    const COOKIE_CATEGORY_NAME_PREFIX = 'consent_category_';

    /**
     * Get cookie category name.
     */
    public static function getCookieCategoryName(string $category): string
    {
        return self::COOKIE_CATEGORY_NAME_PREFIX.$category;
    }
}
