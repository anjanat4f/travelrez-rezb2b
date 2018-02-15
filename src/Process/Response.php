<?php

namespace Travelrez\Rezb2b\Process;

use Travelrez\Common\Message\AbstractResponse;
use Travelrez\Common\Message\RequestInterface;

/**
 * Rezdy Response
 */
class Response extends AbstractResponse
{

    protected $code;
    protected $message;

    public function __construct(RequestInterface $request, $data)
    {

        $this->request = $request;

        $this->data = is_array($data) ? $data : json_decode((string)$data);

    }

    public function getMessage()
    {

        if (!$this->isSuccessful()) {

            if (isset($this->data->code) && $this->data->code != 200 ) {

                $this->message = json_encode($this->data->message);

            }

            return $this->message;

        }
    }

    public function getCode()
    {
        if (!$this->isSuccessful()) {

            if (isset($this->data->code) && $this->data->code != 200 ) {

                $this->code = $this->data->code;

            }

            return $this->code;
        }
        return null;
    }

}
