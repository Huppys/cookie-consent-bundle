<?php

declare(strict_types=1);

namespace huppys\CookieConsentBundle\tests\Cookie;

use PHPUnit\Framework\TestCase;

class CookieHandlerTest extends TestCase
{
//    private Response $response;
//    private $cookies;
//
//    public function setUp(): void
//    {
//        $this->response = new Response();
//    }
//
//    /**
//     * Test CookieHandler:save.
//     */
//    public function testSave(): void
//    {
//
//        $cookieConfig = $this->getCookieDefaultConfig();
//        $this->saveCookieHandler($cookieConfig);
//
//        $this->cookies = $this->response->headers->getCookies();
//
//        $this->assertCount(5, $this->cookies);
//
//        $this->assertSame($cookieConfig['name_prefix'] . 'consent', $this->cookies[0]->getName());
//
//        $this->assertSame($cookieConfig['name_prefix'] . 'consent-key', $this->cookies[1]->getName());
//        $this->assertSame('key-test', $this->cookies[1]->getValue());
//
//        $this->assertSame($cookieConfig['name_prefix'] . 'consent-categories-analytics', $this->cookies[2]->getName());
//        $this->assertSame('true', $this->cookies[2]->getValue());
//
//        $this->assertSame($cookieConfig['name_prefix'] . 'consent-categories-social_media', $this->cookies[3]->getName());
//        $this->assertSame('true', $this->cookies[3]->getValue());
//
//        $this->assertSame($cookieConfig['name_prefix'] . 'consent-categories-tracking', $this->cookies[4]->getName());
//        $this->assertSame('false', $this->cookies[4]->getValue());
//    }
//
//    public function testRejectAllCookies(): void
//    {
//        $this->saveCookieHandler($this->getCookieDefaultConfig(), true);
//        $this->cookies = $this->response->headers->getCookies();
//        $this->assertCount(1, $this->cookies);
//        $this->assertSame('testCookie_consent', $this->cookies[0]->getName());
//    }
//
//    /**
//     * Test CookieHandler:save with httpOnly false.
//     */
//    public function testCookieHandlerHttpOnlyIsFalse(): void
//    {
//        $this->saveCookieHandler($this->getCookieConfigWithHttpOnlyFalse());
//        $cookies = $this->response->headers->getCookies();
//        $this->assertSame(false, $cookies[0]->isHttpOnly());
//        $this->assertSame(true, $cookies[1]->isHttpOnly());
//    }
//
//    /**
//     * Test CookieHandler:save with httpOnly true.
//     */
//    public function testCookieHandlerHttpOnlyIsTrue(): void
//    {
//        $this->saveCookieHandler($this->getCookieDefaultConfig());
//        $cookies = $this->response->headers->getCookies();
//        $this->assertSame(true, $cookies[0]->isHttpOnly());
//    }
//
//    /**
//     * Save CookieHandler
//     */
//    public function saveCookieHandler(array $cookieSettings, bool $rejectAllCookies = false): void
//    {
//        $cookieHandler = new CookieHandler($cookieSettings);
//
//        $cookieHandler->save([
//            'analytics' => 'true',
//            'social_media' => 'true',
//            'tracking' => 'false',
//        ], 'key-test', $this->response, $rejectAllCookies);
//    }
//
//    private function getCookieDefaultConfig(): array
//    {
//        return [
//            'name_prefix' => 'testCookie_',
//            'consent_categories' => [
//                'analytics',
//                'social_media',
//                'tracking',
//            ],
//            'cookies' => [
//                'consent_cookie' => [
//                    'name' => 'consent',
//                    'http_only' => true,
//                    'secure' => true,
//                    'same_site' => 'lax',
//                    'expires' => 'P180D',
//                ],
//                'consent_key_cookie' => [
//                    'name' => 'consent-key',
//                    'http_only' => true,
//                    'secure' => true,
//                    'same_site' => 'strict',
//                    'expires' => 'P180D',
//                ],
//                'consent_categories_cookie' => [
//                    'name' => 'consent-categories',
//                    'http_only' => true,
//                    'secure' => true,
//                    'same_site' => 'lax',
//                    'expires' => 'P180D',
//                ],
//            ]
//        ];
//    }
//
//    private function getCookieConfigWithHttpOnlyFalse(): array
//    {
//        return [
//            'name_prefix' => 'testCookie_',
//            'consent_categories' => [
//                'analytics',
//                'social_media',
//                'tracking',
//            ],
//            'cookies' => [
//                'consent_cookie' => [
//                    'name' => 'consent',
//                    'http_only' => false,
//                    'secure' => true,
//                    'same_site' => 'lax',
//                    'expires' => 'P180D',
//                ],
//                'consent_key_cookie' => [
//                    'name' => 'consent-key',
//                    'http_only' => true,
//                    'secure' => true,
//                    'same_site' => 'strict',
//                    'expires' => 'P180D',
//                ],
//                'consent_categories_cookie' => [
//                    'name' => 'consent-categories',
//                    'http_only' => true,
//                    'secure' => true,
//                    'same_site' => 'lax',
//                    'expires' => 'P180D',
//                ],
//            ]
//        ];
//    }
}
