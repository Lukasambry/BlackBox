<?php

namespace App\DataFixtures;

use App\Entity\Room;
use App\Entity\Theme;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class RoomFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        for ($i = 1; $i <= 5; $i++) {
            $room = new Room();
            $room->setName("Room $i");
            $room->setIsActive(true);
            $room->setCreatedAt(new \DateTimeImmutable());
            $room->setUpdatedAt(new \DateTimeImmutable());
            $room->setTheme($this->getReference("theme_" . ($i % 5), Theme::class));

            $manager->persist($room);

            // Reference to link with secrets
            $this->addReference("room_$i", $room);
        }

        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            ThemeFixtures::class,
        ];
    }
}