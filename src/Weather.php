<?php

namespace TurnCypher\Weather;

use Cache;
use Carbon\Carbon;
use TurnCypher\Weather\Clients\Client;

class Weather
{
    /**
     * @var Client
     */
    private $client;
    private $config;

    const redisPrefix = 'weather:';

    public static function make($client)
    {

        $weather = new self();
        $weather->setClient(app($client));
        return $weather;
    }

    /**
     * @param mixed $client
     */
    public function setClient(Client $client)
    {
        $this->client = $client;
        $this->config = config('weather');
    }

    public function now($params)
    {
        return $this->client->now($params);
    }

    public function forecast($params)
    {
        $redisKey = self::redisPrefix . 'forecast:' . $params['city'];

        $data = Cache::get($redisKey);

        if (empty($data)) {
            $response = $this->client->forecast($params);

            $data = $this->formatForecast($params['channel'], $response);

            if ($data) {
                $refresh = Carbon::now()->addSeconds(config('weather.refreshPeriod'));
                Cache::put($redisKey, $data, $refresh);
            }
        }

        return $data;
    }


    public function suggestion($params)
    {
        $redisKey = self::redisPrefix . 'suggestion:' . $params['city'];

        $data = Cache::get($redisKey);

        if (empty($data)) {
            $response = $this->client->suggestion($params);

            $data = $this->formatSuggestion($params['channel'], $response);

            if ($data) {
                $refresh = Carbon::now()->addSeconds(config('weather.refreshPeriod'));
                Cache::put($redisKey, $data, $refresh);
            }
        }

        return $data;
    }


    /**
     * 格式渠道Api的Response
     * 实时天气
     * @param $channel
     *          渠道
     * @param data
     *          第三方数据源
     * @return array
     */
    private function formatNow($channel, $data)
    {
        if (empty($channel) || empty($data)) return;
        $data = \GuzzleHttp\json_decode($data, true);
        $arr = [];
        switch ($channel) {
            case 'seniverse':
                $tmp = $data['results'][0]['location'];

                $arr['cityCode'] = $tmp['id'];
                $arr['country'] = $tmp['country'];
                $arr['city'] = $tmp['name'];

                $now = $data['results'][0]['now'];

                $arr['regular'] = $now['text'];
                $arr['temperature'] = $now['temperature'];
                $arr['refreshPeriod'] = $this->config['refreshPeriod'];
                break;
            case 'heweather':
                $tmp = $data['HeWeather5'][0]['basic'];

                $arr['cityCode'] = $tmp['id'];
                $arr['country'] = $tmp['cnty'];
                $arr['city'] = $tmp['city'];

                $now = $data['HeWeather5'][0]['now'];

                $arr['regular'] = $now['cond']['txt'];
                $arr['temperature'] = $now['tmp'];
                $arr['refreshPeriod'] = $this->config['refreshPeriod'];
                break;
        }
        return $arr;
    }

    /**
     * 格式渠道Api的Reponse
     * 天气预报
     * @param chanel 渠道
     * @param data   第三方数据源
     * @return json
     */
    private function formatForecast($chanel, $data)
    {
        if (empty($chanel) || empty($data)) return;
        $data = \GuzzleHttp\json_decode($data, true);
        $arr = [];
        switch ($chanel) {
            case 'seniverse':
                $tmp = $data['results'][0]['location'];

                $arr['cityCode'] = $tmp['id'];
                $arr['country'] = $tmp['country'];
                $arr['city'] = $tmp['name'];

                $dailys = $data['results'][0]['daily'];//未来天气
                foreach ($dailys as $v) {
                    $daily['date'] = $v['date'];
                    $daily['regular_day'] = $v['text_day'];
                    $daily['regular_night'] = $v['text_night'];
                    $daily['high'] = $v['high'];
                    $daily['low'] = $v['low'];
                    $daily['wind_direction'] = $v['wind_direction'];
                    $daily['wind_speed'] = $v['wind_speed'];
                    $arr['daily'][] = $daily;

                }
                $arr['refreshPeriod'] = $this->config['refreshPeriod'];

                break;
            case 'heweather':
                $tmp = $data['HeWeather5'][0]['basic'];

                $arr['cityCode'] = $tmp['id'];
                $arr['country'] = $tmp['cnty'];
                $arr['city'] = $tmp['city'];

                $dailys = $data['HeWeather5'][0]['daily_forecast'];
                foreach ($dailys as $v) {
                    $daily['date'] = $v['date'];
                    $daily['regular_day'] = $v['cond']['txt_d'];
                    $daily['regular_night'] = $v['cond']['txt_n'];
                    $daily['high'] = $v['tmp']['max'];
                    $daily['low'] = $v['tmp']['min'];
                    $daily['wind_direction'] = $v['wind']['dir'];
                    $daily['wind_speed'] = $v['wind']['spd'];
                    $arr['daily'][] = $daily;

                }
                $arr['refreshPeriod'] = $this->config['refreshPeriod'];
                break;
        }

        return \GuzzleHttp\json_encode($arr, true);

    }

    /**
     * 格式渠道Api的Response
     * 生活指数
     * @param chanel
     *          渠道
     * @param data
     *          第三方数据源
     * @return mixed json
     */
    private function formatSuggestion($chanel, $data)
    {
        if (empty($chanel) || empty($data)) return;
        $data = \GuzzleHttp\json_decode($data, true);
        $arr = [];
        switch ($chanel) {
            case 'seniverse':
                $tmp = $data['results'][0]['location'];

                $arr['cityCode'] = $tmp['id'];
                $arr['country'] = $tmp['country'];
                $arr['city'] = $tmp['name'];

                $suggestion = $data['results'][0]['suggestion'];

                $arr['drsg']['brf'] = $suggestion['dressing']['brief'];
                $arr['drsg']['txt'] = $suggestion['dressing']['details'];//穿衣指数

                $arr['flu']['brf'] = $suggestion['flu']['brief'];
                $arr['flu']['txt'] = $suggestion['flu']['details'];//感冒指数

                $arr['sport']['brf'] = $suggestion['sport']['brief'];
                $arr['sport']['txt'] = $suggestion['sport']['details'];//运动指数

                $arr['uv']['brf'] = $suggestion['uv']['brief'];
                $arr['uv']['txt'] = $suggestion['uv']['details'];//紫外线指数

                $arr['refreshPeriod'] = $this->config['refreshPeriod'];
                break;
            case 'heweather':
                $tmp = $data['HeWeather5'][0]['basic'];

                $arr['cityCode'] = $tmp['id'];
                $arr['country'] = $tmp['cnty'];
                $arr['city'] = $tmp['city'];

                $suggestion = $data['HeWeather5'][0]['suggestion'];

                $arr['drsg']['brf'] = $suggestion['drsg']['brf'];
                $arr['drsg']['txt'] = $suggestion['drsg']['txt'];//穿衣指数

                $arr['flu']['brf'] = $suggestion['flu']['brf'];
                $arr['flu']['txt'] = $suggestion['flu']['txt'];//感冒指数

                $arr['sport']['brf'] = $suggestion['sport']['brf'];
                $arr['sport']['txt'] = $suggestion['sport']['txt'];//运动指数

                $arr['uv']['brf'] = $suggestion['uv']['brf'];
                $arr['uv']['txt'] = $suggestion['uv']['txt'];//紫外线指数

                $arr['refreshPeriod'] = $this->config['refreshPeriod'];
                break;
        }
        //dd($arr);
        return \GuzzleHttp\json_encode($arr, true);
    }

    public function integration($params)
    {
        return $this->client->integration($params);
    }


    /**
     * 格式化经纬度，保留小数点后两位
     * @param  string latlong 经纬度
     * @return string
     */
    private function formatLatLong($latlong)
    {
        if (empty($latlong)) return;

        if (stripos($latlong, ':') !== false) {

            $point = explode(':', $latlong);

            $p = stripos($point[0], '.');
            $lat = substr($point[0], 0, $p + 3);

            $p = stripos($point[1], '.');
            $long = substr($point[1], 0, $p + 3);

            return $lat . ':' . $long;
        }
    }

    private function formatIntegration($channel, $data)
    {
        if (empty($channel) || empty($data)) return;
        $data = \GuzzleHttp\json_decode($data, true);
        $arr = [];
        switch ($channel) {
            case 'seniverse':
                $tmp = $data['results'][0]['location'];

                $arr['cityCode'] = $tmp['id'];
                $arr['country'] = $tmp['country'];
                $arr['city'] = $tmp['name'];

                $suggestion = $data['results'][0]['suggestion'];

                $arr['drsg']['brf'] = $suggestion['dressing']['brief'];
                $arr['drsg']['txt'] = $suggestion['dressing']['details'];//穿衣指数

                $arr['flu']['brf'] = $suggestion['flu']['brief'];
                $arr['flu']['txt'] = $suggestion['flu']['details'];//感冒指数

                $arr['sport']['brf'] = $suggestion['sport']['brief'];
                $arr['sport']['txt'] = $suggestion['sport']['details'];//运动指数

                $arr['uv']['brf'] = $suggestion['uv']['brief'];
                $arr['uv']['txt'] = $suggestion['uv']['details'];//紫外线指数

                $arr['refreshPeriod'] = $this->config['refreshPeriod'];
                break;
            case 'heweather':

                $basic = $data['HeWeather5'][0]['basic'];
                $now = $data['HeWeather5'][0]['now'];
                $airQuality = $data['HeWeather5'][0]['aqi'];

                $dailyForecast = $data['HeWeather5'][0]['daily_forecast'];

                $arr['cityCode'] = $basic['id'];
                $arr['country'] = $basic['cnty'];
                $arr['city'] = $basic['city'];

                $arr['regular'] = $now['cond']['txt'];
                $arr['temp'] = $now['tmp'];
                $arr['humidity'] = $now['hum'];

                $arr['airQuality'] = $airQuality['city']['qlty'];

                $arr['windDirection'] = $now['wind']['dir'];
                $arr['wind'] = $now['wind']['sc'];

                $arr['tempMax'] = $dailyForecast[0]['tmp']['max'];
                $arr['tempMin'] = $dailyForecast[0]['tmp']['min'];

                $arr['refreshPeriod'] = config('weather.refreshPeriod');

                break;
        }
        //dd($arr);
        return \GuzzleHttp\json_encode($arr, true);
    }

}