<?php

namespace AppBundle\Composer;

use AppBundle\Command\BuildTimestampCommand;
use AppBundle\Command\StaticFilesCommand;
use Sensio\Bundle\DistributionBundle\Composer\ScriptHandler as Base;
use Composer\Script\Event;

class ScriptHandler extends Base
{
    public static function gulpInstall(Event $event)
    {
        $event->getIO()->write('Build static files');
        $options = static::getOptions($event);
        $consoleDir = static::getConsoleDir($event, 'build static files');
        static::executeCommand($event, $consoleDir, StaticFilesCommand::CMD_NAME, $options['process-timeout']);
    }

    public static function createTimestampFile(Event $event)
    {
        $event->getIO()->write('Create timestamp');
        $options = static::getOptions($event);
        $consoleDir = static::getConsoleDir($event, 'create timestamp');
        static::executeCommand($event, $consoleDir, BuildTimestampCommand::CMD_NAME, $options['process-timeout']);
    }
}