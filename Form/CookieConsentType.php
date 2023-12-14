<?php

declare(strict_types=1);


namespace huppys\CookieConsentBundle\Form;

use huppys\CookieConsentBundle\Cookie\CookieChecker;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Translation\TranslatableMessage;

class CookieConsentType extends AbstractType
{
    protected CookieChecker $cookieChecker;
    protected array $cookieCategories;
    protected bool $csrfProtection;

    public function __construct(
        CookieChecker $cookieChecker,
        array         $cookieCategories,
        bool          $csrfProtection = true
    )
    {
        $this->cookieChecker = $cookieChecker;
        $this->cookieCategories = $cookieCategories;
        $this->csrfProtection = $csrfProtection;
    }

    /**
     * Build the cookie consent form.
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        foreach ($this->cookieCategories as $category) {
            $builder->add($category, ChoiceType::class, [
                'expanded' => true,
                'multiple' => false,
                'data' => $this->cookieChecker->isCategoryAllowedByUser($category) ? 'true' : 'false',
                'choices' => [
                    ['cookie_consent.yes' => 'true'],
                    ['cookie_consent.no' => 'false'],
                ],
                'help' => $this->translate('cookie_consent.' . $category . '.title'),
            ]);
        }

        $builder->add('save', SubmitType::class, [
            'label' => $this->translate('cookie_consent.save'),
            'attr' => [
                'class' => 'btn cookie-consent__btn js-submit-cookie-consent-form'
            ]
        ]);
        $builder->add('reject_all_cookies', SubmitType::class, [
            'label' => $this->translate('cookie_consent.reject_all'),
            'attr' => [
                'class' => 'btn cookie-consent__btn js-reject-all-cookies'
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

    private function translate(string $key): TranslatableMessage
    {
        return new TranslatableMessage($key, [], 'CookieConsentBundle');
    }
}
