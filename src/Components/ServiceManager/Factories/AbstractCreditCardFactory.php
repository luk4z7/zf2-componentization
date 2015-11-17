<?php

namespace Components\ServiceManager\Factories;

use Components\EventManager\CreditCard;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\ServiceManager\AbstractFactoryInterface;

class AbstractCreditCardFactory implements AbstractFactoryInterface
{
    public function canCreateServiceWithName(ServiceLocatorInterface $serviceLocator, $name, $requestedName)
    {
        $brand = explode('-', $requestedName);
        $reflaction = new \ReflectionClass('\Components\EventManager\CreditCard');

        if ( array_key_exists($brand[1], $reflaction->getConstants()) )
            return true;
    }

    /**
     * @param ServiceLocatorInterface $serviceLocator
     * @param $name
     * @param $requestedName
     * @return CreditCard
     */
    public function createServiceWithName(ServiceLocatorInterface $serviceLocator, $name, $requestedName)
    {
        return new CreditCard();
    }
}