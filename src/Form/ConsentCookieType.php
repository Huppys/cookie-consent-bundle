<?php

declare(strict_types=1);


namespace huppys\CookieConsentBundle\Form;

use huppys\CookieConsentBundle\Entity\ConsentCookie;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Contracts\Translation\TranslatorInterface;

class ConsentCookieType extends AbstractType
{
    protected TranslatorInterface $translator;

    public function __construct(
        TranslatorInterface $translator
    )
    {
        $this->translator = $translator;
    }

    /**
     * Build the cookie consent form.
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
//        $categoryTitle = $this->translate('cookie_consent.' . $category . '.title');
        $categoryDescription = $this->translate('cookie_consent.' . 'category_name' . '.description');

        $builder->add('value', ChoiceType::class);

        $builder->add('name', HiddenType::class);
    }

    /**
     * Default options.
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => ConsentCookie::class,
            'translation_domain' => 'CookieConsentBundle'
        ]);
    }

    protected function translate(string $key): string
    {
        return $this->translator->trans($key, [], 'CookieConsentBundle');
    }
}
