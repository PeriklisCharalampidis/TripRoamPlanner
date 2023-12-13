<?php

// namespace App\Controller\Admin;

// use App\Entity\User;
// use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
// use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
// use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
// use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

// class UserCrudController extends AbstractCrudController
// {
//     public static function getEntityFqcn(): string
//     {
//         return User::class;
//     }

//     /*
//     public function configureFields(string $pageName): iterable
//     {
//         return [
//             IdField::new('id'),
//             TextField::new('title'),
//             TextEditorField::new('description'),
//         ];
//     }
//     */
// }<?php

namespace App\Controller\Admin;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class UserCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return User::class;
    }

    /*
    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id'),
            TextField::new('username'),
            TextEditorField::new('password')->onlyOnForms(), // Only show password field in forms
        ];
    }
    */

    // Hash the password before storing it in the database
    public function hashPassword(User $user): void
    {
        $plainPassword = $user->getPassword(); // Assuming the password is a property of the User entity
        $hashedPassword = password_hash($plainPassword, PASSWORD_DEFAULT);

        $user->setPassword($hashedPassword);
    }

    // Override the persistEntity method to hash the password before saving the entity
    public function persistEntity(EntityManagerInterface $entityManager, $entityInstance): void
    {
        if ($entityInstance instanceof User) {
            $this->hashPassword($entityInstance);
        }

        parent::persistEntity($entityManager, $entityInstance);
    }
}
