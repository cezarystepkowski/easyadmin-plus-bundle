<?php

declare(strict_types = 1);

namespace Wingu\EasyAdminPlusBundle\Twig\Extension;

class EasyAdminTwigExtension extends \EasyCorp\Bundle\EasyAdminBundle\Twig\EasyAdminTwigExtension
{
    public function renderEntityField(\Twig_Environment $twig, $view, $entityName, $item, array $fieldMetadata): string
    {
        if (isset($fieldMetadata['custom_view']) && $fieldMetadata['custom_view'] === true) {
            $templateParameters = [
                'backend_config' => $this->getBackendConfiguration(),
                'entity_config' => $this->getEntityConfiguration($entityName),
                'field_options' => $fieldMetadata,
                'item' => $item,
                'view' => $view
            ];

            return $twig->render($fieldMetadata['template'], $templateParameters);
        }

        return parent::renderEntityField($twig, $view, $entityName, $item, $fieldMetadata);
    }
}
