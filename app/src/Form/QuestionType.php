<?php

namespace App\Form;

use App\Entity\Question;
use App\Entity\Quiz;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ImprovedQuestionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            // Intitulé de la question
            ->add('name', TextType::class, [
                'label' => 'Intitulé de la question',
                'attr' => [
                    'placeholder' => 'Saisissez l\'intitulé complet de la question',
                    'class' => 'form-control',
                ],
            ])
            // Type de la question
            ->add('type', TextType::class, [
                'label' => 'Type de question',
                'attr' => [
                    'placeholder' => 'Exemple : Choix Multiple, Vrai/Faux, Ouvrir',
                    'class' => 'form-control',
                ],
            ])
            // Quiz associé à la question
            ->add('quiz', EntityType::class, [
                'class' => Quiz::class,
                'choice_label' => 'id', 
                'label' => 'Sélectionnez le quiz associé à cette question',
                'attr' => [
                    'class' => 'form-control',
                ],
            ])
            
            ->add('submit', SubmitType::class, [
                'label' => 'Enregistrer la question',
                'attr' => [
                    'class' => 'btn btn-primary btn-block mt-4'
                ]
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Question::class,
        ]);
    }
}
