<?php

namespace App\DataFixtures;

use App\Entity\Theme;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class ThemeFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $themes = [
            "Racontez votre pire gaffe lors d'un premier rendez-vous amoureux",
            "Partagez votre moment le plus gênant lors d'un repas de famille",
            "Décrivez la blague qui a le plus mal tourné dans votre vie",
            "Racontez votre pire expérience de voyage qui est devenue une anecdote drôle",
            "Partagez votre plus grande méprise d'identité (quand vous avez confondu quelqu'un avec une autre personne)",
            "Racontez votre moment de honte le plus mémorable au travail ou à l'école",
            "Décrivez la fois où vous avez essayé d'impressionner quelqu'un et que tout a mal tourné",
            "Partagez une situation où vous avez ri au moment le plus inapproprié",
            "Racontez la fois où vous avez été pris pour quelqu'un de célèbre",
            "Décrivez votre pire expérience avec la technologie qui est devenue hilarante",
            "Partagez votre plus grand malentendu linguistique ou culturel",
            "Racontez la fois où vous avez accidentellement envoyé un message à la mauvaise personne",
            "Décrivez votre pire tentative de cuisine qui est devenue légendaire",
            "Partagez votre expérience la plus étrange dans les transports en commun",
            "Racontez la fois où vous avez essayé de faire comme dans les films et que ça a mal tourné",
            "Décrivez votre plus grande superstition et comment elle vous a mis dans une situation comique",
            "Partagez votre pire expérience de coiffure ou de relooking",
            "Racontez la fois où vous avez fait semblant de connaître quelque chose et vous vous êtes fait démasquer",
            "Décrivez votre rencontre la plus improbable qui s'est transformée en amitié",
            "Partagez votre plus grand mensonge d'enfant qui vous a rattrapé plus tard",
            "Racontez la fois où vous avez essayé d'être cool et que ça s'est retourné contre vous",
            "Décrivez votre pire cadeau reçu et comment vous avez réagi",
            "Partagez votre plus grande peur irrationnelle et une situation drôle qui en a découlé",
            "Racontez la fois où vous avez été pris en flagrant délit de faire quelque chose d'embarrassant",
            "Décrivez votre plus grande confusion entre deux films/séries qui a créé un quiproquo",
        ];

        foreach ($themes as $index => $question) {
            $theme = new Theme();
            $theme->setQuestion($question);
            $theme->setCreatedAt(new \DateTimeImmutable());
            $theme->setUpdatedAt(new \DateTimeImmutable());

            $manager->persist($theme);
            $this->addReference('theme_' . $index, $theme);
        }

        $manager->flush();
    }
}