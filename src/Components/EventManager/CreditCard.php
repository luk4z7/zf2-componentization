<?php

namespace Components\EventManager;

use Zend\EventManager\EventManager as Event;
use Zend\EventManager\EventManager;
use Zend\EventManager\EventManagerAwareInterface;
use Zend\EventManager\EventManagerInterface;

class CreditCard implements EventManagerAwareInterface
{
    /**
     * @var
     */
    protected $event;

    /**
     * @var
     */
    protected $timezone;

    const BRAND_VISA = 'visa';
    const BRAND_MASTERCARD = 'mastercard';
    const BRAND_DISCOVER = 'discover';
    const BRAND_AMEX = 'amex';
    const BRAND_DINERS_CLUB = 'diners_club';
    const BRAND_JCB = 'jcb';
    const BRAND_SWITCH = 'switch';
    const BRAND_SOLO = 'solo';
    const BRAND_DANKORT = 'dankort';
    const BRAND_MAESTRO = 'maestro';
    const BRAND_FORBRUGSFORENINGEN = 'forbrugsforeningen';
    const BRAND_LASER = 'laser';

    /**
     * @param EventManagerInterface $eventManager
     * @return $this
     */
    public function setEventManager(EventManagerInterface $eventManager)
    {
        $eventManager->setIdentifiers([
            __CLASS__,
            get_called_class()
        ]);
        $this->event = $eventManager;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getEventManager()
    {
        if (null == $this->event)
            $this->setEventManager(new Event());

        return $this->event;
    }

    /**
     * @param $number
     */
    public function getCreditCardFlag($number)
    {
        $this->getEventManager()->trigger(
            __FUNCTION__,
            $this,
            ['flag' => $this->getBrand($number)]
        );
    }

    /**
     * @param $number
     * @return mixed
     */
    public function brandSuported($number)
    {
        $arg = compact('number');
        $result = $this->getEventManager()->triggerUntil(
            __FUNCTION__,
            $this,
            $arg,
            function ($v) use ($number) {
                if ($this->getBrand($number) != self::BRAND_MASTERCARD) {
                    return true;
                }
            }
        );

        if ($result->stopped()) {
            echo " | Somente aceitamos mastercard | ";
            return $result->last();
        }

        echo " Processo de transa&ccedil;&atilde;o em andamento ... ";
    }

    /**
     * @param $number
     * @return int|string
     */
    public function getBrand($number)
    {
        foreach ($this->getSupportedBrands() as $brand => $val) {
            if (preg_match($val, $number)) {
                return $brand;
            }
        }
    }

    /**
     * @return array
     */
    public function getSupportedBrands()
    {
        return array(
            static::BRAND_VISA => '/^4\d{12}(\d{3})?$/',
            static::BRAND_MASTERCARD => '/^(5[1-5]\d{4}|677189)\d{10}$/',
            static::BRAND_DISCOVER => '/^(6011|65\d{2}|64[4-9]\d)\d{12}|(62\d{14})$/',
            static::BRAND_AMEX => '/^3[47]\d{13}$/',
            static::BRAND_DINERS_CLUB => '/^3(0[0-5]|[68]\d)\d{11}$/',
            static::BRAND_JCB => '/^35(28|29|[3-8]\d)\d{12}$/',
            static::BRAND_SWITCH => '/^6759\d{12}(\d{2,3})?$/',
            static::BRAND_SOLO => '/^6767\d{12}(\d{2,3})?$/',
            static::BRAND_DANKORT => '/^5019\d{12}$/',
            static::BRAND_MAESTRO => '/^(5[06-8]|6\d)\d{10,17}$/',
            static::BRAND_FORBRUGSFORENINGEN => '/^600722\d{10}$/',
            static::BRAND_LASER => '/^(6304|6706|6709|6771(?!89))\d{8}(\d{4}|\d{6,7})?$/',
        );
    }

    /**
     * @param $number
     */
    public function processCreditCard($number)
    {
        $this->getEventManager()->trigger(
            'processCreditCardOne',
            $this,
            ['number' => $number, 'status' => 'primeiro processamento'],
            function () {}
        );

        $this->getEventManager()->trigger(
            'processCreditCardTwo',
            $this,
            ['number' => $number, 'status' => 'segundo processamento'],
            function () {}
        );

        $this->getEventManager()->trigger(
            'processCreditCardThree',
            $this,
            ['number' => $number, 'status' => 'terceiro processamento'],
            function () {}
        );
    }

    /**
     * @param array $array
     * @return mixed
     */
    public function exportParameters($array = array())
    {
        return var_export($array);
    }

    /**
     * @param \DateTimeZone $timezone
     * @return $this
     */
    public function setTimezone(\DateTimeZone $timezone)
    {
        $this->timezone = $timezone;
        return $this;
    }

    /**
     * @return string
     */
    public function brand()
    {
        return self::BRAND_DINERS_CLUB;
    }
}