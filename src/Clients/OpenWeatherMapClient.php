<?php
/**
 */

namespace TurnCypher\Weather\Clients;

use GuzzleHttp\Client as Guzzle;

class OpenWeatherMapClient implements Client
{

    private $apiKey;
    private $guzzle;

    const API_NOW = 'data/2.5/weather';
    const API_FORECAST = 'forecast';
    const API_SUGGESTION = 'suggestion';
    const API_WEATHER = 'weather';

    /**
     * HeWeather constructor.
     * @param $config
     */
    public function __construct($config)
    {
        $this->guzzle = new Guzzle(['base_uri' => $config['api_base_url']]);
        $this->apiKey = $config['api_key'];
    }

    //实时天气
    function now($params)
    {
        if (str_contains($params['city'], ',')) {
            $coordinates = explode(',', $params['city']);
            $query['lat'] = $coordinates[0];
            $query['lon'] = $coordinates[1];
        } else {
            $query['q'] = $params['city'];
        }

        $query['lang'] = isset($params['lang']) ? $params['lang'] : 'zh_cn';
        $query['units'] = isset($params['unit']) ? $params['unit'] : 'metric';
        $query['appid'] = $this->apiKey;

        $response = $this->guzzle->request('GET', self::API_NOW, ['query' => $query]);
        return $response->getBody();
    }

    //天气查询
    function forecast($params)
    {
        $query = [
            'city' => $params['city'],
            'lang' => isset($params['lang']) ? $params['lang'] : 'zh-Hans',
            'appid' => $this->apiKey,
        ];
        $response = $this->guzzle->request('GET', self::API_FORECAST, ['query' => $query]);
        return $response->getBody();

    }

    function suggestion($params)
    {
        $query = [
            'city' => $params['city'],
            'lang' => $params['lang'],
            'appid' => $this->apiKey,
        ];
        $response = $this->guzzle->request('GET', self::API_SUGGESTION, ['query' => $query]);
        return $response->getBody();

    }

    function integration($params)
    {
        $query = [
            'city' => $params['city'],
            'lang' => isset($params['lang']) ? $params['lang'] : 'zh-Hans',
            'appid' => $this->apiKey,
        ];
        $response = $this->guzzle->request('GET', self::API_WEATHER, ['query' => $query]);
        return $response->getBody();
    }
}