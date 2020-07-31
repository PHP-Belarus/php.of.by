<?php

namespace PhpOfBy\AdminBundle\Controller;

use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\Field;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use PhpOfBy\ContentBundle\Entity\Article;

class ArticleCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Article::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInSingular('Article')
            ->setEntityLabelInPlural('Article')
            ->setSearchFields(['articleId', 'version', 'title', 'teaser', 'body']);
    }

    public function configureFields(string $pageName): iterable
    {
        $published = Field::new('published');
        $title = TextField::new('title');
        $publicationDate = DateTimeField::new('publicationDate');
        $publicationDate->setCustomOption(DateTimeField::OPTION_WIDGET, DateTimeField::WIDGET_CHOICE);
        $teaser = TextField::new('teaser');
        $body = TextEditorField::new('body');
        $articleId = IntegerField::new('articleId');
        $version = IntegerField::new('version');
        $createdAt = DateTimeField::new('createdAt');
        $updatedAt = DateTimeField::new('updatedAt');

        if (Crud::PAGE_INDEX === $pageName) {
            return [$articleId, $published, $title, $publicationDate, $createdAt, $updatedAt];
        } elseif (Crud::PAGE_DETAIL === $pageName) {
            return [$articleId, $version, $published, $title, $publicationDate, $teaser, $body, $createdAt, $updatedAt];
        } elseif (Crud::PAGE_NEW === $pageName) {
            return [$published, $title, $publicationDate, $teaser, $body];
        } elseif (Crud::PAGE_EDIT === $pageName) {
            return [$published, $title, $publicationDate, $teaser, $body];
        }

        return [];
    }
}
