<?php

declare(strict_types=1);


namespace huppys\CookieConsentBundle\tests\Cookie;

use huppys\CookieConsentBundle\Cookie\CookieChecker;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\InputBag;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;

class CookieCheckerTest extends TestCase
{
    private CookieChecker $cookieChecker;
    private RequestStack $requestStack;


    public function setUp(): void
    {
        $this->requestStack = new RequestStack();
        $request = new Request();
        $this->requestStack->push($request);
        $this->cookieChecker = new CookieChecker($this->requestStack);
    }


    #[Test]
    #[DataProvider('isCookieConsentOptionSetByUserDataProvider')]
    public function shouldGetCookieConsentOptionSetByUser(array $cookies = [], bool $expected = false): void
    {
        $this->requestStack->getCurrentRequest()->cookies = new InputBag($cookies);

        $this->assertSame($expected, $this->cookieChecker->isCookieConsentOptionSetByUser());
    }

    public static function isCookieConsentOptionSetByUserDataProvider(): array
    {
        return [
            [['consent' => date('r')], true],
            [['consent' => 'true'], true],
            [['consent' => ''], true],
            [['Cookie Consent' => 'true'], false],
            [['CookieConsent' => 'true'], false],
            [[], false],
        ];
    }

    #[Test]
    #[DataProvider('isCategoryAllowedByUserDataProvider')]
    public function shouldGetCategoryAllowedByUser(array $cookies = [], string $category = '', bool $expected = false): void
    {
        $this->requestStack->getCurrentRequest()->cookies = new InputBag($cookies);

        $this->assertSame($expected, $this->cookieChecker->isCategoryAllowedByUser($category));
    }


    public static function isCategoryAllowedByUserDataProvider(): array
    {
        return [
            [['consent-category-analytics' => 'true'], 'analytics', true],
            [['consent-category-marketing' => 'true'], 'marketing', true],
            [['Cookie_Category_analytics' => 'false'], 'analytics', false],
            [['Cookie Category analytics' => 'true'], 'analytics', false],
            [['Cookie_Category_Analytics' => 'true'], 'analytics', false],
            [['analytics' => 'true'], 'analytics', false],
        ];
    }
}
