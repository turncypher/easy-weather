<?php
/**
 * Created by PhpStorm.
 * User: Emmanuel
 * Date: 2017/4/25
 * Time: 12:20
 */
namespace TurnCypher\Weather\Clients;

use GuzzleHttp\Client as Guzzle;
class HeWeatherClient implements Client
{

    private $apiKey;
    private $guzzle;

    const API_NOW = 'now';
    const API_FORECAST = 'now';
    /**
     * HeWeather constructor.
     * @param $config
     */
    public function __construct($config)
    {
        $this->guzzle = new Guzzle(['base_uri' => $config['api_base_url']]);
        $this->apiKey = $config['api_key'];
    }

    function now($city)
    {
        $query = [
            'city' => $city,
            'lang' => 'zh-cn',
            'key' => $this->apiKey,
        ];
        $response =  $this->guzzle->request('GET', self::API_NOW, ['query' => $query]);
        return $response->getBody();
    }

    function forecast($city, $start, $days)
    {

    }
}