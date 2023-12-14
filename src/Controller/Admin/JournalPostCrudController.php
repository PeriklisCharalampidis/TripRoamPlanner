<?php

namespace App\Controller\Admin;

use App\Entity\JournalPost;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;


class JournalPostCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return JournalPost::class;
    }


    public function configureFields(string $pageName): iterable
    {
        return [
            DateField::new('date'),
            TextField::new('text'),
            ImageField::new('image')
                ->setBasePath('images')
                ->setUploadDir('public/images')
                // ->setUploadedFileNamePattern('[year]/[month]/[day]/[randomhash].[extension]')
                ->setLabel('Image'),
            AssociationField::new('fk_trip')
                ->setFormTypeOptions([
                    'by_reference' => false, // This is important for ManyToOne associations
                ])
                ->autocomplete()
                ->setLabel('Trip'),
        ];
    }
}
