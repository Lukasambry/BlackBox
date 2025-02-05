<?php

namespace App\Security\Voter;

use App\Entity\Room;
use App\Entity\User;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\User\UserInterface;

class RoomVoter extends Voter
{
    public const VIEW = 'ROOM_VIEW';
    public const CREATE = 'ROOM_CREATE';
    public const EDIT = 'ROOM_EDIT';
    public const DELETE = 'ROOM_DELETE';
    public const JOIN = 'ROOM_JOIN';
    public const START = 'ROOM_START';
    public const PLAY = 'ROOM_PLAY';

    public function __construct(
        private Security $security
    ) {
    }

    protected function supports(string $attribute, mixed $subject): bool
    {
        return in_array($attribute, [
                self::VIEW,
                self::CREATE,
                self::EDIT,
                self::DELETE,
                self::JOIN,
                self::START,
                self::PLAY
            ]) && ($subject instanceof Room || $subject === null);
    }

    protected function voteOnAttribute(string $attribute, mixed $subject, TokenInterface $token): bool
    {
        $user = $token->getUser();

        if (!$user instanceof UserInterface) {
            return false;
        }

        if ($this->security->isGranted('ROLE_ADMIN')) {
            return true;
        }

        /** @var Room $room */
        $room = $subject;

        return match ($attribute) {
            self::VIEW => $this->canView($room, $user),
            self::CREATE => $this->canCreate($user),
            self::EDIT => $this->canEdit($room, $user),
            self::DELETE => $this->canDelete($room, $user),
            self::JOIN => $this->canJoin($room, $user),
            self::START => $this->canStart($room, $user),
            self::PLAY => $this->canPlay($room, $user),
            default => false
        };
    }

    private function canView(Room $room, UserInterface $user): bool
    {
        if (!$room->isPrivate()) {
            return true;
        }

        return $room->isOwner($user) || $room->getPlayers()->contains($user);
    }

    private function canCreate(UserInterface $user): bool
    {
        return true;
    }

    private function canEdit(Room $room, UserInterface $user): bool
    {
        return $room->isOwner($user);
    }

    private function canDelete(Room $room, UserInterface $user): bool
    {
        return $room->isOwner($user);
    }

    private function canJoin(Room $room, UserInterface $user): bool
    {
        if (
            $room->isStarted() ||
            $room->getPlayers()->count() >= $room->getMaxCapacity() ||
            $room->getPlayers()->contains($user) ||
            !$room->isActive()
        ) {
            return false;
        }

        if ($room->isPrivate()) {
            return true;
        }

        return true;
    }

    private function canStart(Room $room, UserInterface $user): bool
    {
        return $room->isOwner($user)
            && !$room->isStarted()
            && $room->getPlayers()->count() >= 2;
    }

    private function canPlay(Room $room, UserInterface $user): bool
    {
        return $room->getPlayers()->contains($user)
            && $room->isStarted()
            && $room->isActive();
    }
}
