<?php
/**
 * PayPal Abstract Request
 */

namespace Travelrez\Rezb2b\Process;

abstract class AbstractRequest extends \Travelrez\Common\Message\AbstractRequest
{
    const API_VERSION = 'v1';

    protected $liveEndpoint = 'https://api.rezdy.com';
    protected $testEndpoint = 'https://api.rezdy-test.com';

    public function getApiKey()
    {
        return $this->getParameter('apiKey');
    }

    public function getProductCode()
    {
        return $this->getParameter('product_code');
    }

    public function setProductCode($value)
    {
        return $this->setParameter('product_code', $value);
    }

    public function getStartDate()
    {
        return $this->getParameter('start_date');
    }

    public function setStartDate($value)
    {
        return $this->setParameter('start_date', $value);
    }

    public function getEndDate()
    {
        return $this->getParameter('end_date');
    }

    public function setEndDate($value)
    {
        return $this->setParameter('end_date', $value);
    }

    public function getItems()
    {
        return $this->getParameter('items');
    }

    public function setItems($value)
    {
        return $this->setParameter('items', $value);
    }

    public function getCustomer()
    {
        return $this->getParameter('customer');
    }

    public function setCustomer($value)
    {
        return $this->setParameter('customer', $value);
    }

    public function getNote()
    {
        return $this->getParameter('note');
    }

    public function setNote($value)
    {
        return $this->setParameter('note', $value);
    }


    

    protected function getBaseData()
    {
        $data["apiKey"] = $this->getApiKey();
        return $data;
    }
    
    public function getOrderId()
    {
        return $this->getParameter('order_id');
    }

    public function setOrderId($value)
    {
        return $this->setParameter('order_id', $value);
    }

    public function sendData($data)
    {

        $params = "";

        if (is_array($data)) {
            $params = http_build_query($data, '', '&');
        }

        $uri = $this->getEndpoint() . "?" . $params;

        $httpResponse = $this->httpClient->send("GET", $uri, []);

        return $this->createResponse($httpResponse->getBody());

    }

    protected function getBaseEndpoint()
    {
        return $this->getTestMode() ? $this->testEndpoint . "/" . self::API_VERSION : $this->liveEndpoint . "/" . self::API_VERSION;
    }

    protected function createResponse($data)
    {

        return $this->response = new Response($this, $data);

    }

}
