<?php

require 'vendor/zendframework/zendframework/library/Zend/Loader/StandardAutoloader.php';
$loader = new Zend\Loader\StandardAutoloader([
    'autoregister_zf' => true
]);
$loader->registerNamespace('Components', 'src/Components');
$loader->registerNamespace('ProxyManager', 'vendor/ocramius/proxy-manager/src/ProxyManager');
$loader->register();




/**
 * Exemplos de uso do componente EventManager
 *
 * 1 - Short Circuiting
 * 2 - Aggregate Listeners
 * 3 - Shared Event Manager
 *
 */
echo "<h1>EventManager</h1><br/>";

$creditCard = new \Components\EventManager\CreditCard();
$creditCard->getEventManager()->attach('*', function($e) {
    if (!empty($e->getParam('flag')))
        echo $e->getParam('flag');
});
$creditCard->getCreditCardFlag(4444333322221111); // visa



/**  Short Circuiting */
$creditCard->brandSuported(4444333322221111);     // visa
$creditCard->brandSuported(5266736406590700);     // mastercard



/**  Aggregate Listeners */
$listenerAggregate = new \Components\EventManager\ListenerAggregate([
    ['processCreditCardOne', 'executeProcessCreditCardOne', 100],
    ['processCreditCardTwo', 'executeProcessCreditCardTwo', 100],
    ['processCreditCardThree', 'executeProcessCreditCardThree', -100],
]);
$creditCard->getEventManager()->attachAggregate($listenerAggregate);
$creditCard->processCreditCard(5266736406590700);



/**  Shared Event Manager */
$sharedEvent = new Zend\EventManager\SharedEventManager();
$sharedEvent->attach('Components\EventManager\CreditCard', 'getCreditCardFlag', function($e) {

    echo "<br/><br/>";
    echo "<strong>Name:</strong> ";
    echo $e->getName() . "<br/><br/>";

    echo "<strong>Target:</strong> ";
    echo get_class($e->getTarget());
    echo "<br/><br/>";

    echo "<strong>Bandeira:</strong> ";
    echo $e->getParam('flag');

});

$newCreditCard = new \Components\EventManager\CreditCard();
$newCreditCard->getEventManager()->setSharedManager($sharedEvent);
$newCreditCard->getCreditCardFlag(30083521152975);





/**
 * Exemplos de uso do componente ServiceManager
 *
 * 1  - Plugin Manager
 * 2  - Registrando serviços com setService
 * 3  - Registrando serviços com setInvokableClass
 * 4  - Factory com callback
 * 5  - Factory com classe
 * 6  - Abstract Factory
 * 7  - Alias
 * 8  - Peering Service Manager
 * 9  - Initializers
 * 10 - ServiceConfig
 * 11 - Delegator
 * 12 - Factory Delegator
 * 13 - Lazy Services
 *
 */
echo "<h1>ServiceManager</h1><br/>";

/** Plugin Manager */
$serviceManager = new Zend\ServiceManager\ServiceManager();
$config = [
    'factories' => [
        'Plugin' => 'Components\ServiceManager\PluginManager\Service\PluginFactory'
    ]
];

$serviceConfig = new \Zend\Mvc\Service\ServiceManagerConfig($config);
$serviceConfig->configureServiceManager($serviceManager);

$plugin = $serviceManager->get('Plugin');
$plugin->setInvokableClass('Boleto1', 'Components\ServiceManager\PluginManager\Plugins\Boleto1');
$plugin->setInvokableClass('Boleto2', 'Components\ServiceManager\PluginManager\Plugins\Boleto2');
$boleto = $plugin->get('Boleto1');
$boleto = $plugin->get('Boleto2');



echo "<br/><br/>";
/**  Registrando serviços com setService */
$serviceManager2 = new \Zend\ServiceManager\ServiceManager();
$serviceManager2->setService('CreditCardWithSetService', new Components\EventManager\CreditCard());
$servicoCreditCard = $serviceManager2->get('CreditCardWithSetService');
$servicoCreditCard2 = $serviceManager2->get('CreditCardWithSetService');
print_r( $servicoCreditCard );
var_dump( $servicoCreditCard === $servicoCreditCard2);



echo "<br/><br/>";
/** Registrando serviços com setInvokableClass */
$serviceManager2->setInvokableClass('CreditCardWithInvokableClass', '\Components\EventManager\CreditCard', false);
$serviceCreditCardWithInvokableClass = $serviceManager2->get('CreditCardWithInvokableClass');
$serviceCreditCardWithInvokableClass2 = $serviceManager2->get('CreditCardWithInvokableClass');
print_r($serviceCreditCardWithInvokableClass);
var_dump( $serviceCreditCardWithInvokableClass === $serviceCreditCardWithInvokableClass2);



echo "<br/><br/>";
/** Factory com callback */
$serviceManager2->setFactory('CreditCardFactory', function($sm) {
    return $sm->get('CreditCardWithInvokableClass');
});
$creditCardFactory = $serviceManager2->get('CreditCardFactory');
var_dump($creditCardFactory->brandSuported(5266736406590700));



echo "<br/><br/>";
/** Factory com classe */
$serviceManager2->setFactory('CreditCardAbstractFactory', 'Components\ServiceManager\Factories\CreditCardParametersFactory');
$serviceManager2->get('CreditCardAbstractFactory');



echo "<br/><br/>";
/** Abstract Factory */
$serviceManager2->addAbstractFactory('Components\ServiceManager\Factories\AbstractCreditCardFactory');
$abstractCreditCardFactory = $serviceManager2->get('BrandSupported-BRAND_VISA');
$abstractCreditCardFactory->getCreditCardFlag(4444333322221111);



echo "<br/><br/>";
/** Alias */
$serviceManager2->setFactory('CreditCardAbstractFactory2', 'Components\ServiceManager\Factories\CreditCardParametersFactory');
$serviceManager2->setAlias('FactoryWithAlias', 'CreditCardAbstractFactory2');
$serviceManager2->get('FactoryWithAlias');



echo "<br/><br/>";
/** Peering Service Manager */
$sm1 = new \Zend\ServiceManager\ServiceManager();
$sm1->setInvokableClass('CreditCardTest1', '\Components\EventManager\CreditCard');

$sm2 = $sm1->createScopedServiceManager(\Zend\ServiceManager\ServiceManager::SCOPE_PARENT);
print_r($sm2->get('CreditCardTest1'));

$sm3 = $sm1->createScopedServiceManager(\Zend\ServiceManager\ServiceManager::SCOPE_CHILD);
$sm3->setInvokableClass('CreditCardTest3', '\Components\EventManager\CreditCard');
print_r($sm1->get('CreditCardTest3'));
print_r($sm2->get('CreditCardTest3'));



echo "<br/><br/>";
/**  Initializers */
$serviceManager3 = new \Zend\ServiceManager\ServiceManager();
$serviceManager3->setInvokableClass('InitializerCreditCard', '\Components\EventManager\CreditCard');
$serviceManager3->addInitializer(function($instance, $serviceManager) {
    if ( $instance instanceof \Components\EventManager\CreditCard )
    {
        $instance->setTimezone(new \DateTimeZone('Europe/London'));
    }
});
$initializer = $serviceManager3->get('InitializerCreditCard');
print_r($initializer);



echo "<br/><br/>";
/** ServiceConfig */
$serviceManager4 = new \Zend\ServiceManager\ServiceManager();
$config = [
    'abstract_factories' => [
        'Components\ServiceManager\Factories\AbstractCreditCardFactory'
    ]
    ,
    'factories' => [
        'CreditCardFactory' => function ($sm) {
            return new \Components\EventManager\CreditCard();
        }
    ],
    'invokables' => [
        'CreditCardInvokable' => '\Components\EventManager\CreditCard'
    ],
    'shared' => [
        'CreditCardInvokable' => false
    ]
];

$serviceConfig = new Zend\Mvc\Service\ServiceManagerConfig( $config );
$serviceConfig->configureServiceManager( $serviceManager4 );

print_r( $serviceManager4->get('CreditCardFactory') );
echo "<br/><br/>";
print_r( $serviceManager4->get('CreditCardInvokable') );
echo "<br/><br/>";
print_r( $serviceManager4->get('BrandSupported-BRAND_AMEX') );



echo "<br/><br/>";
/** Delegator */
$wrapperCreditCard = new Components\EventManager\CreditCard();
$eventManager = new Zend\EventManager\EventManager();
$eventManager->attach('brand', function() {
    echo "return string first !\n";
});

$delegator = new Components\ServiceManager\Delegator\CreditCardDelegator($wrapperCreditCard, $eventManager);
echo $delegator->brand();



echo "<br/><br/>";
/** Factory Delegator */
$serviceManager = new Zend\ServiceManager\ServiceManager();
$serviceManager->setInvokableClass('brand', 'Components\EventManager\CreditCard');
$serviceManager->setInvokableClass('brand-delegator-factory', 'Components\ServiceManager\Factories\CreditCardDelegatorFactory');
$serviceManager->addDelegator('brand', 'brand-delegator-factory');
$delegator = $serviceManager->get('brand');
echo $delegator->brand();



echo "<br/><br/>";
/** Lazy Services */
$config = [
    'lazy_services' => [
        'class_map' => [
            'CreditCardDelegatorLazy' => 'Components\ServiceManager\Delegator\CreditCardDelegatorLazy'
        ],
    ],
];
$serviceManagerLazy = new Zend\ServiceManager\ServiceManager();
$serviceManagerLazy->setService('Config', $config);
$serviceManagerLazy->setInvokableClass('CreditCardDelegatorLazy', 'Components\ServiceManager\Delegator\CreditCardDelegatorLazy');
$serviceManagerLazy->setFactory('creditcard-delegator-factory-lazy', 'Zend\ServiceManager\Proxy\LazyServiceFactoryFactory');
$serviceManagerLazy->addDelegator('CreditCardDelegatorLazy', 'creditcard-delegator-factory-lazy');
$creditCardDelegatorLazy = $serviceManagerLazy->get('CreditCardDelegatorLazy');
echo $creditCardDelegatorLazy->brand();