<?php

declare(strict_types=1);

namespace huppys\CookieConsentBundle\Mapper;

use DateInterval;
use Symfony\Component\HttpFoundation\Cookie;

class CookieConfigMapper
{
    public static function mapToCookie(mixed $cookieConfiguration, string $value, string $cookieNamePrefix): ?Cookie
    {
        if (!isset($value)) {
            return null;
        }

        $name = $cookieConfiguration['name'];
        $http_only = $cookieConfiguration['http_only'];
        $secure = $cookieConfiguration['secure'];
        $same_site = $cookieConfiguration['same_site'];
        $domain = $cookieConfiguration['domain'];
        $expires = $cookieConfiguration['expires'];

        if (!isset($name)) {
            return null;
        }

        if (!isset($http_only)) {
            return null;
        }

        if (!isset($secure)) {
            return null;
        }

        if (!isset($same_site)) {
            return null;
        }

//        if (!isset($domain)) {
//            return null;
//        }

        if (!isset($expires)) {
            return null;
        }

        return new Cookie(name: $cookieNamePrefix . $name, expire: self::convertExpireToDate($expires), domain: $domain, value: $value, secure: $secure, httpOnly: $http_only, raw: false, sameSite: $same_site, partitioned: false);
    }

    public static function convertExpireToDate(string $maxAge): \DateTimeInterface
    {
        $now = new \DateTimeImmutable();
        return $now->add(new DateInterval($maxAge));
    }
}