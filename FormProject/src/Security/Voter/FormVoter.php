<?php

namespace App\Security\Voter;

use App\Entity\Form;
use App\Entity\Section;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
class FormVoter extends Voter
{
    protected function supports($attribute, $subject)
    {
        // replace with your own logic
        // https://symfony.com/doc/current/security/voters.html
        return in_array($attribute, ['EDIT','DELETE','UPDATE_ELEMENTS','CREATE_DATA'])
            && $subject instanceof Form;
    }

    protected function voteOnAttribute($attribute, $form, TokenInterface $token)
    {
        /*$user = $token->getUser();
        // if the user is anonymous, do not grant access
        if (!$user instanceof UserInterface) {
            return false;
        }*/

        // ... (check conditions and return true to grant permission) ...
        switch ($attribute) {
            case 'EDIT':
                return $form->getStatus() != '1';
                break;
            case 'DELETE':
                return $form->getStatus() != '1';
                break;
            case 'UPDATE_ELEMENTS':
                return $form->getStatus() != '1';
                break;
            case 'CREATE_DATA':
                return $form->getStatus() == '1';
                break;

        }

        return false;
    }
}
