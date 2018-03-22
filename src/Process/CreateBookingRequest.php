<?php

namespace Travelrez\Rezb2b\Process;

/**
 * Rezb2b get product request
 */
class CreateBookingRequest extends AbstractRequest
{

    const API_VERSION = '';

    protected $liveEndpoint = ORDER_SERVICE_URL;
    protected $testEndpoint = ORDER_SERVICE_URL_SANDBOX;

    public function getData()
    {

        $this->validate('apiKey');

        $data = $this->getParameters();

        return $data;
    }

    public function getBaseEndpoint()
    {
        return $this->getTestMode() ? $this->testEndpoint . "/" . self::API_VERSION : $this->liveEndpoint . "/" . self::API_VERSION;
    }

    public function getEndPoint()
    {
        return $this->getBaseEndpoint() . "/agent/order/create";
    }

    public function getCustomers()
    {
        return $this->getParameter("customers");
    }

    public function setCustomers($value)
    {
        return $this->setParameter("customers", $value);
    }

    public function sendData($data)
    {

        $items    = $this->getItems();


        $postData = array();

        $customer = $this->getCustomers();

        $postData["item"][] =  array(
            "product_id"     => $items["product_code"],
            "departure_date" => $items["departure_start_date"],
            "rate_options"   => $items["rates"],
            "upgrade"        => $items["options"],
            "guest"          => $items["participants"]   
        );

        $postData["order_subscriber"] = $customer[0];
        
        /*$note     = $this->getNote();

        $params["item"]             = $this->reArrangePost($items);
        $params["order_subscriber"] = $customer[0];
        $params["note_info"]        = "";
        */
        $postData["book_platform"]    = "api";

        //echo '<pre>'; print_r($postData); echo '</pre>';exit();

        $httpResponse = $this->httpClient->send("POST", $this->getEndPoint() , ["api-key" => $this->getApiKey()], $postData);

        return $this->createResponse($httpResponse->getBody());

    }

    protected function createResponse($data)
    {
        return $this->response = new CreateBookingResponse($this, $data);
    }

}
