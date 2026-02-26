<?php

namespace App\Form;

use App\Entity\Role;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class UserType extends AbstractType
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
                    )
                ]
            ])
            ->add('mail', EmailType::class, [
                'label' => 'Email',
                'required' => true,
                'constraints' => [
                    new NotBlank(
                        message: 'Veuillez entrer une adresse mail',
                    )
                ]
            ])
            ->add('password', PasswordType::class, [
                'label' => 'Mot de passe',
                'required' => true,
                'constraints' => [
                    new NotBlank(
                        message: 'Veuillez votre mot de passe',
                    ),
                    new Length(
                        min: 6,
                        minMessage: 'Votre mot de passe doit contenir au moins {{ limit }} caractères',
                        max: 255,
                    ),
                ]
            ])
            ->add('roles', EntityType::class, [
                'class'        => Role::class,
                'choice_label' => 'name',
                'multiple'     => true,
                'mapped'       => false,
                'label'        => 'Rôles',
                'required'     => false,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
