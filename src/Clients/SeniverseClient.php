<?php
/**
 * Created by PhpStorm.
 * User: emmanuel
 * Date: 17-4-24
 * Time: 下午11:52
 */
namespace TurnCypher\Weather\Clients;

use GuzzleHttp\Client as Guzzle;
use TurnCypher\Weather\Clients\Client;
class SeniverseClient implements Client
{
    const API_NOW = 'https://api.seniverse.com/v3/weather/now.json';

    private $apiKey = 'rq00ignk2mjlk557';
    private $guzzle;

    /**
     * SeniverseClient constructor.
     * @param $apiKey
     * @param Client $guzzle
     */
    public function __construct()
    {
        $this->guzzle = new Guzzle();
    }

    public function now($city){
        $query = [
            'location' => $city,
            'lang' => 'zh_Hans',
            'unit' => 'c',
            'key' => $this->apiKey,

        ];
        $respose =  $this->guzzle->request('GET', self::API_NOW, ['query' => $query]);
        return $respose->getBody();
    }
}