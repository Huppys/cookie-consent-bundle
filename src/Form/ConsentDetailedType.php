<?php

namespace huppys\CookieConsentBundle\Form;

use huppys\CookieConsentBundle\Entity\ConsentDetailedConfiguration;
use huppys\CookieConsentBundle\Enum\FormSubmitName;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Contracts\Translation\TranslatorInterface;

class ConsentDetailedType extends AbstractType
{
    private bool $csrfProtection;
    private TranslatorInterface $translator;

    public function __construct(
        TranslatorInterface $translator,
        bool                $csrfProtection = true
    )
    {
        $this->csrfProtection = $csrfProtection;
        $this->translator = $translator;
    }

    /**
     * Build the cookie consent form.
     */
    public function buildForm(FormBuilderInterface $builder, array $options = []): void
    {
        $builder->add('description');
        // build collection of categories

        foreach ($options['data']->getCategories() as $category) {
            $builder->add('categories', ConsentCategoryType::class, [
                'data' => $category,
            ]);
        }


        // add buttons
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
            ])
            ->add(FormSubmitName::SAVE_CONSENT_SETTINGS, SubmitType::class, [
                'label' => $this->translate('cookie_consent.save_settings'),
                'attr' => [
                    'class' => 'cookie-consent__btn js-save-settings'
                ]
            ]);
    }

    /**
     * Default options.
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => ConsentDetailedConfiguration::class,
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