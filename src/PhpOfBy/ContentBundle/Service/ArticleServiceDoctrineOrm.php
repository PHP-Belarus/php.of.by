<?php

namespace PhpOfBy\ContentBundle\Service;

use Doctrine\Bundle\DoctrineBundle\Registry as DoctrineRegistry;
use Doctrine\Common\Persistence\ObjectRepository;

class ArticleServiceDoctrineOrm implements ArticleServiceInterface
{
    const ENTITY_NAME = 'PhpOfByContentBundle:Article';

    /** @var ObjectRepository */
    private $entityManager;

    /**
     * FIXME: We need to fix it with  proper type later. May be by supplying repo as service.
     *
     * @param DoctrineRegistry $registry
     */
    public function __construct(DoctrineRegistry $registry)
    {
        $this->entityManager = $registry->getManager()->getRepository(self::ENTITY_NAME);
    }
}
