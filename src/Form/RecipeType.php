<?php

namespace App\Form;

use App\Entity\Recipe;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\RangeType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use App\Entity\Ingredient;
use App\Repository\IngredientRepository;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;



class RecipeType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('name', TextType::class, [
            'attr' => [
                'class' => 'form-control',
                'minlength' => '2',
                'maxlength' => '50'
            ],
            'label' => 'Name of the recipe',
            'label_attr' => [
                'class' => 'form-label mt-4'
            ],
            'constraints' => [
                new Assert\Length(['min' => 2, 'max' => 50]),
                new Assert\NotBlank()
            ]
        ])
        ->add('time', IntegerType::class, [
            'attr' => [
                'class' => 'form-control',
                'min' => 1,
                'max' => 1440,
            ],
            'required' => false,
            'label' => 'Time in minutes',
            'label_attr' => [
                'class' => 'form-label mt-4'
            ],
            'constraints' => [
                new Assert\Positive(),
                new Assert\LessThan(1441)
            ]
        ])
        ->add('personsNbr', IntegerType::class, [
            'attr' => [
                'class' => 'form-control',
                'min' => 1,
                'max' => 50
            ],
            'required' => false,
            'label' => 'Number of persons',
            'label_attr' => [
                'class' => 'form-label mt-4'
            ],
            'constraints' => [
                new Assert\Positive(),
                new Assert\LessThan(51)
            ]
        ])
        ->add('difficulty', RangeType::class, [
            'attr' => [
                'class' => 'form-range',
                'min' => 1,
                'max' => 5
            ],
            'required' => false,
            'label' => 'Difficulty',
            'label_attr' => [
                'class' => 'form-label mt-4'
            ],
            'constraints' => [
                new Assert\Positive(),
                new Assert\LessThan(6)
            ]
        ])
        ->add('description', TextareaType::class, [
            'attr' => [
                'class' => 'form-control',
                'min' => 1,
                'max' => 5
            ],
            'label' => 'Description',
            'label_attr' => [
                'class' => 'form-label mt-4'
            ],
            'constraints' => [
                new Assert\NotBlank()
            ]
        ])
        ->add('price', MoneyType::class, [
            'attr' => [
                'class' => 'form-control',
            ],
            'required' => false,
            'label' => 'Price ',
            'label_attr' => [
                'class' => 'form-label mt-4'
            ],
            'constraints' => [
                new Assert\Positive(),
                new Assert\LessThan(1001)
            ]
        ])
        ->add('isFavorite', CheckboxType::class, [
            'attr' => [
                'class' => 'form-check-input',
            ],
            'required' => false,
            'label' => 'Is it a favorite ?',
            'label_attr' => [
                'class' => 'form-check-label'
            ],
            'constraints' => [
                new Assert\NotNull()
            ]
        ])
        
        ->add('ingredients', EntityType::class, [
            'class' => Ingredient::class,
            'query_builder' => function (IngredientRepository $er) {
                return $er->createQueryBuilder('i')
                    ->orderBy('i.name', 'ASC');
            },
            'label' => 'Ingredients',
            'label_attr' => [
                'class' => 'form-label mt-4'
            ],
            'choice_label' => 'name',
            'multiple' => true,
            'expanded' => true,
        ])
        ->add('submit', SubmitType::class, [
            'attr' => [
                'class' => 'btn btn-primary mt-4'
            ],
            'label' => 'Create Recipe'
        ]);

    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Recipe::class,
        ]);
    }
}
