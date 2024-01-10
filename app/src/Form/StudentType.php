<?php

namespace App\Form;

use App\Entity\School;
use App\Entity\Section;
use App\Entity\Student;
use App\Entity\User;
use App\Entity\Workshop;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints as Assert;

class StudentType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('schoolEmail', EmailType::class, [
                'label' => 'Votre Email de d\'école',
                'attr' => [
                    'placeholder' => 'Votre Email de d\'école',
                    'class' => 'form-control',
                    'minlength' => '3',
                    'maxlength' => '255',
                ],
                'label_attr' => [
                    'class' => 'form-label mt-4',
                ],
                'required' => true,
                'constraints' => [
                    new Assert\NotBlank(),
                    new Assert\Email(),
                    new Assert\Length([
                        'min' => 3,
                        'max' => 255,
                    ]),
                ],
            ])
            ->add('school', EntityType::class, [
                'class' => School::class,
                'choice_label' => 'name',
                'label' => 'Votre école',
                'attr' => [
                    'class' => 'form-select',
                ],
                'label_attr' => [
                    'class' => 'form-label mt-4',
                ],
                'placeholder' => 'Choisissez votre école',
                'required' => true,
            ])
            ->add('section', EntityType::class, [
                'class' => Section::class,
                'choice_label' => 'name',
                'label' => 'Votre section',
                'attr' => [
                    'class' => 'form-select',
                ],
                'label_attr' => [
                    'class' => 'form-label mt-4',
                ],
                'placeholder' => 'Choisissez votre section',
                'required' => true,
            ])
            ->add('submit', SubmitType::class, [
                'attr' => [
                    'class' => 'btn btn-primary mt-4'
                ],
                'label' => "Valider mon inscription",
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Student::class,
        ]);
    }
}
