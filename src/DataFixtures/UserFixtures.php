<?php

namespace App\DataFixtures;

use App\Entity\User;
use App\Entity\Vote;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserFixtures extends Fixture
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

            $manager->persist($user);

            $this->addReference("user_$i", $user);
        }

        $userTest = new User();
        $userTest->setEmail('user@test.com');
        $userTest->setNickname('User');
        $userTest->setRoles(['ROLE_USER']);
        $userTest->setPassword($this->passwordHasher->hashPassword($userTest, 'user'));
        $userTest->setIsVerified(true);

        $manager->persist($userTest);

        $admin = new User();
        $admin->setEmail('admin@test.com');
        $admin->setNickname('Admin');
        $admin->setRoles(['ROLE_ADMIN']);
        $admin->setPassword($this->passwordHasher->hashPassword($admin, 'admin'));
        $admin->setIsVerified(true);

        $manager->persist($admin);

        $bannedUser = new User();
        $bannedUser->setEmail('banned@test.com');
        $bannedUser->setNickname('BannedUser');
        $bannedUser->setRoles(['ROLE_BANNED']);
        $bannedUser->setPassword($this->passwordHasher->hashPassword($bannedUser, 'banned'));

        $manager->persist($bannedUser);

        $manager->flush();
    }
}
