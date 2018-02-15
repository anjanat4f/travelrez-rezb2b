<?php

namespace Travelrez\Rezb2b\Process;

/**
 * Rezb2b get product request
 */
class CreateBookingRequest extends AbstractRequest
{

    const API_VERSION = '';

    protected $liveEndpoint = 'http://order.services.rezb2b.com';
    protected $testEndpoint = 'http://order.services.qa.rezb2b.com';

    public function getData()
    {

        $this->validate('apiKey');

        $data = $this->getBaseData();

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

    public function sendData($data)
    {

        $items    = $this->getItems();
        $customer = $this->getCustomer();
        $note     = $this->getNote();

        $params["item"]             = $this->reArrangePost($items);
        $params["order_subscriber"] = $customer;
        $params["note_info"]        = $note;
        $params["book_platform"]    = "api";

        $httpResponse = $this->httpClient->send("POST", $this->getEndPoint() , ["api-key" => $this->getApiKey()], $params);

        return $this->createResponse($httpResponse->getBody());

    }

    public function reArrangePost($items)
    {
        $return = array();

        if (!empty($items)) {
            foreach ($items as $k => $item) {

                $return[$k] = array(
                    "product_id"         => $item["product_code"],
                    "departure_date"     => $item["departure_start_date"],
                    "rate_options"       => $item["rates"],
                    "upgrade"            => isset($item["extra"]) ? $item["extra"] : [],
                    "guest"              => $item["guest"],
                    "departure_location" => isset($item["departure_location"]) ? $item["departure_location"] : "",
                    "pickup_location"    => isset($item["pickup_location"]) ? $item["pickup_location"] : "",
                );

            }
        }

        return $return;

    }

    protected function createResponse($data)
    {
        return $this->response = new CreateBookingResponse($this, $data);
    }

}
