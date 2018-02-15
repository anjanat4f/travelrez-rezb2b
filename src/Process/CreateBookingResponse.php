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

        echo "<pre>";print_r($this->data);die;

    }

    
}
