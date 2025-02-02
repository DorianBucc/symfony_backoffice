<?php
namespace App\Security;

use App\Entity\Product;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\User\UserInterface;

class ProductVoter extends Voter
{
    const VIEW = 'view';
    const EDIT = 'edit';
    const DELETE = 'delete';
    const ADD = 'add';

    protected function supports(string $attribute, $subject): bool
    {
        if(in_array($attribute, [self::VIEW, self::ADD], true))
        {
            return true;
        }
        return in_array($attribute, [self::EDIT, self::DELETE], true) && $subject instanceof Product;
    }

    protected function voteOnAttribute(string $attribute, $subject, TokenInterface $token): bool
    {
        $user = $token->getUser();

        if (!$user instanceof UserInterface) {
            return false;
        }

        switch ($attribute) {
            case self::ADD:
                return in_array('ROLE_ADMIN', $user->getRoles(), true);
            case self::EDIT:
                return in_array('ROLE_ADMIN', $user->getRoles(), true);
            case self::DELETE:
                return in_array('ROLE_ADMIN', $user->getRoles(), true);
            case self::VIEW:
                return in_array('ROLE_USER', $user->getRoles(), true);
        }
        return false;
    }
}