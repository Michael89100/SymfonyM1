<?php

namespace App\Form;

use App\Entity\Activity;
use App\Entity\Job;
use App\Entity\Skill;
use App\Entity\Workshop;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints as Assert;

class JobType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $submitLabel = 'Ajouter le métier';
        if (strpos($options['action'], 'edit') !== false) {
            $submitLabel = 'Enregistrer les modifications';
        }
        $builder
            ->add('name', TextType::class, [
                'attr' => [
                    'class' => 'form-control',
                    'minlength' => '2',
                    'maxlength' => '50'
                ],
                'label' => 'Nom',
                'label_attr' => [
                    'class' => 'form-label mt-4',
                ],
                'constraints' => [
                    new Assert\Length(['min' => 2, 'max' => 50]),
                    new Assert\NotBlank(),
                ],
            ])
            ->add('activities', EntityType::class, [
                'class' => Activity::class,
                'label' => 'Activités',
                'label_attr' => [
                    'class' => 'form-label mt-4',
                ],
                'attr' => [
                    'class' => 'form-control d-flex flex-wrap',
                ],
                'choice_label' => 'name',
                'choice_attr' => function ($choice, $key, $value) {
                    return ['class' => 'form-check-input mx-2'];
                },
                'multiple' => true,
                'expanded' => true,
                'constraints' => [
                    new Assert\NotBlank(),
                ],
            ])
            ->add('skills', EntityType::class, [
                'class' => Skill::class,
                'label' => 'Compétences',
                'label_attr' => [
                    'class' => 'form-label mt-4',
                ],
                'attr' => [
                    'class' => 'form-control d-flex flex-wrap',
                ],
                'choice_label' => 'name',
                'choice_attr' => function ($choice, $key, $value) {
                    return ['class' => 'form-check-input mx-2'];
                },
                'multiple' => true,
                'expanded' => true,
                'constraints' => [
                    new Assert\NotBlank(),
                ],
            ])
            ->add('submit', SubmitType::class, [
                'attr' => [
                    'class' => 'btn btn-primary mt-4'
                ],
                'label' => $submitLabel,
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Job::class,
        ]);
    }
}
