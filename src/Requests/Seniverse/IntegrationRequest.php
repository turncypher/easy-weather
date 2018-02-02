<?php
/**
 * Created by PhpStorm.
 * User: Emmanuel
 * Date: 2017/5/4
 * Time: 13:50
 */

namespace TurnCypher\Weather\Requests\Seniverse;

use TurnCypher\Weather\Requests\WeatherRequest;

class IntegrationRequest extends WeatherRequest
{
    protected $city;
    protected $lang;
    protected $unit;
    protected $key;

    /**
     * NowRequest constructor.
     * @param $params
     * @internal param Request $request
     */
    public function __construct(array $params)
    {
        $this->city = $params['city'];
        $this->lang = isset($params['lang']) ? $params['lang'] : 'zh-Hans';
        $this->unit = isset($params['unit']) ? $params['unit'] : 'c';
        $this->key = config('weather.drivers.heweather.api_key');
    }
}