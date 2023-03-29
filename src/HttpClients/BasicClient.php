<?php

namespace Yafimm\RajaOngkir\HttpClients;

use EngineException;
use Yafimm\RajaOngkir\Exceptions\ApiResponseException;
use Yafimm\RajaOngkir\Exceptions\BasicHttpClientException;

class BasicClient extends AbstractClient
{
    /** @var resource */
    protected $curl;

    public function request(array $payload = []): array
    {
        $this->initialize();

        curl_setopt_array($this->curl, [
            CURLOPT_CUSTOMREQUEST => $this->httpMethod,
            CURLOPT_HTTPHEADER => $this->buildHttpHeaders(),
        ]);

        if ('POST' === $this->httpMethod) {
            curl_setopt($this->curl, CURLOPT_POSTFIELDS, http_build_query($payload));
        }

        return $this->executeRequest($this->buildUrl($payload));
    }

    protected function initialize()
    {
        $this->curl = curl_init();

        curl_setopt_array($this->curl, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        ]);

        curl_setopt($this->curl, CURLINFO_HEADER_OUT, true);
    }

    protected function buildUrl(array $payload = []): string
    {
        $url = parent::buildUrl();

        if ('GET' === $this->httpMethod) {
            $url .= '?'.http_build_query($payload);
        }

        return $url;
    }

    private function buildHttpHeaders(): array
    {
        $headers = $this->httpHeaders;

        if ('POST' === $this->httpMethod) {
            $headers += ['Content-Type' => 'application/x-www-form-urlencoded'];
        }

        foreach ($headers as $headerKey => $headerValue) {
            $headers[$headerKey] = $headerKey.':'.$headerValue;
        }

        return $headers;
    }

    private function executeRequest(string $url): array
    {
        set_error_handler(function ($severity, $message) {
            throw new BasicHttpClientException('Client Error: '.$message, $severity);
        });

        curl_setopt($this->curl, CURLOPT_URL, $url); 

        $response = curl_exec($this->curl);
        $err = curl_error($this->curl);

        curl_close($this->curl);

        restore_error_handler();

        if ($err) {
            return $response;
        } else {
            $result = json_decode($response, true);
            $body   = $result['rajaongkir'];
            $status = $body['status'];

            if ( $status['code'] == 200 ) {
                if ( isset( $body['results'] ) ) {
                    if ( count( $body['results'] ) == 1 && isset( $body['results'][0] ) ) {
                        return $body['results'][0];
                    } elseif ( count( $body['results'] ) ) {
                        return $body['results'];
                    }
                } elseif ( isset( $body['result'] ) ) {
                    return $body['result'];
                }
            } else {
                $this->stopIfApiReturnsError($status);
            }
        }
        return [];
    }

    private function stopIfClientReturnsError($status)
    {
        if (false === $status) {
            throw new BasicHttpClientException('Client Error');
        }
    }

    private function stopIfApiReturnsError(array $status)
    {
        if (400 == $status['code']) {
            throw new ApiResponseException('RajaOngkir API Error: '.$status['description']);
        }
    }
}
