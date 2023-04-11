<?php

namespace App\DataFixtures;

use App\Entity\Article;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{
    private UserPasswordHasherInterface $passwordHasher;

    public function __construct(UserPasswordHasherInterface $passwordHasher)
    {
        $this->passwordHasher = $passwordHasher;
    }

    public function load(ObjectManager $manager): void
    {
        // admin
        $adminTest = (new User())->setEmail('test@leblog.roumaing')->setRoles(['ROLE_USER']);
        $adminTest->setPassword($this->passwordHasher->hashPassword($adminTest, 'pourTest'));
        $manager->persist($adminTest);

        // article
        $article = (new Article())
            ->setauthor($adminTest)
            ->setName('Premier article test')
            ->setContent('En voilà du contenu.')
            ->setExcerpt('Ici c\'est l\'en-tête');
        $manager->persist($article);

        $manager->flush();
    }
}
