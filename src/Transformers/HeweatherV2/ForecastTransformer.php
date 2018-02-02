<?php
/**
 * Created by PhpStorm.
 * User: emmanuel
 * Date: 17-7-12
 * Time: 上午11:06
 */

namespace TurnCypher\Weather\Transformers\HeweatherV2;


class ForecastTransformer
{
    private $data;
    private $request;

    public function __construct($data, $request)
    {
        $this->data = $data;
        $this->request = $request;
    }

    function handle()
    {
        $basic = $this->data['HeWeather5'][0]['basic'];
        $now = $this->data['HeWeather5'][0]['now'];
        if (isset($this->data['HeWeather5'][0]['aqi'])) {
            $airQuality = $this->data['HeWeather5'][0]['aqi'];
            $arr["aqi"] = $airQuality['city']['aqi'];
            $quality = array_first(config('weather.aqi'), function ($condition, $key) use ($arr) {
                return ($arr['aqi'] < $key);
            });
            $arr['airQuality'] = trans("weather.aqi.{$quality}", [], $this->request->toArray()['lang']);
        }
        $dailyForecast = $this->data['HeWeather5'][0]['daily_forecast'];

        $arr['cityCode'] = $basic['id'];
        $arr['country'] = $basic['cnty'];
        $arr['city'] = $basic['city'];

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