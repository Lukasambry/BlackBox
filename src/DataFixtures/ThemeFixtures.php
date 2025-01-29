<?php

namespace App\DataFixtures;

use App\Entity\Theme;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class ThemeFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $themes = ['Life', 'Work', 'Love', 'Embarrassing', 'Funny'];

        foreach ($themes as $index => $question) {
            $theme = new Theme();
            $theme->setQuestion($question);
            $theme->setCreatedAt(new \DateTimeImmutable());
            $theme->setUpdatedAt(new \DateTimeImmutable());
            $manager->persist($theme);

            $this->addReference("theme_$index", $theme);
        }

        $manager->flush();
    }
}
