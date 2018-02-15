<?php

namespace Travelrez\Rezb2b\Process;

/**
 * Rezb2b get product request
 */
class GetProductAvailabilityRequest extends AbstractRequest
{

    const API_VERSION = 'v1';

    protected $liveEndpoint = 'http://product.services.rezb2b.com';
    protected $testEndpoint = 'http://product.services.qa.rezb2b.com';


    public function getData()
    {

        $this->validate('apiKey', 'product_code', 'start_date', 'end_date');

        $data = $this->getBaseData();

        $data["product_id"] = $this->getProductCode();

        $data["departure_start_date"] = date("Y-m-d", strtotime($this->getStartDate()));

        $data["departure_end_date"] = date("Y-m-d", strtotime($this->getEndDate()));

        return $data;
    }

    public function getBaseEndpoint()
    {
        return $this->getTestMode() ? $this->testEndpoint . "/" . self::API_VERSION : $this->liveEndpoint . "/" . self::API_VERSION;
    }

    public function getEndPoint()
    {
        return $this->getBaseEndpoint() . "/price/".$this->getProductCode()."/availability";
    }

    public function sendData($data)
    {

        $params = "";

        if (is_array($data)) {
            unset($data["apiKey"]);
            $params = http_build_query($data, '', '&');
        }

        $uri = $this->getEndpoint()."?".$params;

        $httpResponse = $this->httpClient->send("GET", $uri, ["api-key" => $this->getApiKey()]);

        return $this->createResponse($httpResponse->getBody());

    }

    protected function createResponse($data)
    {
        return $this->response = new GetProductAvailabilityResponse($this, $data);
    }

}
