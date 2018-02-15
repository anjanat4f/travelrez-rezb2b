<?php

namespace Travelrez\Rezb2b\Process;

/**
 * Rezdy get product request
 */
class GetProductPriceCalculationResponse extends Response
{



    public function isSuccessful()
    {

        if(is_array($this->data)) {
            foreach ($this->data as $key => $row) {

                if ($row->code != 200) {
                    
                    return false;

                }
            }

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

        $items = $this->data;

        $total = 0;
        $subTotal = 0;
        $bookingItems = array();

        if(!empty($items)){ 

            foreach($items as $item) {

                $total += $item->data->price->total;
                $subTotal += $item->data->price->total;

                $bookingItems[] = array(
                    "product_name"         => $item->data->product_name,
                    "product_code"         => $item->data->product_id,
                    "departure_start_date" => $item->data->departure_date,
                    "departure_end_date"   => $item->data->departure_date,
                    "rates"                => array(),
                    "no_of_guest"          => 0,
                    "sub_total"            => $item->data->price->sub_total,
                    "total"                => $item->data->price->total
                );



            }

            $bookingReturn["total_amount"]   = $total;
            $bookingReturn["total_currency"] = $subTotal;
            $bookingReturn["items"]          = $bookingItems;

        }

        return $bookingReturn;

    }

}
