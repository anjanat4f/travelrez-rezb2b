<?php

namespace Travelrez\Rezb2b\Process;

/**
 * Rezdy get product request
 */
class GetProductRequest extends AbstractRequest
{

    const API_VERSION = 'v1';

    protected $liveEndpoint = 'http://product.services.rezb2b.com';
    //protected $testEndpoint = 'http://product.services.qa.rezb2b.com';
    protected $testEndpoint = 'http://nayan-master.product.service';

    public function getData()
    {

        $this->validate('apiKey', 'product_code');

        $data = $this->getBaseData();

        return $data;
    }

    public function getBaseEndpoint()
    {
        return $this->getTestMode() ? $this->testEndpoint . "/" . self::API_VERSION : $this->liveEndpoint . "/" . self::API_VERSION;
    }

    public function getEndPoint()
    {
        return $this->getBaseEndpoint() . "/product/" . $this->getProductCode() . "/info";
    }

    public function sendData($data)
    {

        $params = "";

        /*if (is_array($data)) {
        $params = http_build_query($data, '', '&');
        }*/

        $uri = $this->getEndpoint();
        
        $httpResponse = $this->httpClient->send("GET", $uri, ["api-key" => $this->getApiKey()]);

        return $this->createResponse($httpResponse->getBody());

    }

    protected function createResponse($data)
    {
        return $this->response = new GetProductResponse($this, $data);
    }

}
