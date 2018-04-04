<?php

namespace Travelrez\Rezb2b\Process;

/**
 * Rezdy get product request
 */
class GetProductPriceCalculationResponse extends Response
{



    public function isSuccessful()
    {

        if (isset($this->data->code) && $this->data->code == 200) {
                    
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

        $total = 0;
        $subTotal = 0;
        $bookingItems = array();

        if(!empty($item)){ 

            list($providerId, $productId)  = explode("-", $item->data->product_id);

            $price = array(
                "total" => $item->data->price->total,
                "sub_total" => $item->data->price->total,
                "price_breakdown" => $item->data->price->converted_total,
                "total_cost" => $item->data->price->total_cost,
                "sub_total_cost" => $item->data->price->sub_total_cost,
                "converted_cost" => $item->data->price->converted_cost,
                "attribute_total" => $item->data->price->attribute_total,
                "attribute_cost" => $item->data->price->attribute_cost
            );
            $bookingItems[] = array(
                "product_code"         => $productId,
                "provider_id"          => $providerId,
                "departure_start_date" => $item->data->departure_date,
                "departure_end_date"   => null,
                "sale_currency"        => $item->data->price->code,
                "price"                => $price
            );

        }

        return $bookingItems;

    }

}
