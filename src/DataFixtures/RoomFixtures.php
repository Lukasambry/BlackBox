<?php

namespace App\DataFixtures;

use App\Entity\Room;
use App\Entity\Theme;
use App\Entity\User;
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
            $room->setIsPrivate(false);
            $room->setMaxCapacity(10);
            $room->setCreatedAt(new \DateTimeImmutable());
            $room->setUpdatedAt(new \DateTimeImmutable());

            $owner = $this->getReference("user_$i", User::class);
            $room->setOwner($owner);

            $theme = $this->getReference("theme_" . (($i - 1) % 5), Theme::class);
            $room->setTheme($theme);

            $manager->persist($room);
            $this->addReference("room_$i", $room);
        }

        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            UserFixtures::class,
            ThemeFixtures::class,
        ];
    }
}
