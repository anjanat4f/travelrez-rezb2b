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
                
                return true;

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
                "available_dates" => $this->getAvailableDates($session),
            );

        }

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
