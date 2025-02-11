<?php

namespace App\Form;

use App\Entity\Pokemon;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class PokemonType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('number')
            ->add('name')
            ->add('type', ChoiceType::class, [
                'choices' => [
                    'Fuego' => 'fuego',
                    'Agua' => 'agua',
                    'Tierra' => 'tierra',
                    'Eléctrico' => 'electrico',
                ],
                'expanded' => true,
                'multiple' => true,
            ])
            ->add('image');
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Pokemon::class,
        ]);
    }
}
