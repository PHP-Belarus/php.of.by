<?php

namespace PhpOfBy\WebsiteBundle\Controller;

use CommonBundle\Controller\AbstractServiceController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration as Mvc;

/**
 * @Mvc\Route(service="phpofby_website.controller.default")
 */
class DefaultController extends AbstractServiceController
{
    /**
     * @Mvc\Route("/")
     * @Mvc\Template
     */
    public function indexAction()
    {
        return [];
    }
}
