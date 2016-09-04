<?php

namespace AppBundle\Command;

use AppBundle\Cache\GalleryCache;

class BuildTimestampCommand extends Base
{
    const CMD_NAME = 'deploy:create-timestamp';

    protected function runCommand()
    {
        /** @var GalleryCache $galleryCache */
        $galleryCache = $this->getContainer()->get('app.gallery_cache');
        $galleryCache->setBuildTimestamp();
        $this->output->writeln("Timestamp: <info>{$galleryCache->getBuildTimestamp()}</info>");
    }

    protected function configure()
    {
        $this
            ->setName(static::CMD_NAME)
            ->setDescription('Creates timestamp file')
            ->setHelp('Creates timestamp file');
    }
}