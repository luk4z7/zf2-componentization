<?php

namespace Components\ServiceManager\Delegator;

use Components\EventManager\CreditCard;

class CreditCardDelegatorLazy extends CreditCard
{
    /**
     * CreditCardDelegatorLazy constructor.
     */
    public function __construct()
    {
        sleep(10);
    }

    /**
     * @return string
     */
    public function brand()
    {
        return parent::brand();
    }
}