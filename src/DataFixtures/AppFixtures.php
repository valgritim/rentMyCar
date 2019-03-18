<?php

namespace App\DataFixtures;


use Faker\Factory;
use App\Entity\Image;
use App\Entity\Advert;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $faker = Factory::create('fr-FR');

        for($i = 1; $i <= 30; $i++){            

            $advert = new Advert;

            $title = ("voiture disponible n° $i");
            $coverImage = $faker->imageUrl(1000,350);
            $advert->setTitle($title)
                    ->setCoverImage($coverImage)
                   ->setIntroduction("Je propose ma voiture en location")
                   ->setContent("Lorem ipsum dolor, sit amet consectetur adipisicing elit. Atque nulla expedita maiores, fugit vel est non repudiandae totam? Sed, corporis")
                   ->setPrice(mt_rand(40, 200))
                   ->setSeats(mt_rand(2, 5));

            for($j = 1; $j<=mt_rand(2, 5); $j++){

                $image = new Image();
                $image->setUrl($faker->imageUrl())
                    ->setCaption($faker->sentence())
                    ->setAdvert($advert);
                $manager->persist($image);
            }
            
            $manager->persist($advert);

        }

        // $product = new Product();
        // $manager->persist($product);

        $manager->flush($advert);
    }
}
