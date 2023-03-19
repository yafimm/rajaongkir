<?php

namespace Yafimm\RajaOngkir\Tests\Integration;

use Yafimm\RajaOngkir\Facades\RajaOngkir;
use Yafimm\RajaOngkir\Providers\LaravelServiceProvider as RajaOngkirServiceProvider;
use Orchestra\Testbench\TestCase as Orchestra;

abstract class TestCase extends Orchestra
{
    public function setUp(): void
    {
        parent::setUp();
    }

    protected function getPackageProviders($app): array
    {
        return [
            RajaOngkirServiceProvider::class,
        ];
    }

    protected function getPackageAliases($app): array
    {
        return [
            'RajaOngkir' => RajaOngkir::class,
        ];
    }
}
