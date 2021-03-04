<?php

declare(strict_types=1);
/**
 * Package: Street-Api.
 * 04 March 2021
 */


namespace Tests;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;

final class StreetTest extends \PHPUnit_Framework_TestCase
{
    private Client $http;

    public function setup()
    {
        $this->http = new Client(['base_uri' => 'http://html.test']);
    }

    public function testValid()
    {
        $response = $this->http->request('GET', '/streets');
        $this->assertEquals(200, $response->getStatusCode());
    }

    public function testPagination()
    {
        $response = $this->http->request('GET', '/streets/4');
        $this->assertEquals(200, $response->getStatusCode());
    }

    public function testBad()
    {
        $response = $this->http->request('GET', '/fake',[
                'http_errors' => false
            ]);
        $this->assertEquals(400, $response->getStatusCode());
    }
}