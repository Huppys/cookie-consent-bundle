<?php

declare(strict_types=1);


namespace huppys\CookieConsentBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Contracts\Translation\TranslatorInterface;

class ConsentVendorType extends AbstractType
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
        $builder->add('consentGiven', CheckboxType::class);

        $builder->add('name', HiddenType::class);
    }

    /**
     * Default options.
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
                                   'data_class' => ConsentVendorTypeModel::class,
                                   'translation_domain' => 'CookieConsentBundle'
                               ]);
    }

    protected function translate(string $key): string
    {
        return $this->translator->trans($key, [], 'CookieConsentBundle');
    }
}
