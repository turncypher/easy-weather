<?php

namespace TurnCypher\Weather\Requests\Heweather;

use TurnCypher\Weather\Requests\WeatherRequest;

/**
 * Created by PhpStorm.
 * User: emmanuel
 * Date: 17-7-12
 * Time: 上午11:04
 */
class ForecastRequest extends WeatherRequest
{
    public function __construct(array $params)
    {
        $this->city = $params['city'];
        $this->lang = isset($params['lang']) ? $params['lang'] : 'zh_cn';
        $this->unit = isset($params['unit']) ? $params['unit'] : 'c';
        $this->key = config('weather.drivers.heweather.api_key');
    }
}