<?php

namespace PhpOfBy\AdminBundle\Controller;

use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\ArrayField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\Field;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use PhpOfBy\SecurityBundle\Entity\User;

class UserCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return User::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInSingular('User')
            ->setEntityLabelInPlural('User')
            ->setSearchFields([
                'username',
                'usernameCanonical',
                'email',
                'emailCanonical',
                'confirmationToken',
                'roles',
                'userId',
                'version',
            ]);
    }

    public function configureFields(string $pageName): iterable
    {
        $enabled = Field::new('enabled');
        $username = TextField::new('username');
        $email = TextField::new('email');
        $plainPassword = TextField::new('plainPassword');
        $roles = ArrayField::new('roles');
        $lastLogin = DateTimeField::new('lastLogin');
        $lastLogin
            ->setFormTypeOptions([
                DateTimeField::OPTION_WIDGET => DateTimeField::WIDGET_TEXT,
                'attr' => ['readonly' => true],
            ]);
        $usernameCanonical = TextField::new('usernameCanonical');
        $emailCanonical = TextField::new('emailCanonical');
        $salt = TextField::new('salt');
        $password = TextField::new('password');
        $confirmationToken = TextField::new('confirmationToken');
        $passwordRequestedAt = DateTimeField::new('passwordRequestedAt');
        $userId = IntegerField::new('userId');
        $version = IntegerField::new('version');
        $createdAt = DateTimeField::new('createdAt');
        $updatedAt = DateTimeField::new('updatedAt');

        if (Crud::PAGE_INDEX === $pageName) {
            return [$username, $email, $enabled, $lastLogin, $createdAt, $updatedAt];
        } elseif (Crud::PAGE_DETAIL === $pageName) {
            return [
                $username,
                $usernameCanonical,
                $email,
                $emailCanonical,
                $enabled,
                $salt,
                $password,
                $lastLogin,
                $confirmationToken,
                $passwordRequestedAt,
                $roles,
                $userId,
                $version,
                $createdAt,
                $updatedAt,
            ];
        } elseif (Crud::PAGE_NEW === $pageName) {
            return [$enabled, $username, $email, $plainPassword, $roles];
        } elseif (Crud::PAGE_EDIT === $pageName) {
            return [$enabled, $username, $email, $plainPassword, $roles, $lastLogin];
        }

        return [];
    }
}
