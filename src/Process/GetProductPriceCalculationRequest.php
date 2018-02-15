<?php

namespace Travelrez\Rezb2b\Process;

/**
 * Rezb2b get product request
 */
class GetProductPriceCalculationRequest extends AbstractRequest
{

    const API_VERSION = 'v1';

    protected $liveEndpoint = 'http://product.services.rezb2b.com';
    protected $testEndpoint = 'http://product.services.qa.rezb2b.com';

    public function getBaseEndpoint()
    {
        return $this->getTestMode() ? $this->testEndpoint . "/" . self::API_VERSION : $this->liveEndpoint . "/" . self::API_VERSION;
    }

    public function getEndPoint()
    {
        return $this->getBaseEndpoint() . "/price";
    }

    public function getData()
    {

        $postData = $this->getItems();

        $data["items"] = $postData;

        return $data;

    }

    public function sendData($data)
    {

        $response = array();

        if (is_array($data)) {
            unset($data["apiKey"]);
        }

        $uri = $this->getEndpoint();

        if (!empty($data["items"])) {

            foreach ($data["items"] as $key => $row) {

                $rates = array();

                if (!empty($row["rates"])) {
                    foreach ($row["rates"] as $roomNo => $rowRates) {
                        foreach ($rowRates as $rowR) {
                            $rates[$roomNo][$rowR["rate_id"]] = $rowR["qty"];
                        }

                    }
                }

                $params = array(
                    "departure_date" => $row["departure_start_date"],
                    "rate_options"   => $rates,
                );

                $httpResponse = $this->httpClient->send("POST", $uri . "/" . $row["product_code"] . "/price-calculate", ["api-key" => $this->getApiKey()], $params);

                $response[] = json_decode((string)$httpResponse->getBody());

            }
        }

        return $this->createResponse($response);

    }

    protected function createResponse($data)
    {

        return $this->response = new GetProductPriceCalculationResponse($this, $data);
    }

}
