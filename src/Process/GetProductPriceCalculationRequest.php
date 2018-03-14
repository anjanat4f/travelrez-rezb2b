<?php

namespace Travelrez\Rezb2b\Process;

/**
 * Rezb2b get product request
 */
class GetProductPriceCalculationRequest extends AbstractRequest
{

    const API_VERSION = 'v1';

    protected $liveEndpoint = 'http://product.services.rezb2b.com';
    //protected $testEndpoint = 'http://product.services.qa.rezb2b.com';
    protected $testEndpoint = 'http://nayan-master.product.service';

    public function getBaseEndpoint()
    {
        return $this->getTestMode() ? $this->testEndpoint . "/" . self::API_VERSION : $this->liveEndpoint . "/" . self::API_VERSION;
    }

    public function getEndPoint()
    {
        return $this->getBaseEndpoint() . "/price";
    }


    public function getRates()
    {
        return $this->getParameter("rates");
    }

    public function setRates($value)
    {
        return $this->setParameter("rates", $value);
    }

    public function getOptions()
    {
        return $this->getParameter("options");
    }

    public function setOptions($value)
    {
        return $this->setParameter("options", $value);
    }

    public function getDepartureStartDate()
    {
        return $this->getParameter("departure_start_date");
    }

    public function setDepartureStartDate($value)
    {
        return $this->setParameter("departure_start_date", $value);
    }

    public function getData()
    {

        $data = $this->getParameters();

        return $data;

    }

    public function sendData($data)
    {

        $response = array();

        if (is_array($data)) {
            unset($data["apiKey"]);
        }

        $uri = $this->getEndpoint();

        $rates = array();

        if(!empty($data["rates"])) {
            foreach($data["rates"] as $r =>  $rate) {
                foreach($rate as $rat) {
                    $rates[$r][$rat["rate_id"]]  =    $rat["qty"];
                }    
            }
        }



        $options =  $this->getOptions();

        
        $params = array(
            "departure_date" => $this->getDepartureStartDate(),
            "rate_options"   => $rates,
            "upgrade" => $options
        );

        $httpResponse = $this->httpClient->send("POST", $uri . "/" . $data["product_code"] . "/price-calculate", ["api-key" => $this->getApiKey()], $params);


        return $this->createResponse($httpResponse->getBody());

    }

    protected function createResponse($data)
    {

        return $this->response = new GetProductPriceCalculationResponse($this, $data);
    }

}
