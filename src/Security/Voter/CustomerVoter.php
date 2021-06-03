<?php


namespace App\Security\Voter;


use App\Entity\Customer;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\User\UserInterface;

class CustomerVoter extends Voter
{
    protected function supports(string $attribute, $subject)
    {

        if($subject && !$subject instanceof Customer) {
            return false;
        }

        $policies = [
            'CAN_REMOVE',
            'CAN_EDIT',
            'CAN_LIST_CUSTOMERS',
            'CAN_LIST_ALL_CUSTOMERS',
            'CAN_CREATE_CUSTOMER'
        ];

        return in_array($attribute, $policies);
    }

    protected function voteOnAttribute(string $attribute, $subject, TokenInterface $token)
    {
        $user = $token->getUser();

        if (!$user instanceof UserInterface) {
            return false;
        }

        if (in_array('ROLE_ADMIN',  $user->getRoles())){
            return true;
        }

        if ($attribute === 'CAN_LIST_ALL_CUSTOMERS') {
            return in_array('ROLE_MODERATOR', $user->getRoles());
        }

        if ($attribute === 'CAN_CREATE_CUSTOMER') {
            return !in_array('ROLE_MODERATOR', $user->getRoles());
        }

        if ($attribute === 'CAN_EDIT') {
            return $subject->user === $user;
        }

        if ($attribute === 'CAN_REMOVE') {
            return in_array('ROLE_MODERATOR', $user->getRoles()) || $subject->user === $user;
        }

        return true;
    }



}






        //si tu es connecté, et que tu veux modifier/supprimer un customer
        /*
        if (($attribute === 'CAN_REMOVE' || $attribute === 'CAN_EDIT') && $subject->user !== $user) {
            //si le customer ne t'appartient pas, tu dégages 
            return false;
        }
            */
  