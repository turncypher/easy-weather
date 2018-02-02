<?php
/**
 * Created by PhpStorm.
 * User: Emmanuel
 * Date: 2017/4/25
 * Time: 9:16
 */

namespace TurnCypher\Weather;

use Illuminate\Support\ServiceProvider;
use TurnCypher\Weather\Clients\HeWeatherClient;
use TurnCypher\Weather\Clients\SeniverseClient;
use TurnCypher\Weather\Clients\HeWeatherV2Client;
use TurnCypher\Weather\Clients\OpenWeatherMapClient;

class WeatherServiceProvider extends ServiceProvider
{
    public function register()
    {
        $config = config('weather.drivers');
        $this->app->singleton('seniverse', function () use ($config) {
            return new SeniverseClient($config['seniverse']);
        });
        $this->app->singleton('heweather', function () use ($config) {
            return new HeWeatherClient($config['heweather']);
        });
        $this->app->singleton('heweatherv2', function () use ($config) {
            return new HeWeatherV2Client($config['heweatherv2']);
        });
        $this->app->singleton('openweathermap', function () use ($config) {
            return new OpenWeatherMapClient($config['openweathermap']);
        });
    }
}