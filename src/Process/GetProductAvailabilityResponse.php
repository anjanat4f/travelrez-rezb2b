<?php

namespace Travelrez\Rezb2b\Process;

/**
 * Rezb2b get product request
 */
class GetProductAvailabilityResponse extends Response
{

    public function isSuccessful()
    {

        if (isset($this->data->code)) {

            if ($this->data->code == 200) {

                if (!empty($this->data->prices)) {
                    return true;
                }

                $this->code    = 500;
                $this->message = "Sorry,No operation found!";

            }

            return false;

        }

        return false;
    }

    public function getResult()
    {

        $session = $this->getProductSessionFromData();

        if (!empty($session)) {

            return array(
                //"rate_options"    => $this->getRates($session),
                "available_dates" => $this->getAvailableDates($session),
            );

        }

    }

    public function getRates()
    {
        $rates = isset($this->data->rates) ? $this->data->rates : array();
        $returnRates =  array();

        if(!empty($rates)) {
            foreach($rates as $rate) {
                $returnRates[] = array(
                    "rate_id" => $rate->product_rate_type_id,
                    "name" => $rate->name,
                    "label" => $rate->label1,
                    "seats_used" => $rate->qty_count,
                    "min_quantity" => 1,
                    "max_quantity" => 1,
                    "price_type" => "ITEM##PERSON"
                );
            }
        }

        return $returnRates;
    }

    public function getProductSessionFromData()
    {

        return isset($this->data->prices) ? $this->data->prices : array();

    }

    public function getAvailableDates($session)
    {

        $departureDates = array();

        if (!empty($session)) {

            foreach ($session as $raw) {

                $operationDate = $raw->date;
                $departureTime = "00:00:00";

                if (!isset($departureDates[$operationDate])) {

                    $departureDates[$operationDate]                           = array();
                    $departureDates[$operationDate]["departure_date"]         = $operationDate;
                    $departureDates[$operationDate]["is_need_departure_time"] = 0;

                }

                if (!isset($departureDates[$operationDate]["departure_times"][$departureTime])) {

                    $departureDates[$operationDate]["departure_times"][$departureTime] = array("departure_time" => $departureTime);

                }

                $departureDates[$operationDate]["departure_times"][$departureTime]["rates"] = $this->getSessionRates($raw->rates);

            }

        }

        return $departureDates;

    }

    public function getSessionRates($priceOptions)
    {

        $rates = array();

        if (!empty($priceOptions)) {

            foreach ($priceOptions as $priceRate) {

                $rates[] = array(
                    "rate_id"    => $priceRate->product_rate_type_id,
                    "price"      => $priceRate->price,
                    "label"      => $priceRate->name,
                    "seats_uses" => 1,
                );

            }

        }

        return $rates;

    }

}
