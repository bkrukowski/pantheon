<?php

namespace AppBundle\Cache;

class GalleryCache
{
    private $cacheDir;

    private $buildTime;

    public function __construct(string $cacheDir)
    {
        $this->cacheDir = $cacheDir;
    }

    public function getBuildTimestamp() : int
    {
        if (is_null($this->buildTime)) {
            $this->buildTime = require $this->getBuildPath();
        }

        return $this->buildTime;
    }

    public function setBuildTimestamp()
    {
        $path = $this->getBuildPath();
        if (file_exists($path)) {
            throw new \RuntimeException('Build timestamp is already defined!');
        }
        $value = time();
        file_put_contents($this->getBuildPath(), "<?php return {$value};");
    }

    private function getBuildPath() : string
    {
        return $this->cacheDir . DIRECTORY_SEPARATOR . 'appBundle_buildTime.php';
    }
}