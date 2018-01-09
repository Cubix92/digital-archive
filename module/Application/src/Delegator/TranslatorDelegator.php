<?php

namespace Application\Delegator;

use Interop\Container\ContainerInterface;
use Zend\I18n\Translator\Loader\PhpArray;
use Zend\Mvc\I18n\Translator;
use Zend\ServiceManager\Factory\DelegatorFactoryInterface;

class TranslatorDelegator implements DelegatorFactoryInterface
{
    public function __invoke(ContainerInterface $container, $name, callable $callback, array $options = null)
    {
        /**
         * @var Translator $translator
         */
        $translator = $callback();

        $translator->addTranslationFile(
            PhpArray::class,
            __DIR__ . '/../../../../vendor/zendframework/zend-i18n-resources/languages/pl/Zend_Validate.php'
        );

        $translator->addTranslationFile(PhpArray::class, './data/languages/pl_PL.php');

        return $translator;
    }
}
