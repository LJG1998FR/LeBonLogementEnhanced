<?php

namespace App\Security\Voter;

use App\Entity\Bien;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\User\UserInterface;

class BienVoter extends Voter
{
    protected function supports(string $attribute, $subject): bool
    {
        // replace with your own logic
        // https://symfony.com/doc/current/security/voters.html
        return in_array($attribute, ['EDIT', 'DELETE'])
            && $subject instanceof Bien;
    }

    protected function voteOnAttribute(string $attribute, $bien, TokenInterface $token): bool
    {
        $user = $token->getUser();
        // if the user is anonymous, do not grant access
        if (!$user instanceof UserInterface) {
            return false;
        }

        // ... (check conditions and return true to grant permission) ...
        switch ($attribute) {
            case 'EDIT':
                if ($bien->getProprietaire() == $user){
                    return true;
                }
                break;
            case 'DELETE':
                if ($bien->getProprietaire() == $user){
                    return true;
                }
                break;
        }

        return false;
    }
}
