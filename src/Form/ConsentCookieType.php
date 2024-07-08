<?php

declare(strict_types=1);


namespace huppys\CookieConsentBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Contracts\Translation\TranslatorInterface;

class ConsentCookieType extends AbstractType
{

    public function __construct(
        private readonly TranslatorInterface $translator
    )
    {
    }

    /**
     * Build the cookie consent form.
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
//        $categoryTitle = $this->translate('cookie_consent.' . $category . '.title');
        $categoryDescription = $this->translate('cookie_consent.' . 'category_name' . '.description');

        $builder->add('consentGiven', CheckboxType::class);

        $builder->add('name', HiddenType::class);
    }

    /**
     * Default options.
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
                                   'data_class' => ConsentDetailsType::class,
                                   'translation_domain' => 'CookieConsentBundle'
                               ]);
    }

    protected function translate(string $key): string
    {
        return $this->translator->trans($key, [], 'CookieConsentBundle');
    }
}
