<?php

namespace App\Tests;

use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class RegistrationTest extends WebTestCase
{
    public function testDisplayRegistrationPage(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/register');

        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('h1', 'Register');
    }

    public function testSuccessfulRegistration(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/register');

        $form = $crawler->selectButton('Register')->form();

        $form['registration_form[nickname]'] = 'TestUser';
        $form['registration_form[email]'] = 'testuser@example.com';
        $form['registration_form[plainPassword]'] = 'password123';
        $form['registration_form[confirmPassword]'] = 'password123';
        $form['registration_form[agreeTerms]']->tick();

        $client->submit($form);

        $user = static::getContainer()->get('doctrine')
            ->getRepository(User::class)
            ->findOneBy(['email' => 'testuser@example.com']);

        $this->assertNotNull($user, 'L\'utilisateur doit être créé en base de données.');
        $this->assertSame('TestUser', $user->getNickname());
    }

    public function testRegistrationWithPasswordMismatch(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/register');

        $form = $crawler->selectButton('Register')->form();

        $form['registration_form[nickname]'] = 'TestUser2';
        $form['registration_form[email]'] = 'testuser2@example.com';
        $form['registration_form[plainPassword]'] = 'password123';
        $form['registration_form[confirmPassword]'] = 'differentPassword';
        $form['registration_form[agreeTerms]']->tick();

        $crawler = $client->submit($form);

        $this->assertResponseStatusCodeSame(422);
    }
}
