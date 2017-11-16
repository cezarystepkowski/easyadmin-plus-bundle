<?php

declare(strict_types = 1);

namespace Wingu\EasyAdminPlusBundle\Controller;

use FOS\UserBundle\Controller\SecurityController as BaseSecurityController;

class SecurityController extends BaseSecurityController
{
    /**
     * {@inheritdoc}
     */
    protected function renderLogin(array $data)
    {
        $data['logo'] = $this->getParameter('wingu_easy_admin_plus.logo');
        $data['title'] = $this->getParameter('wingu_easy_admin_plus.title');

        return $this
            ->get('templating')
            ->renderResponse(
                '@WinguEasyAdminPlus/security/login.html.twig',
                $data
            );
    }
}
