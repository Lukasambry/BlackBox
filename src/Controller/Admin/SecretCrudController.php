<?php

namespace App\Controller\Admin;

use App\Entity\Secret;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class SecretCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Secret::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->hideOnForm(),
            AssociationField::new('user')
                ->setFormTypeOptions(['choice_label' => 'email']),
            AssociationField::new('room')
                ->setFormTypeOptions(['choice_label' => 'name']),
            TextEditorField::new('content'),
            IntegerField::new('votesUp'),
            IntegerField::new('votesDown'),
        ];
    }
}
