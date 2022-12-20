<?php

namespace App\Security\Voter;

use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\User\UserInterface;

class ArticleVoter extends Voter
{
    protected function supports(string $attribute, $subject): bool
    {
        // Vérifie que Edit et delete est en correspondance dans l'action edit et delete et que le sujet est une instance de article
        return in_array($attribute, ['edit', 'delete'])
            && $subject instanceof \App\Entity\Articles;
    }

    protected function voteOnAttribute(string $attribute, $subject, TokenInterface $token): bool
    {
        $user = $token->getUser();
        // Si l'utilisateur est anonyme, on ne lui donne pas d'accès (connexion obligatoire)
        if (!$user instanceof UserInterface) {
            return false;
        }

        // Si on est un admin, on a tous les droits
        if (in_array('ROLE_ADMIN', $user->getRoles())) {
            return true;
        }


        // En tant que User, on vérifie que les conditions suivantes sont respectées :
        // User à l'origine de la création de l'article
        // Dans ce cas on lui donne la permission de modifier et/ou de supprimer.
        switch ($attribute) {

            // On autorise l'User créateur de l'article à le modifier
            case 'edit':
                return $subject->getUser() === $user;
                break;

            // On autorise l'User créateur à le supprimer
            case 'delete':
                return $subject->getUser() === $user;
                break;
        }

        return false;
    }
}