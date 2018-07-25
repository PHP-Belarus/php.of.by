<?php

namespace PhpOfBy\WebsiteBundle\Controller;

use CommonBundle\Controller\AbstractServiceController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration as Mvc;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Mvc\Route(service="phpofby_website.controller.default")
 */
class DefaultController extends AbstractServiceController
{
    /**
     * @Route("/")
     * @Mvc\Template
     *
     * @return array
     */
    public function indexAction(): array
    {
        return [];
    }
}
