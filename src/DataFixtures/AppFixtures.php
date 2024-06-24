<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\Tag;
use App\Entity\Artwork;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {


        $faker = \Faker\Factory::create();

        // je crée mes tags
        $tags = ["portrait", "science-fiction", "jeux-video", "femme", "manga", "paysage", "personnage", "fantasy", "animal", "Fantastique", "abstrait", "érotique", "humoristique", "Bande-dessinée", "crayon", "Western"];

        // Tableau vide pour tous les objets tags que je vais créer
        $allTags = [];

        // je fait une boucle pour créer un objet Tag pour chaque tag du tableau
        foreach ($tags as $tag) {

            $newTag = new Tag();
            $newTag->setName($tag);
            $newTag->setDescription("dessin qui parle de ". $tag);

            $manager->persist($newTag);

            $allTags[] = $newTag;
        }

        //je créer un tableau vide pour tout les objets artwork crée
        $allArtwork = [];

        //je crée 50 artworks
        for ($i = 0; $i < 50; $i++) {

            $newArtwork = new Artwork();
            $newArtwork->setTitle($faker->sentence($nbWords = 6, $variableNbWords = true));
            $newArtwork->setDescription($faker->paragraph($nbSentences = 3, $variableNbSentences = true));
            $newArtwork->setPicture("https://picsum.photos/seed/".$i."/600/400");
            $newArtwork->setCreatedAt(new \DateTime());
            $newArtwork->setDrawingCreatedAt($faker->dateTimeBetween($startDate = '-1 years', $endDate = 'now'));

            // Sélection aléatoire de 4 tags parmi tous les tags disponibles
            $randomTags = $this->getRandomTags($allTags, 4);
            foreach ($randomTags as $tag) {
                $newArtwork->addTag($tag);
            }

            $manager->persist($newArtwork);

            $allArtwork[] = $newArtwork;
        }




        $manager->flush();
    }
    /**
     * Sélectionne un nombre aléatoire de tags parmi une liste de tags donnée.
     *
     * @param array $tags Liste complète des tags disponibles
     * @param int $count Nombre de tags à sélectionner
     * @return array Tableau de tags sélectionnés
     */
    private function getRandomTags(array $tags, int $count): array
    {
        shuffle($tags); // Mélange les tags
        return array_slice($tags, 0, $count); // Sélectionne les premiers $count tags mélangés
    }
}
