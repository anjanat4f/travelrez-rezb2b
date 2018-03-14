<?php

namespace Travelrez\Rezb2b\Process;

/**
 * Rezdy get product request
 */
class CreateBookingResponse extends Response
{

    public function isSuccessful()
    {
        if(isset($this->data->code) && $this->data->code == 200){
        	return true;
        }

        return false;
    }

    public function getResult()
    {
        $firstItem = reset($this->data->data->order_product->item);

        return array(
            "booking_id" => $this->data->data->order_id,
            "status"     => $firstItem->status,
        );

    }

    
}
