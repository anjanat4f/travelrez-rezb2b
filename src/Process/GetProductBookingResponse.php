<?php

namespace Travelrez\Rezb2b\Process;

/**
 * Rezdy get product request
 */
class GetProductBookingResponse extends Response
{

    public function isSuccessful()
    {

        if ($this->data->code == 200) {

            return true;

        }

        return false;
    }

    /**
     *   total_amount = 25.00
     *   total_currency = USD
     *   items = [[
     *        product_name         : <PROTDUCT_TITLE>
     *        product_code         : <PROTDUCT_CODE>
     *        departure_start_date : <DEPARTURE_START_DATE>
     *        departure_end_date   : <DEPARTURE_END_DATE>
     *        rates                : [
     *               "rate_id" : XXXX
     *               "rate_name" : Adult
     *               "qty" : 1
     *        ],
     *        no_of_guest : 1
     *        sub_total : 25.00
     *        total : 25.00
     *    ]]
     *
     *
     **/

    public function getResult()
    {

        $bookingReturn = array();

        $item = $this->data;

        $request = $this->getRequest()->getParameters();

        $total        = 0;
        $subTotal     = 0;
        $bookingItems = array();

        if (!empty($item)) {

            list($providerId, $productId) = explode("-", $item->basicInfo->product_id);

            $bookingItems = array(
                "product_code"       => $productId,
                "product_name"       => isset($item->basicInfo->product_name) ? $item->basicInfo->product_name : "",
                "sale_quantity_rule" => "PERSON",
                /*"departure_start_date" => $item->data->departure_date,
            "departure_end_date"   => $item->data->departure_date,
            "rates"                => array(),
            "no_of_guest"          => 0,
            "sub_total"            => $item->data->price->sub_total,
            "total"                => $item->data->price->total*/
            );

            $pricingSection = $this->getPricingSection($item->prices, $request["departure_start_date"]);

            $firstRate = reset($pricingSection->rates);

            $bookingItems["price"] = array(
                "sale_currency"       => "USD",
                "min_booking_size"    => null,
                "spaces_remaining"    => null,
                "total_price"         => $firstRate->price,
                "total_price_display" => $firstRate->converted_price,
                "price_breakdown"     => $firstRate->converted_price,
            );

            
            $attributes = $this->getAttributes($item->attributes);

            $bookingItems["attributes"] = $attributes;

            $pickupPoints = $this->getPickupPoints($pricingSection);

            if (!empty($pickupPoints)) {
                $bookingItems["pickup_points"] = $pickupPoints;
            }

        }

        return $bookingItems;

    }

    public function getPricingSection($pricing, $match = null)
    {

        if (!empty($pricing)) {

            if (!$match) {

                return reset($pricing);

            } else {

                $time = strtotime($match);

                foreach ($pricing as $price) {
                    if (strtotime($price->date) == $time) {
                        return $price;
                    }
                }

            }

        }

        return false;

    }

    public function getPickupPoints($pricing)
    {

        $timeLocation = array();

        if (!empty($pricing->departures)) {

            foreach ($pricing->departures->data as $departure) {

                $availTimes = $departure->available_times;

                foreach ($availTimes as $key => $time) {
                    $timeLocation[] = array(
                        "pickup_key"  => $time . " " . $departure->title,
                        "time"        => $time,
                        "pickup_name" => $departure->title,
                        "description" => "",
                        "address1"    => $departure->address,
                        "address2"    => "",
                        "postcode"    => "",
                        "geocode"     => $departure->latitude.",".$departure->longitute,
                    );
                }

            }
        }

        return $timeLocation;

    }


    public function getAttributes($attributes)
    {

        $returnAttr = array();

        if(!empty($attributes)) {
            foreach ($attributes as $key => $raw) {
                
                $returnAttr[] = array(
                    "option_name"       => $raw->upgrade_name,
                    "option_id"         => $raw->upgrade_id,
                    "short_description" => isset($raw->upgrade_description) ? $raw->upgrade_description : "",
                    "option_selections" => $this->attributeSelections($raw->options),
                );

            }
        }

        return $returnAttr;
    }


    public function attributeSelections($options)
    {
        $selections = array();

        if(!empty($options)) {

            foreach($options as $option) {

                $selections[] = array(
                    "value"                => $option->option_id,
                    "price"                => null,
                    "option_sale_currency" => "USD",
                    "text"                 => $option->option_name,
                    "is_has_sub"           => !empty($option->options) ? 1 : 0,
                    "sub_options"          => !empty($option->options) ? $this->getSuboptions($option->options) : array(),  
                );

            }

        }

        return $selections;

    }

    public function getSuboptions($subOptions)
    {

        $selections = array();

        if(!empty($subOptions)) {

            foreach($subOptions as $option) {

                $selections[] = array(
                    "value"                => $option->parent_id."_".$option->option_id,
                    "price"                => null,
                    "option_sale_currency" => "USD",
                    "text"                 => $option->option_name,
                );

            }

        }

        return $selections;

    }

}
