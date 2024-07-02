<?php

declare(strict_types=1);

namespace huppys\CookieConsentBundle\Form;

use huppys\CookieConsentBundle\Enum\FormSubmitName;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Contracts\Translation\TranslatorInterface;

class ConsentSimpleType extends AbstractType
{

    public function __construct(
        private readonly TranslatorInterface $translator,
        private readonly bool                $csrfProtection = true
    )
    {
    }

    /**
     * Build the cookie consent form.
     */
    public function buildForm(FormBuilderInterface $builder, array $options = []): void
    {
        $builder
            ->add(FormSubmitName::ACCEPT_ALL, SubmitType::class, [
                'label' => $this->translate('cookie_consent.accept_all'),
                'attr' => [
                    'class' => 'cookie-consent__btn js-accept-all-cookies'
                ]
            ])
            ->add(FormSubmitName::REJECT_ALL, SubmitType::class, [
                'label' => $this->translate('cookie_consent.reject_all'),
                'attr' => [
                    'class' => 'cookie-consent__btn js-reject-all-cookies'
                ]
            ]);
    }

    /**
     * Default options.
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
                                   'translation_domain' => 'CookieConsentBundle',
                                   'csrf_protection' => $this->csrfProtection,
                                   'csrf_token_id' => 'csrf_cookie_consent',
                               ]);
    }

    protected function translate(string $key): string
    {
        return $this->translator->trans($key, [], 'CookieConsentBundle');
    }
}
