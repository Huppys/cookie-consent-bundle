<?php

declare(strict_types=1);


namespace huppys\CookieConsentBundle\tests\Bundle;

use huppys\CookieConsentBundle\CookieConsentBundle;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Config\Definition\Configuration;
use Symfony\Component\Config\Definition\Exception\InvalidConfigurationException;
use Symfony\Component\Config\Definition\Processor;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Yaml\Parser;

class CookieConsentConfigDefinitonTest extends TestCase
{

    private Processor $processor;
    private ContainerBuilder $containerBuilder;
    private Configuration $configuration;


    public function setUp(): void
    {
        $this->containerBuilder = new ContainerBuilder();
        $this->processor = new Processor();
        $this->configuration = new Configuration(new CookieConsentBundle(), $this->containerBuilder, 'cookie_consent');
    }

    public function tearDown(): void
    {
        unset($this->containerBuilder);
    }

    public function testFullConfiguration(): void
    {
        $processedConfig = $this->processor->processConfiguration($this->configuration, [$this->getFullConfig()]);

        $this->assertEquals(['tracking', 'marketing', 'social_media'], $processedConfig['consent_categories']);
        $this->assertEquals('top', $processedConfig['position']);
    }

    public function testInvalidConfiguration(): void
    {
        $this->expectException(InvalidConfigurationException::class);
        $this->processor->processConfiguration($this->configuration, [$this->getInvalidConfig()]);
    }

    public function testCookieNamesContainPrefix(): void
    {
        $processedConfig = $this->processor->processConfiguration($this->configuration, [$this->getFullConfig()]);

        $this->assertEquals('test_', $processedConfig['cookie_settings']['name_prefix']);
    }

    public function testCookieSettingsIsAnArray(): void
    {
        $processedConfig = $this->processor->processConfiguration($this->configuration, [$this->getFullConfig()]);
        $this->assertIsArray($processedConfig);
    }

    /**
     * get full config.
     */
    protected function getFullConfig(): array
    {
        $yaml = <<<EOF
consent_categories:
- 'tracking'
- 'marketing'
- 'social_media'
cookie_settings:
    name_prefix: 'test_'
    cookies:
        consent_cookie:
            name: 'consent'
            http_only: false
            secure: true
            same_site: 'strict'
            expires: 'P180D'
        consent_key_cookie:
            name: 'consent_key'
            http_only: true
            secure: true
            same_site: 'strict'
            expires: 'P180D'
position: 'top'
csrf_protection: true
EOF;
        $parser = new Parser();

        return $parser->parse($yaml);
    }

    /**
     * get invalid config.
     */
    protected function getInvalidConfig(): array
    {
        $yaml = <<<EOF
foo: 'bar'
EOF;
        $parser = new Parser();

        return $parser->parse($yaml);
    }
}
