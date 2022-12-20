<?php

namespace App\DataFixtures;

use App\Entity\Messages;
use App\Entity\Users;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class MessagesFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        // utilise le faker pour envoyer des donnés aléatoires
        $faker = Factory::create('fr_FR');

        // Créer 30 messages avec une boucle for 
        for ($nbMessages = 1; $nbMessages <20; $nbMessages++) {

            $messages = new Messages();
            $messages->SetObject($faker->sentence(4));
            $messages->setMessage($faker->text(50));
            $messages->SetStatus($faker->boolean(50)); // mettre un chiffre 50 pour avoir 50% de chance d'avoir TRUE



            $messages->setCreatedAt(\DateTimeImmutable::createFromMutable($faker->dateTimeBetween('now', '+30 years')));
            $messages->SetUser($this->getReference("UserId")); // récuperer l'objet dans UsersFixture de la table UsersId

            $messages->setTarget($this->getReference("TargetId")); // récuperer l'objet dans UsersFixture de la table TargetId
            $manager->persist($messages);

        }

        // persistes les messages
        // envoi la bdd 
            $manager->flush();
    }
    public function getDependencies()
    {
        return [
            UsersFixtures::class,
        ];
    }
}
