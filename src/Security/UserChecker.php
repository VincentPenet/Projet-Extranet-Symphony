<?php

namespace App\Security;

use App\Entity\Users;
use Symfony\Component\Security\Core\Exception\CustomUserMessageAuthenticationException;
use Symfony\Component\Security\Core\User\UserCheckerInterface;
use Symfony\Component\Security\Core\User\UserInterface;

class UserChecker implements UserCheckerInterface
{
    public function checkPreAuth(UserInterface $user)
    {
        if (!$user instanceof Users) {
            return;
        }

        if (!$user->getActivated()) {
            // the message passed to this exception is meant to be displayed to the user
            throw new CustomUserMessageAuthenticationException('Votre compte n\'est pas encore activé.');
        }

    }

    public function checkPostAuth(UserInterface $user)
    {
        $this->checkPreAuth($user);

    }
}