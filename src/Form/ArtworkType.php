<?php

namespace App\Form;

use App\Entity\Artwork;
use App\Entity\Tag;
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
            ->add('createdAt')
            ->add('drawingCreatedAt')
            ->add('tag', EntityType::class, [
                // * c'est un ChoiceType : multiple + expanded
                "multiple" => true,
                "expanded" => true, // radiobutton
                // ! The required option "class" is missing.
                // ? à quelle entité est on lié ?
                "class" => Tag::class,
                // ! Object of class Proxies\__CG__\App\Entity\Type could not be converted to string
                // on doit préciser la propriété pour l'affichage
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
