<?php
/**
 * Created by PhpStorm.
 * User: emmanuel
 * Date: 17-4-24
 * Time: 下午11:52
 */
namespace TurnCypher\Weather\Clients;

use GuzzleHttp\Client as Guzzle;
use GuzzleHttp\Psr7\Response;

class SeniverseClient implements Client
{
    private $apiKey;
    private $guzzle;

    const API_NOW = 'weather/now.json';
    const API_FORECAST = 'weather/daily.json';

    /**
     * SeniverseClient constructor.
     * @param $config
     * @internal param $apiKey
     * @internal param \TurnCypher\Weather\Clients\Client $guzzle
     */
    public function __construct($config)
    {
        $this->guzzle = new Guzzle(['base_uri' => $config['api_base_url']]);
        $this->apiKey = $config['api_key'];
    }

    public function now($city){
        $query = [
            'location' => $city,
            'lang' => 'zh_Hans',
            'unit' => 'c',
            'key' => $this->apiKey,
        ];

        /**
         * @var Response $response
        */
        $response =  $this->guzzle->request('GET', self::API_NOW, ['query' => $query]);
        return $response->getBody()->getContents();
    }

    function forecast($city, $start, $days)
    {
        $query = [
            'location' => $city,
            'lang' => 'zh_Hans',
            'unit' => 'c',
            'key' => $this->apiKey,
            'start' => $start,
            'days' => $days
        ];
        $response =  $this->guzzle->request('GET', self::API_FORECAST, ['query' => $query]);
        return $response->getBody()->getContents();
    }
}