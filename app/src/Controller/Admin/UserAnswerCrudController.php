<?php

namespace App\Controller\Admin;

use App\Entity\UserAnswer;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class UserAnswerCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return UserAnswer::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            TextField::new('name'),
            AssociationField::new('question'),
            AssociationField::new('user'),
        ];
    }
}
