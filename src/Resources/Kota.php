<?php

namespace Yafimm\RajaOngkir\Resources;

use Yafimm\RajaOngkir\HttpClients\AbstractClient;

class Kota extends AbstractLocation
{
    /**
     * @param \Yafimm\RajaOngkir\HttpClients\AbstractClient $httpClient
     */
    public function __construct(AbstractClient $httpClient)
    {
        $this->httpClient = $httpClient;
        $this->httpClient->setEntity('city');
        $this->httpClient->setHttpMethod('GET');
    }

    /**
     * @return self
     */
    public function setSearchColumn()
    {
        $this->searchDriver->setSearchColumn('city_name');

        return $this;
    }

    /**
     * @param int|string $provinceId
     * @return self
     */
    public function dariProvinsi($provinceId): self
    {
        $this->result = $this->httpClient->request(['province' => $provinceId]);

        return $this;
    }
}
