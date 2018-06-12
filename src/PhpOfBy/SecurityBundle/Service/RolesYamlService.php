<?php

namespace PhpOfBy\SecurityBundle\Service;

class RolesYamlService implements RolesServiceInterface
{
    /**
     * @var array
     */
    private $roles = [];

    /**
     * @param array $roles
     */
    public function __construct(array $roles)
    {
        $this->roles = $roles;
    }

    /**
     * @return array
     */
    public function getRoles()
    {
        $result = [];
        foreach (array_keys($this->roles) as $roleName) {
            $result[$roleName] = $roleName;
        }

        return $result;
    }
}
