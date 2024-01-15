<?php

namespace App\Form;

use App\Entity\Question;
use App\Entity\User;
use App\Entity\UserAnswer;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Doctrine\ORM\EntityRepository;

class UserAnswerType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $quizId = $options['quizId'];

        $builder
            ->add('question', EntityType::class, [
                'class' => Question::class,
                'choice_label' => 'name',
                'query_builder' => function (EntityRepository $er) use ($quizId) {
                    return $er->createQueryBuilder('q')
                        ->join('q.quiz', 'quiz')
                        ->where('quiz.id = :quizId')
                        ->setParameter('quizId', $quizId);
                },
                'placeholder' => 'Select a question',
            ])
            // ->add('response', $this->getResponseType($options['data']->getQuestion()))
            ->add('response')
            ->add('submit', SubmitType::class, [
                'attr' => [
                    'class' => 'btn btn-primary mt-4'
                ],
                'label' => 'Sauvegarder',
            ])
        ;
    }

    private function getResponseType(?Question $question)
    {
        if ($question === null) {
            return TextType::class;
        }

        // Determine the type of the question and return the corresponding form field type
        switch ($question->getType()) {
            case 'text':
                return TextType::class;
            case 'choice':
                return ChoiceType::class;
            // Add more cases for other question types if needed
            default:
                return TextType::class;
        }
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => UserAnswer::class,
            'quizId' => null,
            'questions' => [],
        ]);
    }
}