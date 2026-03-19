<?php

namespace App\Form;

use App\Entity\Game;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class GameValidationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('approve', SubmitType::class, [
                'label' => 'Approuver',
            ])
            ->add('archive', SubmitType::class, [
                'label' => 'Archiver',
            ])
            ->add('remove', SubmitType::class, [
                'label' => 'Suprimer définitivement',
            ])
            ->add('restore', SubmitType::class, [
                'label' => 'Restaurer',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Game::class,
            // Permet d'enlever le CSRF token
            'csrf_protection' => false,
            // Permet de dire à Symfony d'ignorer les vieux token envoyés par le navigateur, il ignore et valide le formulaire
            'allow_extra_fields' => true,
        ]);
    }
}
