<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class RightTableType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('rights', CollectionType::class, [
                // Pourquoi faire deux fichiers ? Parce que le RightType est utilisé pour chaque droit et le RightTableType est utilisé pour chaque rôle
                'entry_type' => RightType::class,
                'entry_options' => ['label' => false],
                'label' => false,
                'allow_add' => false,
                'allow_delete' => false,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => null,
        ]);
    }
}
