<?php

namespace App\Controller\Admin;

use App\Entity\Student;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\EmailField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class StudentCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Student::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            TextField::new('schoolEmail')
                ->setFormTypeOptions(['label' => "email de l'école"]),
            AssociationField::new('section')
                ->setCrudController(SectionCrudController::class)
                ->autocomplete(),
            AssociationField::new('school')
                ->setCrudController(SchoolCrudController::class)
                ->setFormTypeOptions(['label' => 'école'])
                ->autocomplete(),
            AssociationField::new('edition')
                ->setCrudController(EditionCrudController::class)
                ->setFormTypeOptions(['label' => 'édition'])
                ->autocomplete(),
            AssociationField::new('workshops')
                ->setCrudController(WorkshopCrudController::class)
                ->setFormTypeOptions(['label' => 'ateliers'])
                ->autocomplete(),
            AssociationField::new('user')
            ->setCrudController(UserCrudController::class)
                ->setFormTypeOptions([
                    'by_reference' => true,
                    'label' => 'Utilisateur'
                ])
                ->autocomplete(),
        ];
    }
}
