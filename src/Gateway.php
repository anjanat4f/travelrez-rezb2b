<?php

namespace Travelrez\Rezb2b;

if ($_SERVER['REMOTE_ADDR'] == '127.0.0.1') {
    define("ORDER_SERVICE_URL","http://order.services.rezb2b.com");
    define("ORDER_SERVICE_URL_SANDBOX","http://nayan-master.order.service");

    define("PRODUCT_SERVICE_URL","http://product.services.rezb2b.com");
    define("PRODUCT_SERVICE_URL_SANDBOX","http://product.services.anjana");
} else {
    define("ORDER_SERVICE_URL","http://order.services.rezb2b.com");
    define("ORDER_SERVICE_URL_SANDBOX","http://order.services.qa.rezb2b.com");
    
    define("PRODUCT_SERVICE_URL","http://product.services.rezb2b.com");
    define("PRODUCT_SERVICE_URL_SANDBOX","http://product.services.qa.rezb2b.com");
}
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
