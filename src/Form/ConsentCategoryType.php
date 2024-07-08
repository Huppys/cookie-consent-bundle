<?php

namespace huppys\CookieConsentBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Contracts\Translation\TranslatorInterface;

class ConsentCategoryType extends AbstractType
{
    private TranslatorInterface $translator;

    public function __construct(
        TranslatorInterface $translator,
    )
    {
        $this->translator = $translator;
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
//        $categoryTitle = $this->translate('cookie_consent.' . $category . '.title');
//        $categoryDescription = $this->translate('cookie_consent.' . $category . '.description');

        $builder->add('name', HiddenType::class);
        $builder->add('cookies', CollectionType::class, [
            'entry_type' => ConsentCookieType::class
        ]);
        $builder->add('userConsent', CheckboxType::class, [
            'required' => true,
        ]);
    }

    /**
     * Default options.
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => ConsentCategory::class,
            'translation_domain' => 'CookieConsentBundle',
        ]);
    }

    protected function translate(string $key): string
    {
        return $this->translator->trans($key, [], 'CookieConsentBundle');
    }
}