<?php

namespace App\Form;

use App\Entity\Artwork;
use App\Entity\Tag;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ArtworkType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title')
            ->add('description')
            ->add('picture')
            ->add('drawingCreatedAt', DateType::class, [
                'widget' => 'single_text', // Permet d'afficher un calendrier de sélection de date
                'html5' => true, // Utilise le widget HTML5 si disponible
            ])            ->add('tag', EntityType::class, [
                "multiple" => true,
                "expanded" => true, // radiobutton
                "class" => Tag::class,
                'choice_label' => 'name',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Artwork::class,

        ]);
    }
}
