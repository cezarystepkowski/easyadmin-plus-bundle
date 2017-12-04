<?php

declare(strict_types = 1);

namespace Wingu\EasyAdminPlusBundle\Controller;

use EasyCorp\Bundle\EasyAdminBundle\Event\EasyAdminEvents;
use Pagerfanta\Pagerfanta;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Response;
use Wingu\EasyAdminPlusBundle\Doctrine\ORM\AdvancedSearchRepository;
use Wingu\EasyAdminPlusBundle\Form\Type\BooleanType;

abstract class AdvancedSearchAdminController extends AdminController
{
    public function listAction(): Response
    {
        $this->dispatch(EasyAdminEvents::PRE_LIST);

        $fields = $this->entity['list']['fields'];
        $paginator = $this->findAll(
            $this->entity['class'],
            $this->request->query->get('page', 1),
            $this->config['list']['max_results'],
            $this->request->query->get('sortField'),
            $this->request->query->get('sortDirection'),
            $this->entity['list']['dql_filter']
        );

        $this->dispatch(EasyAdminEvents::POST_LIST, ['paginator' => $paginator]);

        return $this->render(
            $this->entity['templates']['list'],
            [
                'paginator' => $paginator,
                'fields' => $fields,
                'delete_form_template' => $this->createDeleteForm($this->entity['name'], '__id__')->createView(),
                'advanced_search_form' => $this->buildAdvancedSearchFormView()->createView()
            ]
        );
    }

    private function buildAdvancedSearchFormView(): FormInterface
    {
        /** @var FormBuilderInterface $formBuilder */
        $formBuilder = $this->get('form.factory')->createNamedBuilder('search');
        foreach ($this->entity['search']['fields'] as $key => $field) {
            $field = $this->getFormTypeForField($field['type']);
            $formBuilder->add($key, $field['type'], $field['options']);
        }

        return $formBuilder->getForm()->handleRequest($this->request);
    }

    protected function getFormTypeForField(string $fieldType): array
    {
        $options = ['required' => false];
        switch ($fieldType) {
            case 'integer':
                return ['type' => IntegerType::class, 'options' => $options];
            case 'boolean':
                return ['type' => BooleanType::class, 'options' => $options];
            default:
                return ['type' => TextType::class, 'options' => $options];
        }
    }

    public function searchAdvancedAction(): Response
    {
        $paginator = $this->getPaginatorForAdvancedSearch();
        $paginator->setMaxPerPage($this->config['list']['max_results']);
        $paginator->setCurrentPage($this->request->query->get('page', 1));

        return $this->render(
            $this->entity['templates']['list'],
            [
                'paginator' => $paginator,
                'fields' => $this->entity['list']['fields'],
                'advanced_search_form' => $this->buildAdvancedSearchFormView()->createView(),
                'delete_form_template' => $this->createDeleteForm($this->entity['name'], '__id__')->createView()
            ]
        );
    }

    protected function getPaginatorForDownload(string $referrer): Pagerfanta
    {
        if ($referrer === 'searchAdvanced') {
            $paginator = $this->getPaginatorForAdvancedSearch();
            $paginator->setCurrentPage(1); // page 1
            $paginator->setMaxPerPage(\PHP_INT_MAX);// all entries

            return $paginator;
        }

        return parent::getPaginatorForDownload($referrer);
    }

    private function getPaginatorForAdvancedSearch(): Pagerfanta
    {
        $repository = $this->getAdvancedSearchRepository();

        $criteria = $this->buildAdvancedSearchFormView()->getData();
        $repositoryCriteria = [];
        $user = null;
        foreach ($this->entity['search']['fields'] as $key => $field) {
            if (!isset($criteria[$key])) {
                continue;
            }

            $repositoryCriteria[$key] = [
                'value' => $criteria[$key],
                'type' => $field['type']
            ];
        }

        $repositorySorting = [$this->request->query->get('sortField') => $this->request->query->get('sortDirection')];
        $paginator = $repository->createPaginatorForAdvancedSearch($repositoryCriteria, $repositorySorting);

        return $paginator;
    }

    abstract protected function getAdvancedSearchRepository(): AdvancedSearchRepository;
}
