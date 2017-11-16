<?php

declare(strict_types = 1);

namespace Wingu\EasyAdminPlusBundle\Search;

use EasyCorp\Bundle\EasyAdminBundle\Configuration\ConfigManager;
use EasyCorp\Bundle\EasyAdminBundle\Search\Finder;
use Symfony\Component\PropertyAccess\PropertyAccessor;

final class Autocomplete
{
    private $configManager;

    private $finder;

    private $propertyAccessor;

    public function __construct(ConfigManager $configManager, Finder $finder, PropertyAccessor $propertyAccessor)
    {
        $this->configManager = $configManager;
        $this->finder = $finder;
        $this->propertyAccessor = $propertyAccessor;
    }

    /**
     * Finds the values of the given entity which match the query provided.
     *
     * @param string $entity
     * @param string $query
     * @param int $page
     *
     * @return array
     */
    public function find($entity, $query, $page = 1): array
    {
        if (empty($entity) || empty($query)) {
            return ['results' => []];
        }

        $backendConfig = $this->configManager->getBackendConfig();
        if (!isset($backendConfig['entities'][$entity])) {
            throw new \InvalidArgumentException(
                \sprintf(
                    'The "entity" argument must contain the name of an entity managed by EasyAdmin ("%s" given).',
                    $entity
                )
            );
        }

        $paginator = $this->finder->findByAllProperties(
            $backendConfig['entities'][$entity],
            $query,
            $page,
            $backendConfig['show']['max_results']
        );

        return [
            'results' => $this->processResults(
                $paginator->getCurrentPageResults(),
                $backendConfig['entities'][$entity]
            ),
            'has_next_page' => $paginator->hasNextPage()
        ];
    }

    /**
     * @param \Traversable $entities
     * @param array $targetEntityConfig
     * @return array
     */
    private function processResults(\Traversable $entities, array $targetEntityConfig): array
    {
        $results = [];

        foreach ($entities as $entity) {
            $results[] = [
                'id' => (string)$this
                    ->propertyAccessor
                    ->getValue(
                        $entity,
                        $targetEntityConfig['primary_key_field_name']
                    ),
                'text' => $this->getText($entity, $targetEntityConfig)
            ];
        }

        return $results;
    }

    private function getText($entity, array $targetEntityConfig): string
    {
        if (isset($targetEntityConfig['search']['autocomplete']['text'])) {
            return (string)$this
                ->propertyAccessor
                ->getValue(
                    $entity,
                    $targetEntityConfig['search']['autocomplete']['text']
                );
        }

        return (string)$entity;
    }
}
