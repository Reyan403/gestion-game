<?php

namespace App\Form;

use App\Entity\Category;
use App\Entity\Game;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;

class GameType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title', TextType::class, [
                'label' => 'Titre',
                'constraints' => [
                    new NotBlank(
                        message: 'Veuillez entrerle titre du jeu.'
                    )
                ]
            ])
            ->add('description', TextType::class, [
                'label' => 'Description',
                'constraints' => [
                    new NotBlank(
                        message: 'Veuillez entrer la description du jeu.'
                    )
                ]
            ])
            ->add('image', FileType::class, [
                'label' => 'URL de l\'image',
                'constraints' => [
                    new NotBlank(
                        message: 'Veuillez l\'url de l\'image'
                    )
                ]
            ])
            ->add('categories', EntityType::class, [
                'class' => Category::class,
                'choice_label' => 'id',
                'multiple' => true,
                'label' => 'Catégories',
                'constraints' => [
                    new NotBlank (
                        message: 'Veuillez entrer les catégories de ce jeu.'
                    )
                ]
            ])
            ->add('submit', SubmitType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Game::class,
        ]);
    }
}
