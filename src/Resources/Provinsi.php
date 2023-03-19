<?php

namespace Yafimm\RajaOngkir\Resources;

use Yafimm\RajaOngkir\HttpClients\AbstractClient;

class Provinsi extends AbstractLocation
{
    /**
     * @param \Yafimm\RajaOngkir\HttpClients\AbstractClient $httpClient
     */
    public function __construct(AbstractClient $httpClient)
    {
        $this->httpClient = $httpClient;
        $this->httpClient->setEntity('province');
        $this->httpClient->setHttpMethod('GET');
    }

    /**
     * @return self
     */
    public function setSearchColumn()
    {
        $this->searchDriver->setSearchColumn('province');

        return $this;
    }
}
