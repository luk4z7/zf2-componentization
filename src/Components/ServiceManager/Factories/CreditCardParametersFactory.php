<?php

namespace Components\ServiceManager\Factories;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class CreditCardParametersFactory implements FactoryInterface
{
    /**
     * @param ServiceLocatorInterface $serviceLocator
     * @return mixed
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $card = $serviceLocator->get('CreditCardWithInvokableClass');
        $card->exportParameters(array(
            'firstName' => 'Bobby',
            'lastName' => 'Tables',
            'number' => '4444333322221111',
            'cvv' => '123',
            'expiryMonth' => '12',
            'expiryYear' => '2017',
            'email' => 'testcard@gmail.com'
        ));
        return $card;
    }
}