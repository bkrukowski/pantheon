<?php

namespace AppBundle\Command;

use Symfony\Component\Process\Process;
use Composer\Semver\Semver;

class StaticFilesCommand extends Base
{
    const CMD_NAME = 'deploy:static-files';

    protected function runCommand()
    {
        $this->checkProgram('Node', 'node -v', '^6.4.0');
        $this->checkProgram('NPM', 'npm -v', '^3.10.3');
        $publicDir = $this->getPublicDir();
        $this->runProcess('npm install', $publicDir);
        $this->runProcess('npm run gulp build-prod', $publicDir);
    }

    protected function configure()
    {
        $this
            ->setName(static::CMD_NAME)
            ->setDescription('Build static files.')
            ->setHelp('Build static files.');
    }

    private function runProcess(string $command, string $cwd)
    {
        (new Process($command, $cwd))->run(function ($type, $buffer) {
            if (Process::ERR === $type) {
                $buffer = "<error>{$buffer}</error>";
            }
            $this->output->write($buffer);
        });
    }

    private function checkProgram(string $name, string $command, string $requiredVersion)
    {
        $process = new Process($command);
        $process->run();
        if (!$process->isSuccessful()) {
            throw new \RuntimeException("{$name} is required!");
        }
        $version = trim($process->getOutput());
        $this->output->writeln("{$name} version <info>{$version}</info>");
        if (!Semver::satisfies($version, $requiredVersion)) {
            throw new \RuntimeException("Version {$version} does not match to pattern {$requiredVersion}!");
        }
    }

    private function getPublicDir() : string
    {
        return $this->getContainer()->get('kernel')->locateResource('@AppBundle/Resources/public');
    }
}