<?php

namespace App\Controller\Admin;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\EmailField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserCrudController extends AbstractCrudController
{
    public function __construct(private UserPasswordHasherInterface $passwordHasher)
    {

    }

    public static function getEntityFqcn(): string
    {
        return User::class;
    }

    public function configureFields(string $pageName): iterable
    {
        $passwordInput = TextField::new('plainPassword')
            ->setFormType(PasswordType::class);

        return [
            EmailField::new('email'),
            TextField::new('firstName'),
            TextField::new('lastName'),
            $passwordInput,
            ChoiceField::new('roles')
                ->setChoices([
                    'Simple utilisateur' => 'ROLE_USER',
                    'Administrateur' => 'ROLE_ADMIN',
                ])
                ->allowMultipleChoices()
                ->autocomplete()
        ];
    }

    public function persistEntity(EntityManagerInterface $entityManager, $entityInstance): void
    {

        $entityInstance->setPassword(
            $this->passwordHasher->hashPassword(
                $entityInstance,
                $entityInstance->getPlainPassword()
            )
        );
        parent::persistEntity($entityManager, $entityInstance);
    }
}
