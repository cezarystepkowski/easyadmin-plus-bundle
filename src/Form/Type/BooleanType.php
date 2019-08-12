<?php

declare(strict_types = 1);

namespace Wingu\EasyAdminPlusBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Boolean form type.
 */
class BooleanType extends AbstractType
{
    private const VALUE_TRUE = 1;

    private const VALUE_FALSE = 0;

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults(
            [
                'choices' => [
                    'label.true' => static::VALUE_TRUE,
                    'label.false' => static::VALUE_FALSE
                ],
                'translation_domain' => 'EasyAdminBundle',
            ]
        );
    }

    /**
     * {@inheritdoc}
     */
    public function getParent() : string
    {
        return ChoiceType::class;
    }
}
