<?php

namespace App\Form;

use App\Entity\Category;
use App\Entity\GameUpdate;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Image;
use Symfony\Component\Validator\Constraints\NotBlank;

class GameUpdateType extends AbstractType
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
                'mapped' => false,
                'required' => false,
                'constraints' => [
                    new Image(
                        maxSize : '2M'
                    ),
                ]
            ])
            ->add('categories', EntityType::class, [
                'class' => Category::class,
                'choice_label' => 'name',
                'multiple' => true,
                'expanded' => true,
                'label' => 'Catégories',
                'constraints' => [
                    new NotBlank (
                        message: 'Veuillez entrer les catégories de ce jeu.'
                    )
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => GameUpdate::class,
            // Permet d'enlever le CSRF token
            'csrf_protection' => false,
            // Permet de dire à Symfony d'ignorer les vieux token envoyés par le navigateur, il ignore et valide le formulaire
            'allow_extra_fields' => true,
        ]);
    }
}
