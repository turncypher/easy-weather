<?php
/**
 * Created by PhpStorm.
 * User: emmanuel
 * Date: 18-2-1
 * Time: 下午5:00
 */

namespace TurnCypher\Weather\Requests\HeweatherV2;

use TurnCypher\Weather\Requests\WeatherRequest;

class Request extends WeatherRequest
{

    protected $city;
    protected $lang;
    protected $unit;
    protected $key;

    /**
     * Request constructor.
     */
    public function __construct()
    {
        $this->key = config('weather.drivers.heweatherv2.api_key');
    }
}