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
            ->add('name')
            ->add('question', EntityType::class, [
                'class' => Question::class,
'choice_label' => 'id',
            ])
            ->add('user', EntityType::class, [
                'class' => User::class,
'choice_label' => 'id',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => UserAnswer::class,
        ]);
    }
}
