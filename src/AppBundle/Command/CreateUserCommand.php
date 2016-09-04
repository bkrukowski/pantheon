<?php

namespace AppBundle\Command;

use AppBundle\Entity\User;

class CreateUserCommand extends Base
{
    protected function runCommand()
    {
        $email = $this->askForEmail();
        $this->checkEmail($email);
        $password = $this->askForPassword();
        $this->addUser($email, $password);
        $this->output->writeln("User <info>{$email}</info> has been added successfully.");
    }

    private function checkEmail(string $email)
    {
        $repository = $this->getDoctrine()->getRepository('AppBundle:User');
        if ($repository->findByEmail($email)) {
            throw new \RuntimeException("User [{$email}] already exists!");
        }
    }

    private function addUser(string $email, string $password)
    {
        $user = new User();
        $encoder = $this->getContainer()->get('security.password_encoder');
        $encoded = $encoder->encodePassword($user, $password);

        $user->setEmail($email);
        $user->setPassword($encoded);
        $em = $this->getDoctrine()->getManager();
        $em->persist($user);
        $em->flush();
    }

    protected function configure()
    {
        $this
            ->setName('app:create-user')
            ->setDescription('Creates new users.')
            ->setHelp('This command allows you to create new users...');
    }
}