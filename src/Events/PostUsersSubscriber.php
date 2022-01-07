<?php

namespace App\Events;

use ApiPlatform\Core\EventListener\EventPriorities;
use App\Entity\User;
use JetBrains\PhpStorm\ArrayShape;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Event\ViewEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Security\Core\Security;

class PostUsersSubscriber implements EventSubscriberInterface
{
    public function __construct(
        private Security $security,
        public UserPasswordHasherInterface $passwordHasher
    ) {
    }

    #[ArrayShape([KernelEvents::VIEW => 'array[]'])]
    public static function getSubscribedEvents(): array
    {
        return [
            KernelEvents::VIEW => [
                ['currentClientForUsers', EventPriorities::POST_VALIDATE],
                ['hashPassword', EventPriorities::POST_VALIDATE],
            ],
        ];
    }

    public function currentClientForUsers(ViewEvent $viewEvent): void
    {
        $user = $viewEvent->getControllerResult();
        $method = $viewEvent->getRequest()->getMethod();

        if ($user instanceof User && Request::METHOD_POST === $method) {
            $user->setClient($this->security->getUser());
        }
    }

    public function hashPassword(ViewEvent $viewEvent): void
    {
        $user = $viewEvent->getControllerResult();
        $method = $viewEvent->getRequest()->getMethod();

        if ($user instanceof User && Request::METHOD_POST === $method) {
            $user->setPassword($this->passwordHasher->hashPassword($user, $user->getPassword()));
        }
    }
}
