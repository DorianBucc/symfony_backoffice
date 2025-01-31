<?php
namespace App\Security;

use App\Entity\Product;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\User\UserInterface;

class ProductVoter extends Voter
{
    const EDIT = 'edit';
    const DELETE = 'delete';

    protected function supports(string $attribute, $subject): bool
    {
        return in_array($attribute, [self::EDIT, self::DELETE], true)
            && $subject instanceof Product;
    }

    protected function voteOnAttribute(string $attribute, $subject, TokenInterface $token): bool
    {
        $user = $token->getUser();
        if (!$user instanceof UserInterface) {
            return false;
        }

        if (in_array('ROLE_ADMIN', $user->getRoles(), true)) {
            return true;
        }

        return false;
    }
}