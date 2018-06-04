<?php

namespace PhpOfBy\SecurityBundle\Service;

use FOS\UserBundle\Doctrine\UserManager;
use FOS\UserBundle\Model\UserInterface;

class UserManagerServiceDoctrineOrm extends UserManager implements UserManagerServiceInterface
{
    /**
     * @param array $criteria
     *
     * @return UserInterface
     */
    public function findUserBy(array $criteria)
    {
        if (array_key_exists('id', $criteria)) {
            $criteria['userId'] = $criteria['id'];
            unset($criteria['id']);
        }

        return parent::findUserBy($criteria);
    }
}
