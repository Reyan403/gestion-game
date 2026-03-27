<?php

namespace App\Form;

use App\Entity\Role;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;

class UserManagementType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
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
