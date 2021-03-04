<?php

declare(strict_types=1);
/**
 * Package: Street-Api.
 * 04 March 2021
 */


namespace Tests;

use App\Helpers\Config;
use GuzzleHttp\Client;

final class StreetTest extends \PHPUnit_Framework_TestCase
{
    private Client $http;

    public function setup()
    {
        $config = Config::init();
        $domain = implode('', $config->pick(['protocol', 'domain']));
        $this->http = new Client(['base_uri' => $domain]);
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
        $response = $this->http->request(
            'GET',
            '/fake',
            [
                'http_errors' => false
            ]
        );
        $this->assertEquals(400, $response->getStatusCode());
    }
}