<?php

namespace AppBundle\Command;

use AppBundle\Entity\User;

class EditUserCommand extends Base
{
    protected function runCommand()
    {
        $email = $this->askForEmail();
        $user = $this->getUserByEmail($email);
        $password = $this->askForPassword();
        $this->updateUser($user, $password);
    }

    protected function configure()
    {
        $this
            ->setName('app:edit-user')
            ->setDescription('Edit existing users.')
            ->setHelp('This command allows you to edit existing users.');
    }

    private function updateUser(User $user, string $password)
    {
        $encoder = $this->getContainer()->get('security.password_encoder');
        $encoded = $encoder->encodePassword($user, $password);
        $user->setPassword($encoded);

        $em = $this->getDoctrine()->getEntityManager();
        $em->persist($user);
        $em->flush();
    }

    private function getUserByEmail(string $email) : User
    {
        $repository = $this->getDoctrine()->getRepository('AppBundle:User');
        if ($user = $repository->findOneByEmail($email)) {
            return $user;
        }

        throw new \RuntimeException("There is no user with email {$email}!");
    }
}