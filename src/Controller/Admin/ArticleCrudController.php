<?php

namespace App\Controller\Admin;

use App\Entity\Article;
use Doctrine\ORM\EntityManagerInterface;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\SlugField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class ArticleCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Article::class;
    }

    public function configureFields(string $pageName): iterable
    {
        yield IdField::new('id')
            ->onlyOnIndex();
        yield TextField::new('name')
            ->setLabel('Titre');
        yield SlugField::new('slug')
            ->setTargetFieldName('name');
        yield DateField::new('creationDate')
            ->setLabel('Date de création')
            ->onlyWhenUpdating();
        yield AssociationField::new('author')
            ->setLabel('Auteur');
        yield TextareaField::new('excerpt')
            ->setLabel('En tête de l\'article')
            ->setHelp('En-tête (250 caractères au maximum) utilisé en tant que résumé de l\'article dans l\'index');
        yield TextEditorField::new('content')
            ->setLabel('Corps de l\'article')
            ->onlyOnForms();
        yield BooleanField::new('published')
            ->setLabel('Publié');
        yield DateField::new('publishedDate')
            ->setLabel('Date de publication')
            ->hideWhenCreating();
    }

    public function persistEntity(EntityManagerInterface $entityManager, $entityInstance): void
    {
        $this->setPublishedDateOnPublication($entityInstance);
        parent::updateEntity($entityManager, $entityInstance);
    }

    public function updateEntity(EntityManagerInterface $entityManager, $entityInstance): void
    {
        $this->setPublishedDateOnPublication($entityInstance);
        parent::updateEntity($entityManager, $entityInstance);
    }

    private function setPublishedDateOnPublication($entityInstance)
    {
        if ($entityInstance->getPublished() && !($entityInstance->getPublishedDate())) {
            $entityInstance->setPublishedDate(new \DateTimeImmutable());
        }
    }

}
