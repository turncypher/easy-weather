<?php
/**
 * Created by PhpStorm.
 * User: emmanuel
 * Date: 17-4-24
 * Time: 下午11:37
 */
namespace TurnCypher\Weather;

use TurnCypher\Weather\Clients\Client;
class Weather
{
    private $client;

    public function now($city){
        return $this->client->now($city);
    }

    /**
     * @param mixed $client
     */
    public function setClient(Client $client)
    {
        $this->client = $client;
    }

}