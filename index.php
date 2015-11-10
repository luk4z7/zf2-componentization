<?php

require 'vendor/zendframework/zendframework/library/Zend/Loader/StandardAutoloader.php';
$loader = new Zend\Loader\StandardAutoloader([
    'autoregister_zf' => true
]);
$loader->registerNamespace('Components', 'src/Components');
$loader->register();

$creditCard = new \Components\EventManager\CreditCard();
$creditCard->getEventManager()->attach('*', function($e) {
    if (!empty($e->getParam('flag')))
        echo $e->getParam('flag');
});
$creditCard->getCreditCardFlag(4444333322221111); // visa

// Short Circuiting
$creditCard->brandSuported(4444333322221111);     // visa
$creditCard->brandSuported(5266736406590700);     // mastercard

// Aggregate Listeners
$listenerAggregate = new \Components\EventManager\ListenerAggregate([
    ['processCreditCardOne', 'executeProcessCreditCardOne', 100],
    ['processCreditCardTwo', 'executeProcessCreditCardTwo', 100],
    ['processCreditCardThree', 'executeProcessCreditCardThree', -100],
]);
$creditCard->getEventManager()->attachAggregate($listenerAggregate);
$creditCard->processCreditCard(5266736406590700);

// Shared Event Manager
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