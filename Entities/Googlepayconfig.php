<?php

namespace Modules\Icommercegooglepay\Entities;

class Googlepayconfig
{
    private $description;
    private $image;
    private $status;
    public function __construct()
    {
        $this->description = setting('Icommercegooglepay::description');
        $this->image = setting('Icommercegooglepay::image');
        $this->status = setting('Icommercegooglepay::status');
    }

    public function getData()
    {
        return (object) [
            'description' => $this->description,
            'image' => url($this->image),
            'status' => $this->status
        ];
    }
}
