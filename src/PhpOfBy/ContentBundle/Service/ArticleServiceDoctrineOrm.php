<?php

namespace PhpOfBy\ContentBundle\Service;

use Doctrine\Bundle\DoctrineBundle\Registry as DoctrineRegistry;
use Doctrine\Persistence\ObjectRepository;
use PhpOfBy\ContentBundle\Entity\Article;

class ArticleServiceDoctrineOrm implements ArticleServiceInterface
{
    /** @var ObjectRepository */
    private $entityManager;

    /**
     * FIXME: We need to fix it with  proper type later. May be by supplying repo as service.
     *
     * @param DoctrineRegistry $registry
     */
    public function __construct(DoctrineRegistry $registry)
    {
        $this->entityManager = $registry->getManager()->getRepository(Article::class);
    }
}
