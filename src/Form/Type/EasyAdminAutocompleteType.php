<?php

declare(strict_types = 1);

namespace Wingu\EasyAdminPlusBundle\Form\Type;

use Symfony\Component\OptionsResolver\OptionsResolver;

class EasyAdminAutocompleteType extends \EasyCorp\Bundle\EasyAdminBundle\Form\Type\EasyAdminAutocompleteType
{
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        parent::configureOptions($resolver);

        $resolver->setDefined(
            \array_merge(
                $resolver->getDefinedOptions(),
                ['choice_label', 'choice_value']
            )
        );
    }
}
