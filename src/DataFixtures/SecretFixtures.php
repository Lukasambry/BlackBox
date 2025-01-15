<?php

namespace App\DataFixtures;

use App\Entity\Room;
use App\Entity\Secret;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class SecretFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        for ($i = 1; $i <= 20; $i++) {
            $secret = new Secret();
            $secret->setContent("Secret $i");
            $secret->setCreatedAt(new \DateTimeImmutable());
            $secret->setUpdatedAt(new \DateTimeImmutable());
            $secret->setRoom($this->getReference("room_" . (($i % 5) + 1), Room::class));

            $manager->persist($secret);

            // Reference to link with votes
            $this->addReference("secret_$i", $secret);
        }

        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            RoomFixtures::class,
        ];
    }
}