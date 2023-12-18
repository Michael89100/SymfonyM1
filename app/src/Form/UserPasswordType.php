<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints as Assert;

class UserPasswordType extends AbstractType
{
  public function buildForm(FormBuilderInterface $builder, array $option): void
  {
    $builder
      ->add('plainPassword', PasswordType::class, [
        'label' => 'Mot de passe actuel',
        'attr' => [
          'placeholder' => 'Mot de passe actuel',
          'class' => 'form-control',
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
      ])
      ->add('newPassword', RepeatedType::class, [
        'type' => PasswordType::class,
        'invalid_message' => 'Les mots de passe doivent être identiques.',
        'options' => ['attr' => ['class' => 'form-control']],
        'required' => true,
        'first_options' => [
          'label' => 'Nouveau mot de passe',
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
            'placeholder' => 'Confirmation du nouveau mot de passe',
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
        'label' => 'Enregistrer',
        'attr' => [
          'class' => 'btn btn-primary btn-block mt-4'
        ]
      ]);
  }
}
