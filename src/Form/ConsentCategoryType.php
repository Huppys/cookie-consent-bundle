<?php

namespace huppys\CookieConsentBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
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

        $builder->add('consentGiven', CheckboxType::class, [
            'required' => false,
            'mapped' => false // we won't map this field to the ConsentCategoryTypeModel, we only need it for client-side interaction
        ]);

        $builder->add('vendors', CollectionType::class, [
            'entry_type' => ConsentVendorType::class,
            'entry_options' => [
                'attr' => ['class' => 'consent-form-category-vendor'],
            ],
            'attr' => [
                'class' => 'consent-form-vendors'
            ]
        ]);
    }

    /**
     * Default options.
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => ConsentCategoryTypeModel::class,
            'translation_domain' => 'CookieConsentBundle',
        ]);
    }

    protected function translate(string $key): string
    {
        return $this->translator->trans($key, [], 'CookieConsentBundle');
    }
}