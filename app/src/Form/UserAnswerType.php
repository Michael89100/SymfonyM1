<?php

namespace App\Form;

use App\Entity\Question;
use App\Entity\User;
use App\Entity\UserAnswer;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserAnswerType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            
            ->add('name', null, [
                'label' => 'Prénom', 
                'attr' => [
                    'placeholder' => 'Entre votre Nom',
                    'class' => 'form-control',
                ],
            ])
            ->add('question', EntityType::class, [
                'class' => Question::class,
                'choice_label' => 'id', 
                'label' => 'Selectionné une question', 
                'attr' => [
                    'class' => 'form-control',
                ],
            ])
            ->add('user', EntityType::class, [
                'class' => User::class,
                'choice_label' => 'id', 
                'label' => 'Selectionné un utilisateur', 
                'attr' => [
                    'class' => 'form-control',
                ],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => UserAnswer::class,
        ]);
    }
}