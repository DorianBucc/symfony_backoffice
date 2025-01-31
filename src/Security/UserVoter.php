<?php

namespace App\Security;

use App\Entity\User;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\User\UserInterface;

class UserVoter extends Voter
{
    const EDIT = 'edit';
    const DELETE = 'delete';
    const MANAGE = 'manage_users';

    protected function supports(string $attribute, $subject): bool
    {
        return $attribute === self::MANAGE;
    }

    protected function voteOnAttribute(string $attribute, $subject, TokenInterface $token): bool
    {
        $user = $token->getUser();

        if (!$user instanceof UserInterface) {
            return false;
        }

        return in_array('ROLE_ADMIN', $user->getRoles(), true);
    }
}
