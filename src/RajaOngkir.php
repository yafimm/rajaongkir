<?php

namespace Yafimm\RajaOngkir;

use Yafimm\RajaOngkir\Contracts\HttpClientContract;
use Yafimm\RajaOngkir\Contracts\SearchDriverContract;
use Yafimm\RajaOngkir\HttpClients\AbstractClient;
use Yafimm\RajaOngkir\HttpClients\BasicClient;
use Yafimm\RajaOngkir\Resources\Kota;
use Yafimm\RajaOngkir\Resources\OngkosKirim;
use Yafimm\RajaOngkir\Resources\Provinsi;
use Yafimm\RajaOngkir\SearchDrivers\AbstractDriver;
use Yafimm\RajaOngkir\SearchDrivers\BasicDriver;

class RajaOngkir
{
    /** @var \Yafimm\RajaOngkir\Contracts\HttpClientContract */
    protected $httpClient;

    /** @var \Yafimm\RajaOngkir\Contracts\SearchDriverContract */
    protected $searchDriver;

    /** @var array */
    protected $options;

    /** @var string */
    private $apiKey;

    /** @var string */
    private $package;

    /**
     * @param string $apiKey
     * @param string $package
     */
    public function __construct(string $apiKey, string $package = 'starter')
    {
        $this->apiKey = $apiKey;
        $this->package = $package;

        $this->setHttpClient(new BasicClient);
    }

    /**
     * @param \Yafimm\RajaOngkir\Contracts\HttpClientContract $httpClient
     * @return self
     */
    public function setHttpClient(HttpClientContract $httpClient): self
    {
        $this->httpClient = $httpClient;
        $this->httpClient->setApiKey($this->apiKey);
        $this->httpClient->setPackage($this->package);

        return $this;
    }

    /**
     * @param \Yafimm\RajaOngkir\Contracts\SearchDriverContract $searchDriver
     * @return self
     */
    public function setSearchDriver(SearchDriverContract $searchDriver): self
    {
        $this->searchDriver = $searchDriver;

        return $this;
    }

    /**
     * @return \Yafimm\RajaOngkir\Resources\Provinsi;
     */
    public function provinsi(): Provinsi
    {
        $resource = new Provinsi($this->httpClient);

        if (null === $this->searchDriver) {
            $resource->setSearchDriver(new BasicDriver);
            $resource->setSearchColumn();
        }

        return $resource;
    }

    /**
     * @return \Yafimm\RajaOngkir\Resources\Kota;
     */
    public function kota(): Kota
    {
        $resource = new Kota($this->httpClient);

        if (null === $this->searchDriver) {
            $resource->setSearchDriver(new BasicDriver);
            $resource->setSearchColumn();
        }

        return $resource;
    }

    /**
     * @param array $payload
     * @return \Yafimm\RajaOngkir\Resources\OngkosKirim;
     */
    public function ongkosKirim(array $payload): OngkosKirim
    {
        return new OngkosKirim($this->httpClient, $payload);
    }

    /**
     * @return \Yafimm\RajaOngkir\Resources\OngkosKirim;
     */
    public function ongkir(array $payload): OngkosKirim
    {
        return $this->ongkosKirim($payload);
    }

    /**
     * @return \Yafimm\RajaOngkir\Resources\OngkosKirim;
     */
    public function biaya(array $payload): OngkosKirim
    {
        return $this->ongkosKirim($payload);
    }
}
