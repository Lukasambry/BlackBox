<?php

namespace App\DataFixtures;

use App\Entity\Secret;
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
            $vote->setSecret($this->getReference("secret_" . (($i % 20) + 1), Secret::class));

            $manager->persist($vote);

            // Reference to link with other fixtures
            $this->addReference("vote_$i", $vote);
        }

        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            SecretFixtures::class,
        ];
    }
}