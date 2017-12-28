<?php

namespace Application\Service;

class Slugger
{
    public function transform(string $value):string
    {
        $filteredValue = str_replace(' ', '-', trim($value));

        return $filteredValue;
    }
}