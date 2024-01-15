<?php

namespace App\Controller\Admin;

use App\Entity\Speaker;
use Doctrine\ORM\EntityManagerInterface;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\EmailField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class SpeakerCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Speaker::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            EmailField::new('socialEmail'),
            AssociationField::new('user')
                ->setCrudController(UserCrudController::class)
                ->setFormTypeOptions(['label' => 'utilisateur'])
                ->autocomplete(),
        ];
    }
}
