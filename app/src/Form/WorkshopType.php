<?php

namespace App\Form;

use App\Entity\Edition;
use App\Entity\Job;
use App\Entity\Room;
use App\Entity\Sector;
use App\Entity\Student;
use App\Entity\Workshop;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class WorkshopType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('startAt')
            ->add('endAt')
            ->add('name')
            ->add('description')
            ->add('room', EntityType::class, [
                'class' => Room::class,
'choice_label' => 'id',
            ])
            ->add('jobs', EntityType::class, [
                'class' => Job::class,
'choice_label' => 'id',
'multiple' => true,
            ])
            ->add('sector', EntityType::class, [
                'class' => Sector::class,
'choice_label' => 'id',
            ])
            ->add('students', EntityType::class, [
                'class' => Student::class,
'choice_label' => 'id',
'multiple' => true,
            ])
            ->add('edition', EntityType::class, [
                'class' => Edition::class,
'choice_label' => 'id',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Workshop::class,
        ]);
    }
}
