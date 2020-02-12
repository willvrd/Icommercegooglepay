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
        $this->publicKey = setting('icommerceGooglepay::publicKey');
        $this->privateKey1 = setting('icommerceGooglepay::privateKey1');
        $this->privateKey2 = setting('icommerceGooglepay::privateKey2');
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
            'publicKey' => $this->publicKey,
            'privateKey1' => $this->privateKey1,
            'privateKey2' => $this->privateKey2,
            'gateway' => $this->gateway,
            'gatewayMerchantId' => $this->gatewayMerchantId,
            'mode' => $this->mode,
            'image' => url($this->image),
            'status' => $this->status
        ];
    }
}
