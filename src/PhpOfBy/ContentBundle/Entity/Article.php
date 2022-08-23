<?php

namespace PhpOfBy\ContentBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Timestampable\Timestampable;
use Gedmo\Timestampable\Traits\TimestampableEntity;

#[ORM\Table()]
#[ORM\Entity(repositoryClass: ArticleRepository::class)]
class Article implements Timestampable
{
    /*
     * Hook timestampable behavior
     * updates createdAt, updatedAt fields
     */
    use TimestampableEntity;

    #[ORM\Column(type: 'integer')]
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'AUTO')]
    /** @var int */
    private $articleId;

    #[ORM\Column(type: 'integer', nullable: false)]
    #[ORM\Version]
    /** @var int */
    private $version;

    #[ORM\Column(type: 'boolean', nullable: false, options: ['default' => 0])]
    /** @var bool */
    private $published = false;

    #[ORM\Column(type: 'string', length: 255, nullable: false)]
    /** @var string */
    private $title;

    #[ORM\Column(type: 'datetime', nullable: false)]
    /** @var \DateTime */
    private $publicationDate;

    #[ORM\Column(type: 'string', length: 255, nullable: false)]
    /** @var string */
    private $teaser;

    #[ORM\Column(type: 'text', length: 50000, nullable: true)]
    /** @var string */
    private $body;

    public function __construct()
    {
        $this->publicationDate = new \DateTime();
    }

    /**
     * @return int
     */
    public function getArticleId()
    {
        return $this->articleId;
    }

    /**
     * Set version.
     *
     * @param int $version
     *
     * @return Article
     */
    public function setVersion($version)
    {
        $this->version = $version;

        return $this;
    }

    /**
     * Get version.
     *
     * @return int
     */
    public function getVersion()
    {
        return $this->version;
    }

    /**
     * @return bool
     */
    public function isPublished()
    {
        return $this->published;
    }

    /**
     * @param bool $published
     *
     * @return Article
     */
    public function setPublished($published)
    {
        $this->published = $published;

        return $this;
    }

    /**
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @param string $title
     *
     * @return Article
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getPublicationDate()
    {
        return $this->publicationDate;
    }

    /**
     * @param \DateTime $publicationDate
     *
     * @return Article
     */
    public function setPublicationDate($publicationDate)
    {
        $this->publicationDate = $publicationDate;

        return $this;
    }

    /**
     * @return string
     */
    public function getTeaser()
    {
        return $this->teaser;
    }

    /**
     * @param string $teaser
     *
     * @return Article
     */
    public function setTeaser($teaser)
    {
        $this->teaser = $teaser;

        return $this;
    }

    /**
     * @return string
     */
    public function getBody()
    {
        return $this->body;
    }

    /**
     * @param string $body
     *
     * @return Article
     */
    public function setBody($body)
    {
        $this->body = $body;

        return $this;
    }
}
