<?php

declare(strict_types = 1);

namespace Wingu\EasyAdminPlusBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Boolean form type.
 */
final class BooleanType extends AbstractType
{
    private const VALUE_TRUE = 1;

    private const VALUE_FALSE = 0;

    /**
     * Label for the true value.
     *
     * @var string
     */
    protected $labelTrue = 'Yes';

    /**
     * Label for the false value.
     *
     * @var string
     */
    protected $labelFalse = 'No';

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults(
            [
                'choices' => [
                    $this->labelTrue => static::VALUE_TRUE,
                    $this->labelFalse => static::VALUE_FALSE
                ]
            ]
        );
    }

    /**
     * {@inheritdoc}
     */
    public function getParent(): string
    {
        return ChoiceType::class;
    }
}
