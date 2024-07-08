<?php

namespace huppys\CookieConsentBundle\tests\Form;

use huppys\CookieConsentBundle\Enum\FormSubmitName;
use huppys\CookieConsentBundle\Form\ConsentSimpleConfiguration;
use huppys\CookieConsentBundle\Form\ConsentSimpleType;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\MockObject\Exception;
use Symfony\Component\Form\PreloadedExtension;
use Symfony\Component\Form\Test\TypeTestCase;
use Symfony\Contracts\Translation\TranslatorInterface;

class ConsentSimpleTypeTest extends TypeTestCase
{
    private ConsentSimpleType $simpleType;

    #[Test]
    #[DataProvider('submittedFormProvider')]
    public function shouldHaveClickedButton($formData, $expectedClickedButton): void
    {
        $model = new ConsentSimpleConfiguration($formData);

        $form = $this->factory->create(ConsentSimpleType::class, $model);

        $form->submit($formData);

        $this->assertEquals($form->getClickedButton()->getName(), $expectedClickedButton);

        $this->assertTrue($form->isSynchronized());
    }

    /**
     * @throws Exception
     */
    protected function getExtensions(): array
    {
        $translatorInterfaceMock = $this->createMock(TranslatorInterface::class);

        $type = new ConsentSimpleType($translatorInterfaceMock);

        return [
            new PreloadedExtension([$type], []),
        ];
    }

    public static function submittedFormProvider(): array
    {
        return [
            'accept_all_clicked' => [
                [
                    FormSubmitName::ACCEPT_ALL => true,
                ],
                FormSubmitName::ACCEPT_ALL
            ],
            'reject_all_clicked' => [
                [
                    FormSubmitName::REJECT_ALL => true,
                ],
                FormSubmitName::REJECT_ALL
            ]
        ];
    }
}