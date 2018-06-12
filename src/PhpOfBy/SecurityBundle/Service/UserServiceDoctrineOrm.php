<?php

namespace PhpOfBy\SecurityBundle\Service;

use FOS\UserBundle\Doctrine\UserManager;
use FOS\UserBundle\Model\UserInterface;

class UserServiceDoctrineOrm extends UserManager implements UserServiceInterface
{
    /**
     * @param array $criteria
     *
     * @return UserInterface|null
     */
    public function findUserBy(array $criteria): ?UserInterface
    {
        if (array_key_exists('id', $criteria)) {
            $criteria['userId'] = $criteria['id'];
            unset($criteria['id']);
        }

        return parent::findUserBy($criteria);
    }
}
