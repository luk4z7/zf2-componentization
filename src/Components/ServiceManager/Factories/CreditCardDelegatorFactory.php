<?php

namespace Components\ServiceManager\Factories;

use Zend\ServiceManager\DelegatorFactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\EventManager\EventManager;
use Components\ServiceManager\Delegator\CreditCardDelegator;

class CreditCardDelegatorFactory implements DelegatorFactoryInterface
{
    /**
     * @param ServiceLocatorInterface $serviceLocator
     * @param string $name
     * @param string $requestedName
     * @param callable $callback
     * @return CreditCardDelegator
     */
    public function createDelegatorWithName(ServiceLocatorInterface $serviceLocator, $name, $requestedName, $callback)
    {
        $realBrand = call_user_func($callback);
        $eventManager = new EventManager();
        $eventManager->attach('brand', function () {
            echo "return string first, now with factory! \n ";
        });
        return new CreditCardDelegator($realBrand, $eventManager);
    }
}