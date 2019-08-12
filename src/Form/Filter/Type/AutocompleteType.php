<?php

declare(strict_types=1);

namespace Wingu\EasyAdminPlusBundle\Form\Filter\Type;

use EasyCorp\Bundle\EasyAdminBundle\Form\Filter\Type\FilterInterface;
use EasyCorp\Bundle\EasyAdminBundle\Form\Filter\Type\FilterTypeTrait;
use Wingu\EasyAdminPlusBundle\Form\Type\EasyAdminAutocompleteType;

class AutocompleteType extends EasyAdminAutocompleteType implements FilterInterface
{
    use FilterTypeTrait;
}
