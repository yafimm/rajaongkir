<?php

namespace Yafimm\RajaOngkir\Resources;

abstract class AbstractResource
{
    /** @var array */
    protected $result = [];

    /** @var \Yafimm\RajaOngkir\HttpClients\AbstractClient */
    protected $httpClient;

    public function get(): array
    {
        return $this->result;
    }
}
