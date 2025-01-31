<?php

namespace App\EventSubscriber;

use App\Entity\UserLog;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Security\Http\Event\LoginSuccessEvent;
use Symfony\Component\Security\Http\Event\LogoutEvent;

class UserLogSubscriber implements EventSubscriberInterface
{
    private EntityManagerInterface $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    public static function getSubscribedEvents(): array
    {
        return [
            LoginSuccessEvent::class => 'onLoginSuccess',
            LogoutEvent::class       => 'onLogout',
        ];
    }

    public function onLoginSuccess(LoginSuccessEvent $event): void
    {
        $user = $event->getAuthenticatedToken()->getUser();
        if (!$user) {
            return;
        }

        $log = new UserLog();
        $log->setUser($user);
        $log->setAction('login');
        $log->setCreatedAt(new \DateTimeImmutable());
        $log->setUpdatedAt(new \DateTimeImmutable());

        $this->em->persist($log);
        $this->em->flush();
    }

    public function onLogout(LogoutEvent $event): void
    {
        $token = $event->getToken();
        if (!$token) {
            return;
        }

        $user = $token->getUser();
        if (!$user) {
            return;
        }

        $log = new UserLog();
        $log->setUser($user);
        $log->setAction('logout');
        $log->setCreatedAt(new \DateTimeImmutable());
        $log->setUpdatedAt(new \DateTimeImmutable());

        $this->em->persist($log);
        $this->em->flush();
    }
}
