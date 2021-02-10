<?php

namespace App\DataFixtures;

use App\Entity\Article;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        for ($i=0; $i <10; $i++) { 
            $article= new Article();
            $article->setPrenom("Orus n°$i")
                    ->setRace("Staff n°$i")
                    ->setSexe("mâle n°$i")
                    ->setAge("2")
                    ->setDescriptif("<p> voici un grand chien qui est très vif,voici un grand chien qui est très vif n°$i</p>")
                    ->setGrandDescriptif("<p>voici un grand chien qui est très vifvoici un grand chien qui est très vifvoici un grand chien qui est très vifvoici un grand chien qui est très vifvoici un grand chien qui est très vifvoici un grand chien qui est très vifvoici un grand chien qui est très vifvoici un grand chien qui est très vifvoici un grand chien qui est très vif n°$i</p>")
                    ->setEntente("Avec tous les animaux n°$i")
                    ->setCreatedAt(new \DateTime());

                    $manager->persist($article);

        }

        $manager->flush();
    }
}
