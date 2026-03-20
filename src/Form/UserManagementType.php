<?php

namespace App\Form;

use App\Entity\Role;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class UserManagementType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'label' => 'Nom',
                'required' => true,
                'constraints' => [
                    new NotBlank(
                        message: 'Veuillez entrer un nom',
                    ),
                    new Length(
                        min: 2,
                        minMessage: 'Votre nom doit contenir au moins {{ limit }} caractères',
                        max: 255,
                    ),
                ],
            ])
            ->add('mail', EmailType::class, [
                'label' => 'Email',
                'required' => true,
                'constraints' => [
                    new NotBlank(
                        message: 'Veuillez entrer une adresse mail',
                    ),
                ],
            ])
            ->add('rolesEntities', EntityType::class, [
                'class' => Role::class,
                'choice_label' => 'name',
                'multiple' => true,
                'expanded' => true,
                'label' => 'Rôle',
                'constraints' => [
                    new NotBlank(
                        message: 'Veuillez entrer le rôle de l\'utilisateur.'
                    ),
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
            // Permet d'enlever le CSRF token
            'csrf_protection' => false,
            // Permet de dire à Symfony d'ignorer les vieux token envoyés par le navigateur, il ignore et valide le formulaire
            'allow_extra_fields' => true,
        ]);
    }
}
