<?php

namespace App\DataFixtures;

use App\Entity\User;
use App\Entity\Vote;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserFixtures extends Fixture implements DependentFixtureInterface
{
    private UserPasswordHasherInterface $passwordHasher;

    public function __construct(UserPasswordHasherInterface $passwordHasher)
    {
        $this->passwordHasher = $passwordHasher;
    }

    public function load(ObjectManager $manager): void
    {
        for ($i = 1; $i <= 10; $i++) {
            $user = new User();
            $user->setEmail("user$i@example.com");
            $user->setNickname("User$i");
            $user->setRoles(['ROLE_USER']);
            $user->setPassword($this->passwordHasher->hashPassword($user, 'password'));
            $user->setHasVoted($i % 2 === 0);
            $user->setVote($this->getReference("vote_" . (($i % 20) + 1), Vote::class));

            $manager->persist($user);

            // Reference to link with other fixtures
            $this->addReference("user_$i", $user);
        }

        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            VoteFixtures::class,
        ];
    }
}