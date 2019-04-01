<?php

namespace App\DataFixtures;


use Faker\Factory;
use App\Entity\User;
use App\Entity\Image;
use App\Entity\Advert;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AppFixtures extends Fixture
{   
    private $encoder; 

    public function __construct(UserPasswordEncoderInterface $encoder){
        $this->encoder= $encoder;
    }

    public function load(ObjectManager $manager)
    {
        $faker = Factory::create('fr-FR');
        $genres = ['male', 'female']; //termes utilisés par faker!

        //Nous gérons les utilisateurs
        $users = [];        
        
        for($i = 1; $i <=10; $i++){
            $user = new User();

            $genre = $faker->randomElement($genres);
            $picture = 'https://randomuser.me/api/portraits/';
            $pictureId = $faker->numberBetween(1,99) . '.jpg';
            $picture .= ($genre == 'male' ? 'men/' : 'women/') . $pictureId;

            $hash = $this->encoder->encodePassword($user, 'password'); //j encode le mot en dur

            $user->setFirstName($faker->firstName($genre))
                ->setLastName($faker->lastName)
                ->setEmail($faker->email)
                ->setIntroduction($faker->sentence())
                ->setDescription('<p>' . join('</p><p>', $faker->paragraphs(3)) .'</p>')
                ->setHash($hash)
                ->setPicture($picture);

            $manager->persist($user);      
            $users[] = $user;
            }
        
        // Nous gérons les annonces
        for($i = 1; $i <= 30; $i++){            

            $advert = new Advert;

            $title = ("voiture disponible n° $i");
            $coverImage = $faker->imageUrl(1000,350);
            $user = $users[mt_rand(0, count($users)-1)];

            $advert->setTitle($title)
                    ->setCoverImage($coverImage)
                   ->setIntroduction("Je propose ma voiture en location")
                   ->setContent("Lorem ipsum dolor, sit amet consectetur adipisicing elit. Atque nulla expedita maiores, fugit vel est non repudiandae totam? Sed, corporis")
                   ->setPrice(mt_rand(40, 200))
                   ->setSeats(mt_rand(2, 5))
                   ->setAuthor($user);

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
