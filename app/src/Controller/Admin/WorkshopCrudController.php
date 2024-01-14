<?php

namespace App\Controller\Admin;

use App\Entity\Workshop;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class WorkshopCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Workshop::class;
    }


    public function configureFields(string $pageName): iterable
    {
        return [
            TextField::new('name'),
            TextEditorField::new('description'),
            DateTimeField::new('startAt'),
            DateTimeField::new('endAt'),
            AssociationField::new('sector')
                ->setCrudController(SectorCrudController::class)
                ->setFormTypeOptions(['label' => 'secteur'])
                ->autocomplete(),
            AssociationField::new('jobs')
                ->setCrudController(JobCrudController::class)
                ->setFormTypeOptions(['label' => 'Métier(s)'])
                ->autocomplete(),
            AssociationField::new('room')
                ->setCrudController(RoomCrudController::class)
                ->setFormTypeOptions(['label' => 'Salle'])
                ->autocomplete(),
            AssociationField::new('edition')
                ->setCrudController(EditionCrudController::class)
                ->setFormTypeOptions(['label' => 'édition'])
                ->autocomplete(),
            AssociationField::new('speakers')
                ->setCrudController(SpeakerCrudController::class)
                ->setFormTypeOptions(['label' => 'intervenant(s)'])
                ->autocomplete(),
        ];
    }
}
