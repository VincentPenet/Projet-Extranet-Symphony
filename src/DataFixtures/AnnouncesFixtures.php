<?php

namespace App\DataFixtures;

use App\Entity\Announces;


use App\DataFixtures\AppFixtures;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class AnnouncesFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {

        $faker = Factory::create('fr_FR');

        for ($i = 0; $i < 5; $i++) {

            $announce = new Announces();
            $announce->setCategories($faker->randomElement($array = array('Stage', 'Alternance', 'Emploi')));
            $announce->setTitle($faker->title());
            $announce->setDescription($faker->text());
            $announce->setOriginalLink($faker->url());
            $announce->setNameCompany($faker->sentence(1));
            $announce->setAdressCompany($faker->address());
            $announce->setAdressAdditional($faker->address());
            $announce->setZipCode($faker->postcode());
            $announce->setCity($faker->city());
            $announce->setSlug($faker->slug());


            $announce->setCreatedAt(\DateTimeImmutable::createFromMutable($faker->dateTimeBetween('-30 days')));

            $announce->setUser($this->getReference("UserId"));

            $manager->persist($announce);
            $manager->flush();
        }
    }
    public function getDependencies()
    {
        return [
            UsersFixtures::class,
        ];
    }
}
