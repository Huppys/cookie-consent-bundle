<?php

namespace huppys\CookieConsentBundle\tests\Fixtures\Configuration;


use huppys\CookieConsentBundle\Enum\CookieName;
use Symfony\Component\HttpFoundation\Cookie;

class ConsentBundleConfiguration
{
    public static function testCaseConfiguration(): array
    {
        return [
            'consent_configuration' => [
                'consent_cookie' => [
                    'name' => CookieName::COOKIE_CONSENT_NAME,
                    'http_only' => true,
                    'secure' => true,
                    'same_site' => Cookie::SAMESITE_LAX,
                    'domain' => null,
                    'expires' => 'P180D'
                ],
                'consent_categories' => [
                    'functional' => [
                        'bookmark',
                        'shopping_cart'
                    ],
                    'social_media' => [
                        'twitter'
                    ],
                    'marketing' => []
                ]
            ],
            'position' => 'dialog'
        ];
    }

    public static function kernelTestCaseConfiguration(): array
    {
        return [
            'consent_configuration' => [
                'consent_categories' => [
                    'functional' => [
                        'bookmark',
                        'shopping_cart'
                    ],
                    'social_media' => [
                        'twitter'
                    ],
                    'marketing' => []
                ]
            ],
            'position' => 'dialog'
        ];
    }
}
