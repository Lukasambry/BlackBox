<?php

namespace App\DataFixtures;

use App\Entity\Secret;
use App\Entity\User;
use App\Entity\Vote;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class VoteFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        for ($i = 1; $i <= 30; $i++) {
            $vote = new Vote();
            $vote->setCreatedAt(new \DateTimeImmutable());
            $vote->setUpdatedAt(new \DateTimeImmutable());
            $vote->setUser($this->getReference("user_" . (($i % 10) + 1), User::class));
            $vote->setSecret($this->getReference("secret_" . (($i % 20) + 1), Secret::class));

            $manager->persist($vote);

            $this->addReference("vote_$i", $vote);
        }

        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            UserFixtures::class,
            SecretFixtures::class,
        ];
    }
}