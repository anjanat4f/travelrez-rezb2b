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
        return $this->getBaseEndpoint() . "agent/order/create";
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

        $items = $this->getItems();

        $postData = array();

        $customer = $this->getCustomers();

        $postData["item"][] = array(
            "product_id"         => $items["product_code"],
            "order_product_id"   => $items["order_product_id"],   
            "departure_date"     => $items["departure_start_date"],
            "rate_options"       => $items["rates"],
            "upgrade"            => $items["options"],
            "guest"              => $items["participants"],
            "pickup_location"    => @$items["pickup_location"],
            "departure_location" => @$items["departure_location"],
            "flight"             => array(
                'arrival_airline_name'   => @$items["flight"]['arrival_airline_name'],
                'arrival_flight_no'      => @$items["flight"]['arrival_flight_no'],
                'arrival_airport_name'   => @$items["flight"]['arrival_airport_name'],
                'arrival_date'           => @$items["flight"]['arrival_date'],
                'arrival_time'           => @$items["flight"]['arrival_time'],
                'departure_airline_name' => @$items["flight"]['departure_airline_name'],
                'departure_flight_no'    => @$items["flight"]['departure_flight_no'],
                'departure_airport_name' => @$items["flight"]['departure_airport_name'],
                'departure_date'         => @$items["flight"]['departure_date'],
                'departure_time'         => @$items["flight"]['departure_time'],
            ),

        );

        $postData["order_subscriber"] = isset($customer[0]) ? $customer[0] : $customer;

        /*$note     = $this->getNote();

        $params["item"]             = $this->reArrangePost($items);
        $params["order_subscriber"] = $customer[0];
        $params["note_info"]        = "";
         */

        $postData["note_info"]     = $this->getNote();
        $postData["order_id"]      = $this->getOrderId();
        $postData["book_platform"] = "api";
        $httpResponse = $this->httpClient->send("POST", $this->getEndPoint(), ["api-key" => $this->getApiKey()], $postData);
        return $this->createResponse($httpResponse->getBody());

    }

    protected function createResponse($data)
    {
        return $this->response = new CreateBookingResponse($this, $data);
    }

}
