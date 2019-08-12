<?php

declare(strict_types = 1);

namespace Wingu\EasyAdminPlusBundle\Controller;

use FOS\UserBundle\Controller\SecurityController as BaseSecurityController;
use Symfony\Component\HttpFoundation\Response;

class SecurityController extends BaseSecurityController
{
    /**
     * {@inheritdoc}
     */
    protected function renderLogin(array $data): Response
    {
        return new Response($this->get('twig')->render(
            '@EasyAdmin/page/login.html.twig',
            \array_merge(
                $data,
                [
                    'csrf_token_intention' => 'authenticate',
                    'action' => $this->generateUrl('fos_user_security_check'),
                ]
            )
        ));
    }
}
