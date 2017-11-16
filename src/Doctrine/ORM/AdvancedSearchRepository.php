<?php

declare(strict_types = 1);

namespace Wingu\EasyAdminPlusBundle\Doctrine\ORM;

use Pagerfanta\Pagerfanta;

interface AdvancedSearchRepository
{
    /**
     * Create a paginator for advanced searching.
     *
     * @param array $criteria Filtering criteria.
     * @param array $sorting Sorting criteria.
     *
     * @return Pagerfanta
     */
    public function createPaginatorForAdvancedSearch(array $criteria, array $sorting): Pagerfanta;
}
