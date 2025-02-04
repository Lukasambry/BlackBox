<?php

namespace App\Tests;

use App\Security\Authenticator;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Http\Authenticator\Passport\Passport;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Session\Storage\MockArraySessionStorage;

class AuthenticatorTest extends TestCase
{
    public function testAuthenticate(): void
    {
        $urlGenerator = $this->createMock(UrlGeneratorInterface::class);
        $urlGenerator
            ->method('generate')
            ->with('app_login')
            ->willReturn('/login');

        $authenticator = new Authenticator($urlGenerator);

        $request = new Request([], [
            'email' => 'test@example.com',
            'password' => 'secret',
            '_csrf_token' => 'monCsrf'
        ]);

        $session = new Session(new MockArraySessionStorage());
        $request->setSession($session);

        $passport = $authenticator->authenticate($request);

        $this->assertInstanceOf(Passport::class, $passport);
        $this->assertEquals('test@example.com', $session->get('_security.last_username'));
    }
}
