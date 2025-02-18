<?php

namespace App\Form;

use App\Entity\Pokemon;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Validator\Constraints\File;


class PokemonType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('evolution', EntityType::class, [
                'class' => Pokemon::class,
                'choice_label' => 'name',
                'placeholder' => 'Selecciona una evolución',
                'label' => 'Evolución',
                'required' => false,
            ])
            ->add('number')
            ->add('name')
            ->add('type', ChoiceType::class, [
                'choices' => [
                    'Fuego' => 'fuego',
                    'Agua' => 'agua',
                    'Tierra' => 'tierra',
                    'Eléctrico' => 'electrico',
                    'Bicho' => 'bicho',
                    'Dragón' => 'dragon',
                    'Hada' => 'hada',
                    'Lucha' => 'lucha',
                    'Volador' => 'volador',
                    'Fantasma' => 'fantasma',
                    'Planta' => 'planta',
                    'Hielo' => 'hielo',
                    'Normal' => 'normal',
                    'Veneno' => 'veneno',
                    'Psíquico' => 'psiquico',
                    'Roca' => 'roca',
                    'Acero' => 'acero',
                ],
                'expanded' => true,
                'multiple' => true,
                'attr' => ['class' => 'form-check'],
            ])
            ->add('image', FileType::class, [
                'label' => 'Image',
                'mapped' => true,
                'required' => true,
                'constraints' => [
                    new File([
                        'maxSize' => '1024k',
                        'mimeTypes' => [
                            'image/jpeg',
                            'image/png',
                            'image/gif',
                            'image/webp', // Si deseas soportar WebP
                        ],
                        'mimeTypesMessage' => 'Por favor, sube una imagen válida (JPEG, PNG, GIF, WebP).',
                    ])
                ],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Pokemon::class,
        ]);
    }
}