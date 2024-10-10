<?php

namespace PhpOfBy\SecurityBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use FOS\UserBundle\Model\User as BaseUser;
use Gedmo\Timestampable\Timestampable;
use Gedmo\Timestampable\Traits\TimestampableEntity;

#[ORM\Table()]
#[ORM\Entity()]
class User extends BaseUser implements Timestampable, UserInterface
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
    protected $userId;

    #[ORM\Column(type: 'integer', nullable: false)]
    #[ORM\Version]
    /** @var int */
    private $version;

    /**
     * Override method because we need to user userId instead of just id
     * Serializes the user.
     *
     * The serialized data have to contain the fields used during check for
     * changes and the id.
     *
     * @return array
     */
    public function __serialize(): array
    {
        return [
            $this->password,
            $this->salt,
            $this->usernameCanonical,
            $this->username,
            $this->enabled,
            $this->getUserId(),
            $this->email,
            $this->emailCanonical,
        ];
    }

    /**
     * Override method because we need to user userId instead of just id
     * Unserializes the user.
     *
     * @param array $data
     */
    public function __unserialize(array $data): void
    {
        if (13 === count($data)) {
            // Unserializing a User object from 1.3.x
            unset($data[4], $data[5], $data[6], $data[9], $data[10]);
            $data = array_values($data);
        } elseif (11 === count($data)) {
            // Unserializing a User from a dev version somewhere between 2.0-alpha3 and 2.0-beta1
            unset($data[4], $data[7], $data[8]);
            $data = array_values($data);
        }

        list(
            $this->password,
            $this->salt,
            $this->usernameCanonical,
            $this->username,
            $this->enabled,
            $this->userId,
            $this->email,
            $this->emailCanonical,
        ) = $data;
    }

    /**
     * @return int
     */
    public function getUserId()
    {
        return $this->userId;
    }

    /**
     * Need to override this method because we are using different field for storing user id.
     *
     * @return int
     */
    public function getId()
    {
        return $this->userId;
    }

    /**
     * Set version.
     *
     * @param int $version
     *
     * @return User
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
}
