<?php

namespace AppBundle;

use AppBundle\Cache\GalleryCache;
use AppBundle\Helper\RandomizerInterface;

trait AppContext
{
    protected function getRandomizer() : RandomizerInterface
    {
        return $this->container->get('app.randomizer');
    }

    protected function getGalleryCache() : GalleryCache
    {
        return $this->container->get('app.gallery_cache');
    }
}