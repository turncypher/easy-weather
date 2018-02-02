<?php
/**
 * Created by PhpStorm.
 * User: Emmanuel
 * Date: 2017/5/5
 * Time: 15:39
 */

namespace TurnCypher\Weather\Transformers\HeweatherV2;

class IntegrationTransformer
{
    private $data;
    private $request;


    /**
     * IntegrationTransformer constructor.
     * @param $data
     * @param $request
     */
    public function __construct($data, $request)
    {
        $this->data = $data;
        $this->request = $request;
    }

    function handle()
    {
        $basic = $this->data['HeWeather6'][0]['basic'];
        $now = $this->data['HeWeather6'][0]['now'];
        if (isset($this->data['HeWeather6'][0]['aqi'])) {
            $airQuality = $this->data['HeWeather6'][0]['aqi'];
            $arr["aqi"] = $airQuality['city']['aqi'];
            $quality = array_first(config('weather.aqi'), function ($condition, $key) use ($arr) {
                return ($arr['aqi'] < $key);
            });
            $arr['airQuality'] = trans("weather.aqi.{$quality}", [], $this->request->toArray()['lang']);
        }
        $dailyForecast = $this->data['HeWeather6'][0]['daily_forecast'];
        $arr['cityCode'] = $basic['cid'];
        $arr['country'] = $basic['cnty'];
//        $arr['city'] = $basic['city'];
        $arr['weather'] = $now['cond']['txt'];
        $arr['code'] = $now['cond']['code'];
        $arr['temp'] = $now['tmp'];
        $arr['humidity'] = $now['hum'];


        $arr['windDirection'] = $now['wind']['dir'];
        $arr['wind'] = $now['wind']['sc'];

        $arr['tempMax'] = $dailyForecast[0]['tmp']['max'];
        $arr['tempMin'] = $dailyForecast[0]['tmp']['min'];

        $result['weather'] = $arr;
        $result['message'] = 'ok';
        $result['code'] = config('weather.status.common.ok');

        return $result;
    }
}