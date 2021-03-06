<?php

declare(strict_types=1);

namespace Wingu\EasyAdminPlusBundle\Controller;

use Doctrine\ORM\EntityRepository;
use EasyCorp\Bundle\EasyAdminBundle\Controller\EasyAdminController;
use EasyCorp\Bundle\EasyAdminBundle\Event\EasyAdminEvents;
use Pagerfanta\Pagerfanta;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;
use Symfony\Component\Serializer\Encoder\CsvEncoder;
use Symfony\Contracts\Translation\TranslatorInterface;

class AdminController extends EasyAdminController
{
    protected const FLASH_TYPE_SUCCESS = 'success';

    protected const FLASH_TYPE_INFO = 'info';

    protected const FLASH_TYPE_WARNING = 'warning';

    protected const FLASH_TYPE_ERROR = 'error';

    /**
     * Download the data as CSV.
     *
     * For search all pages will be send to download.
     * For list only current view unless filters are present in which case all pages will be send.
     *
     * @return Response
     */
    public function downloadAction(): Response
    {
        $paginator = $this->getPaginatorForDownload($this->getReferrerAction());

        $data = $this->prepareDataToDownload($paginator->getCurrentPageResults());

        return $this->renderDownloadResponse($data);
    }

    protected function downloadBatchAction(array $ids): Response
    {
        $resources = $this->getRepository()->findBy(
            ['id' => $ids],
            [$this->request->query->get('sortField') => $this->request->query->get('sortDirection')]
        );

        $data = $this->prepareDataToDownload($resources);

        return $this->renderDownloadResponse($data);
    }

    /**
     * @param array|\Traversable $resources
     */
    protected function prepareDataToDownload($resources): array
    {
        $fields = $this->entity['download']['fields'] ?? $this->entity['list']['fields'];
        $data = [];
        foreach ($resources as $item) {
            $row = [];
            foreach ($fields as $field => $metadata) {
                $label = $metadata['label'] ?? \ucfirst($metadata['property']);
                $label = $this->has('translator') ? $this->get('translator')->trans($label) : $label;
                $row[$label] = $this->renderEntityField($item, $metadata);
            }
            $data[] = $row;
        }

        return $data;
    }

    protected function renderDownloadResponse(array $data): Response
    {
        $content = $this->get('serializer')->encode($data, CsvEncoder::FORMAT);
        $filename = \sprintf('export_%s_%s.csv', $this->entity['name'], \date('Y_m_d_H_i_s'));

        $response = new Response($content);
        $response->headers->set('Content-Type', 'text/csv');
        $disposition = $response->headers->makeDisposition(ResponseHeaderBag::DISPOSITION_ATTACHMENT, $filename);
        $response->headers->set('Content-Disposition', $disposition);

        return $response;
    }

    protected function getReferrerAction(): string
    {
        $referrerUrl = $this->request->query->get('referer');
        if ($referrerUrl === null || $referrerUrl === '') {
            return 'list';
        }

        \parse_str(\parse_url(\urldecode($referrerUrl), \PHP_URL_QUERY), $query);

        return $query['action'] ?? 'list';
    }

    protected function renderEntityField($item, array $fieldMetadata): string
    {
        $fieldName = $fieldMetadata['property'];

        try {
            $value = $this->get('easyadmin.property_accessor')->getValue($item, $fieldName);
        } catch (\Exception $e) {
            return '';
        }

        if (isset($fieldMetadata['template'])) {
            return $this->renderView(
                $fieldMetadata['template'],
                \array_merge($fieldMetadata, ['item' => $item, 'value' => $value, 'noHtml' => true])
            );
        }

        try {
            if ($value instanceof \DateTime) {
                return $value->format($fieldMetadata['format'] ?? 'Y-m-d H:i');
            }

            return (string)$value;
        } catch (\Exception $e) {
            return '';
        }
    }

    /**
     * Get the current entity.
     *
     * This is available only actions that operate on one entity (like edit, but not on lists).
     */
    protected function getCurrentEntity()
    {
        $easyAdmin = $this->request->attributes->get('easyadmin');
        if (!isset($easyAdmin['item'])) {
            throw new \LogicException(\sprintf('Entity not available for view "%s".', $easyAdmin['view']));
        }

        return $easyAdmin['item'];
    }

    protected function getRepository(): EntityRepository
    {
        return $this->em->getRepository($this->entity['class']);
    }

    protected function modifyEntity($entity, ?string $successMessage): void
    {
        $this->dispatch(EasyAdminEvents::PRE_UPDATE, ['entity' => $entity]);

        $this->executeDynamicMethod('preUpdate<EntityName>Entity', [$entity]);

        $this->updateEntity($entity);

        $this->dispatch(EasyAdminEvents::POST_UPDATE, ['entity' => $entity]);

        $this->addFlash(self::FLASH_TYPE_SUCCESS, $successMessage);
    }

    protected function getPaginatorForDownload(string $referrer): Pagerfanta
    {
        switch ($referrer) {
            case 'search':
                $paginator = $this->findBy(
                    $this->entity['class'],
                    $this->request->query->get('query'),
                    $this->entity['search']['fields'],
                    1, // page 1
                    \PHP_INT_MAX, // all entries
                    $this->request->query->get('sortField'),
                    $this->request->query->get('sortDirection'),
                    $this->entity['search']['dql_filter']
                );
                break;
            case 'list':
            default:
                if ($this->request->get('filters') !== null) {
                    $paginator = $this->findAll(
                        $this->entity['class'],
                        1, // page 1
                        \PHP_INT_MAX, // all entries
                        $this->request->query->get('sortField'),
                        $this->request->query->get('sortDirection'),
                        $this->entity['list']['dql_filter']
                    );
                } else {
                    $paginator = $this->findAll(
                        $this->entity['class'],
                        $this->request->query->get('page', 1),
                        $this->entity['list']['max_results'],
                        $this->request->query->get('sortField'),
                        $this->request->query->get('sortDirection'),
                        $this->entity['list']['dql_filter']
                    );
                }
        }

        return $paginator;
    }

    public static function getSubscribedServices(): array
    {
        return \array_merge(parent::getSubscribedServices(), [
            'translator' => '?' . TranslatorInterface::class,
        ]);
    }
}
