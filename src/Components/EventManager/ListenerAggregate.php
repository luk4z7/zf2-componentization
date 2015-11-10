<?php

namespace Components\EventManager;

use Zend\EventManager\EventManagerInterface;
use Zend\EventManager\ListenerAggregateInterface;

class ListenerAggregate implements ListenerAggregateInterface
{
    protected $listeners = [];
    protected $params;

    public function __construct($params)
    {
        $this->params = $params;
    }

    /**
     * @param EventManagerInterface $events
     */
    public function attach(EventManagerInterface $events)
    {
        $array = new \ArrayIterator($this->params);
        while($array->valid())
        {
            $this->listeners[] = $events->attach( $array->current()[0], [$this, $array->current()[1]], $array->current()[2] );
            $array->next();
        }
    }

    /**
     * @param EventManagerInterface $events
     */
    public function detach(EventManagerInterface $events)
    {
        foreach ( $this->listeners as $key => $listener)
        {
            if ( $events->detach($listener) )
                unset($this->listeners[$key]);
        }
    }

    /**
     * Process One
     */
    public function executeProcessCreditCardOne()
    {
        echo " | Execu&ccedil;&atilde;o do primeiro processamento de cart&atilde;o de cr&eacute;dito | ";
    }

    /**
     * Process Two
     */
    public function executeProcessCreditCardTwo()
    {
        echo " Execu&ccedil;&atilde;o do segundo processamento de cart&atilde;o de cr&eacute;dito | ";
    }

    /**
     * Process Three
     */
    public function executeProcessCreditCardThree()
    {
        echo " Execu&ccedil;&atilde;o do terceiro processamento de cart&atilde;o de cr&eacute;dito | ";
    }
}