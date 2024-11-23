<?php

declare(strict_types=1);


namespace huppys\CookieConsentBundle\tests\Bundle;

use huppys\CookieConsentBundle\CookieConsentBundle;
use huppys\CookieConsentBundle\tests\Fixtures\Configuration\ConsentBundleConfiguration;
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

        $consentCategories = $processedConfig['consent_configuration']['consent_categories'];

        $this->assertArrayHasKey('functional', $consentCategories);
        $this->assertArrayHasKey('social_media', $consentCategories);
        $this->assertArrayHasKey('marketing', $consentCategories);

        $categoryFunction = $consentCategories['functional'];
        $this->assertContains('bookmark', $categoryFunction);
        $this->assertContains('shopping_cart', $categoryFunction);

        $this->assertContains('twitter', $consentCategories['social_media']);

        $categoryMarketing = $consentCategories['marketing'];
        $this->assertIsArray($categoryMarketing);
        $this->assertCount(0, $categoryMarketing);

        $this->assertEquals('dialog', $processedConfig['position']);
    }

    public function testInvalidConfiguration(): void
    {
        $this->expectException(InvalidConfigurationException::class);
        $this->processor->processConfiguration($this->configuration, [$this->getInvalidConfig()]);
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
        return ConsentBundleConfiguration::testCaseConfiguration();
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
