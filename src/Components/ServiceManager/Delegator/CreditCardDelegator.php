<?php

namespace Components\ServiceManager\Delegator;

use Zend\EventManager\EventManagerInterface;
use Components\EventManager\CreditCard;

class CreditCardDelegator extends CreditCard
{
    /**
     * @var
     */
    protected $realBrand;

    /**
     * @var
     */
    protected $eventManager;

    /**
     * CreditCardDelegator constructor.
     * @param CreditCard $realBrand
     * @param EventManagerInterface $eventManager
     */
    public function __construct(CreditCard $realBrand, EventManagerInterface $eventManager)
    {
        $this->realBrand = $realBrand;
        $this->eventManager = $eventManager;
    }

    /**
     * @return string
     */
    public function brand()
    {
        $this->eventManager->trigger('brand', $this);
        return $this->realBrand->brand();
    }
}