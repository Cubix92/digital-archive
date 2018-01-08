<?php

namespace Application\Service;

class Slugger
{
    public function transform(string $value):string
    {
        $trimmedValue   = trim($value);
        $lowerValue     = strtolower($trimmedValue);
        $filteredValue  = str_replace(' ', '-', $lowerValue);
        $slug           = str_replace('-', '_', $filteredValue);

        return $slug;
    }
}