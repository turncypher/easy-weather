<?php
/**
 * user: emmanuel
 * data: 2017-4-24
 */

namespace TurnCypher\Weather\Clients;

use GuzzleHttp\Client as Guzzle;
use GuzzleHttp\Psr7\Response;

class SeniverseClient implements Client
{
    private $apiKey;
    private $guzzle;
    private $cacheKey;

    const API_NOW = 'weather/now.json';//实时天气
    const API_FORECAST = 'weather/daily.json';//未来天气
    const API_SUGGESTION = 'life/suggestion.json';//生活指数

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
        $this->cacheKey = $config['cache_key'];
    }

    public function now($params)
    {
        $query = [
            'location' => $params['city'],
            'lang' => isset($params['lang']) ? $params['lang'] : 'zh-Hans',
            'unit' => 'c',
            'key' => $this->apiKey,
        ];
        $response = $this->guzzle->request('GET', self::API_NOW, ['query' => $query]);
        return $response->getBody()->getContents();
    }

    function forecast($params)
    {
        $query = [
            'location' => $params['city'],
            'lang' => isset($params['lang']) ? $params['lang'] : 'zh-Hans',
            'unit' => 'c',
            'key' => $this->apiKey,
            'start' => $params['start'],
            'days' => $params['days']
        ];
        $response = $this->guzzle->request('GET', self::API_FORECAST, ['query' => $query]);
        return $response->getBody()->getContents();
    }

    function suggestion($params)
    {
        $query = [
            'location' => $params['city'],
            'lang' => isset($params['lang']) ? $params['lang'] : 'zh-Hans',
            'key' => $this->apiKey,
        ];

        $response = $this->guzzle->request('GET', self::API_SUGGESTION, ['query' => $query]);
        return $response->getBody()->getContents();

    }
}