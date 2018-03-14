<?php

namespace Travelrez\Rezb2b\Process;

/**
 * Rezb2b get product request
 */
class GetProductBookingRequest extends AbstractRequest
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

    public function getDepartureStartDate()
    {
        return $this->getParameter("departure_start_date");
    }

    public function setDepartureStartDate($value)
    {
        return $this->setParameter("departure_start_date", $value);
    }

    public function getRates()
    {
        return $this->getParameter("rates");
    }

    public function setRates($value)
    {
        return $this->setParameter("rates", $value);
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

        $params = array(
            "departure_start_date" => $data["departure_start_date"],
            "departure_end_date" => $data["departure_start_date"],
            //"used_in"        => "order",
 
        );

        $query = http_build_query($params);

        $httpResponse = $this->httpClient->send("GET", $uri . "/" . $data["product_code"] . "/availability?".$query, ["api-key" => "00c521f8bd816035af483df1f"/*$this->getApiKey()*/], $params);

        $response = $httpResponse->getBody();

        return $this->createResponse($httpResponse->getBody());

    }

    protected function createResponse($data)
    {

        return $this->response = new GetProductBookingResponse($this, $data);
    }

}
