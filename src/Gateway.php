<?php

namespace Travelrez\Rezb2b;

use Travelrez\Common\AbstractGateway;

class Gateway extends AbstractGateway
{

    public function getName()
    {
        return "Rezb2b";
    }

    public function getDefaultParameters()
    {
        return array(
            'apiKey'   => '',
            'testMode' => true,
        );
    }

    public function getApiKey()
    {
        return $this->getParameter('apiKey');
    }

    public function setApiKey($value)
    {
        return $this->setParameter('apiKey', $value);
    }

    public function getProduct(array $parameters = array())
    {

        return $this->createRequest('\Travelrez\Rezb2b\Process\GetProductRequest', $parameters);

    }

    public function getProductAvailability(array $parameters = array())
    {

        return $this->createRequest('\Travelrez\Rezb2b\Process\GetProductAvailabilityRequest', $parameters);

    }

    public function getProductPriceCalculation(array $parameters = array())
    {

        return $this->createRequest('\Travelrez\Rezb2b\Process\GetProductPriceCalculationRequest', $parameters);

    }

    public function getProductBookingRequest(array $parameters = array())
    {

        return $this->createRequest('\Travelrez\Rezb2b\Process\GetProductBookingRequest', $parameters);

    }

    
    
    public function createBooking(array $parameters = array())
    {
       return $this->createRequest('\Travelrez\Rezb2b\Process\CreateBookingRequest', $parameters);
    }

}
