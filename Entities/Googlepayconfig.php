<?php

namespace Modules\Icommercegooglepay\Entities;

class Googlepayconfig
{
    private $description;
    private $merchantId;
    private $mode;
    private $image;
    private $status;
    public function __construct()
    {
        $this->description = setting('icommerceGooglepay::description');
        $this->merchantId = setting('icommerceGooglepay::merchantId');
        $this->merchantName = setting('icommerceGooglepay::merchantName');
        $this->gateway = setting('icommerceGooglepay::gateway');
        $this->gatewayMerchantId = setting('icommerceGooglepay::gatewayMerchantId');
        $this->mode = setting('icommerceGooglepay::mode');
        $this->image = setting('icommerceGooglepay::image');
        $this->status = setting('icommerceGooglepay::status');
    }

    public function getData()
    {
        return (object) [
            'description' => $this->description,
            'merchantId' => $this->merchantId,
            'merchantName' => $this->merchantName,
            'gateway' => $this->gateway,
            'gatewayMerchantId' => $this->gatewayMerchantId,
            'mode' => $this->mode,
            'image' => url($this->image),
            'status' => $this->status
        ];
    }
}
