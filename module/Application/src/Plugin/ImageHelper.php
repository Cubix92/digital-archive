<?php

namespace Application\Plugin;

use Zend\View\Helper\AbstractHelper;

class ImageHelper extends AbstractHelper
{
    public function __invoke($imagePath)
    {
        $absolutePath = trim($imagePath, '.');
        $publicPath = str_replace('public', '', $absolutePath);

        return $publicPath;
    }
}