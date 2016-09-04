<?php

namespace AppBundle\Helper;

class Randomizer implements RandomizerInterface
{
    public function randomString(int $length = self::DEFAULT_LENGTH, $characters = self::DEFAULT_CHARACTERS) : string
    {
        $result = '';
        for ($i = 0; $i < $length; $i++) {
            $result .= $characters[rand(0, strlen($characters) - 1)];
        }

        return $result;
    }
}