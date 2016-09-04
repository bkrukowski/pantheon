<?php

namespace AppBundle\Command;

use Symfony\Bridge\Doctrine\RegistryInterface as Doctrine;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question;

abstract class Base extends ContainerAwareCommand
{
    /**
     * @var InputInterface $input
     */
    protected $input;

    /**
     * @var OutputInterface $output
     */
    protected $output;

    protected function getDoctrine() : Doctrine
    {
        return $this->getContainer()->get('doctrine');
    }

    abstract protected function runCommand();

    final protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->input = $input;
        $this->output = $output;
        $this->runCommand();
    }

    protected function askForEmail()
    {
        $question = new Question('Put valid email: ');
        $question->setValidator(function ($email) {
            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                throw new \RuntimeException('Invalid email!');
            }

            return $email;
        });

        return $this->getHelper('question')->ask($this->input, $this->output, $question);
    }

    protected function askForPasswordWithValidator(callable $passwordValidator)
    {
        $question = new Question('Put password: ');
        $question->setHidden(true);
        $question->setHiddenFallback(false);
        $question->setValidator($passwordValidator);
        $password = $this->getHelper('question')->ask($this->input, $this->output, $question);

        $secondQuestion = new Question('Repeat password: ');
        $secondQuestion->setHidden(true);
        $secondQuestion->setHiddenFallback(false);
        $question->setValidator(function ($password2) use ($password) {
            if ($password !== $password2) {
                throw new \RuntimeException('Passwords are different!');
            }

            return $password2;
        });

        return $this->getHelper('question')->ask($this->input, $this->output, $secondQuestion);
    }

    protected function askForPassword()
    {
        return $this->askForPasswordWithValidator(function ($password) {
            $min = 3;
            if (strlen($password) < $min) {
                throw new \RuntimeException('Password cannot be shorter than 3 characters!');
            }

            return $password;
        });
    }
}