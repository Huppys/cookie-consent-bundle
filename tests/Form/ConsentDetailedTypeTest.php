<?php

namespace huppys\CookieConsentBundle\tests\Form;

use huppys\CookieConsentBundle\Entity\ConsentCategory;
use huppys\CookieConsentBundle\Entity\ConsentCookie;
use huppys\CookieConsentBundle\Entity\ConsentDetailedConfiguration;
use huppys\CookieConsentBundle\Enum\FormSubmitName;
use huppys\CookieConsentBundle\Form\ConsentCategoryType;
use huppys\CookieConsentBundle\Form\ConsentCookieType;
use huppys\CookieConsentBundle\Form\ConsentDetailedType;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\MockObject\Exception;
use Symfony\Component\Form\PreloadedExtension;
use Symfony\Component\Form\Test\TypeTestCase;
use Symfony\Contracts\Translation\TranslatorInterface;

class ConsentDetailedTypeTest extends TypeTestCase
{
    #[Test]
    #[DataProvider('submittedFormProvider')]
    public function shouldHaveClickedButton($formData, $expectedClickedButton): void
    {
        $formModel = new ConsentDetailedConfiguration();

        $formModel->setDescription($formData['description']);

        foreach ($formData['categories'] as $category) {

            $consentCategory = new ConsentCategory();
            $consentCategory->setName($category['name']);
            $consentCategory->setUserConsent($category['userConsent']);

            foreach ($category['cookies'] as $cookie) {
                $consentCookie = new ConsentCookie();

                // explicitly set fields from formData
                $consentCookie->setName($cookie['name']);
                $consentCookie->setConsentGiven($cookie['consentGiven']);
                $consentCookie->setDescriptionKey($cookie['descriptionKey']);

                $consentCategory->getCookies()->add($consentCookie);
            }

            $formModel->getCategories()->add($consentCategory);
        }

        $form = $this->factory->create(ConsentDetailedType::class, $formModel);

        $form->submit($formData);

        $this->assertTrue($form->isSynchronized());

        $this->assertEquals($form->getClickedButton()->getName(), $expectedClickedButton);
    }

    /**
     * @throws Exception
     */
    protected function getExtensions(): array
    {
        $translatorInterfaceMock = $this->createMock(TranslatorInterface::class);

        $consentDetailedType = new ConsentDetailedType($translatorInterfaceMock);
        $consentCategoryType = new ConsentCategoryType($translatorInterfaceMock);
        $consentCookieType = new ConsentCookieType($translatorInterfaceMock);

        return [
            new PreloadedExtension([$consentDetailedType, $consentCategoryType, $consentCookieType], []),
        ];
    }

    public static function submittedFormProvider(): array
    {
        return [
            'dataset:save_consent_settings_clicked' => [
                [
                    FormSubmitName::SAVE_CONSENT_SETTINGS => true,
                    'description' => 'test_detailed_type',
                    'categories' => [
                        [
                            'name' => 'analytics',
                            'userConsent' => false,
                            'cookies' => [
                                [
                                    'name' => 'googleanalytics',
                                    'consentGiven' => false,
                                    'descriptionKey' => 'googleanalytics',
                                ]
                            ],
                        ],
                        [
                            'name' => 'tracking',
                            'userConsent' => true,
                            'cookies' => [
                                [
                                    'name' => 'googletagmanager',
                                    'consentGiven' => true,
                                    'descriptionKey' => 'googletagmanager',
                                ]
                            ],
                        ],
                        [
                            'name' => 'social_media',
                            'userConsent' => false,
                            'cookies' => [
                                [
                                    'name' => 'meta',
                                    'consentGiven' => false,
                                    'descriptionKey' => 'meta',
                                ],
                                [
                                    'name' => 'google',
                                    'consentGiven' => true,
                                    'descriptionKey' => 'google',
                                ]
                            ],
                        ],
                    ],
                    'consent_version' => 1,
                ],
                FormSubmitName::SAVE_CONSENT_SETTINGS,
            ],
            'dataset:accept_all_clicked' => [
                [
                    FormSubmitName::ACCEPT_ALL => true,
                    'description' => 'test_detailed_type',
                ],
                FormSubmitName::ACCEPT_ALL,
            ],
        ];
    }
}
