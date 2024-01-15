<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints as Assert;

class RegistrationType extends AbstractType
{
  public function buildForm(FormBuilderInterface $builder, array $options): void
  {
    $builder
      ->add('firstName', TextType::class, [
        'label' => 'Prénom',
        'attr' => [
          'placeholder' => 'Prénom',
          'class' => 'form-control',
          'minlength' => 2,
          'maxlength' => 50
        ],
        'label_attr' => [
          'class' => 'form-label mt-4'
        ],
        'constraints' => [
          new Assert\NotBlank(),
          new Assert\Length(['min' => 2, 'max' => 50]),
        ],
      ])
      ->add('lastName', TextType::class, [
        'label' => 'Nom',
        'attr' => [
          'placeholder' => 'Nom complet',
          'class' => 'form-control',
          'minlength' => 2,
          'maxlength' => 50
        ],
        'label_attr' => [
          'class' => 'form-label mt-4'
        ],
        'constraints' => [
          new Assert\NotBlank(),
          new Assert\Length(['min' => 2, 'max' => 50]),
        ],
        'required' => false,
      ])
      ->add('email', EmailType::class, [
        'label' => 'Adresse email',
        'attr' => [
          'placeholder' => 'Adresse email',
          'class' => 'form-control',
          'minlength' => 4,
          'maxlength' => 180
        ],
        'label_attr' => [
          'class' => 'form-label mt-4'
        ],
        'constraints' => [
          new Assert\NotBlank(),
          new Assert\Email(),
          new Assert\Length(['min' => 4, 'max' => 180]),
        ],
      ])
      ->add('plainPassword', RepeatedType::class, [
        'type' => PasswordType::class,
        'invalid_message' => 'Les mots de passe doivent être identiques.',
        'options' => ['attr' => ['class' => 'form-control']],
        'required' => true,
        'first_options' => [
          'label' => 'Mot de passe',
          'attr' => [
            'class' => 'form-control',
            'placeholder' => 'Mot de passe',
            'minlength' => 8,
            'maxlength' => 50
          ],
          'label_attr' => [
            'class' => 'form-label mt-4'
          ],
          'constraints' => [
            new Assert\NotBlank(),
            new Assert\Length(['min' => 8, 'max' => 50]),
          ],
        ],
        'second_options' => [
          'label' => 'Confirmation du mot de passe',
          'attr' => [
            'class' => 'form-control',
            'placeholder' => 'Confirmation du mot de passe',
            'minlength' => 8,
            'maxlength' => 50
          ],
          'label_attr' => [
            'class' => 'form-label mt-4'
          ],
          'constraints' => [
            new Assert\NotBlank(),
            new Assert\Length(['min' => 8, 'max' => 50]),
          ],
        ],
      ])
      ->add('submit', SubmitType::class, [
        'label' => 'S\'inscrire',
        'attr' => [
          'class' => 'btn btn-primary btn-block mt-4'
        ]
      ]);
  }

  public function configureOptions(OptionsResolver $resolver): void
  {
    $resolver->setDefaults([
      'data_class' => User::class,
    ]);
  }
}
