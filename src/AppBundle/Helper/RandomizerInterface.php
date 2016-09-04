<?php

namespace AppBundle\Helper;

interface RandomizerInterface
{
    const DEFAULT_LENGTH = 30;
    const DEFAULT_CHARACTERS = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';

    public function randomString(int $length = self::DEFAULT_LENGTH, $characters = self::DEFAULT_CHARACTERS) : string;
}