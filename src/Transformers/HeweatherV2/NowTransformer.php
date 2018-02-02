<?php
/**
 * Created by PhpStorm.
 * User: Emmanuel
 * Date: 2017/5/5
 * Time: 15:38
 */

namespace TurnCypher\Weather\Transformers\HeweatherV2;

class NowTransformer
{
    /**
     * NowTransformer constructor.
     * @param $data
     * @param $request
     */
    public function __construct($data)
    {
        $this->data = $data;
    }

    function handle()
    {
        $tmp = $this->data['HeWeather5'][0]['basic'];

        $arr['cityCode'] = $tmp['id'];
        $arr['country'] = $tmp['cnty'];
        $arr['city'] = $tmp['city'];

        $now = $this->data['HeWeather5'][0]['now'];

        $arr['code'] = $now['cond']['code'];
        $arr['weather'] = $now['cond']['txt'];
        $arr['temperature'] = $now['tmp'];
        $arr['feel'] = $now['fl'];
        $arr['humidity'] = $now['hum'];
        $arr['precipitation'] = $now['pcpn'];
        $arr['visible'] = $now['vis'];
        $arr['wind'] = $now['wind']['sc'];
        $arr['windDirection'] = $now['wind']['dir'];

        $result['weather'] = $arr;
        $result['message'] = 'ok';
        $result['code'] = config('weather.status.common.ok');
        return $result;
    }
}