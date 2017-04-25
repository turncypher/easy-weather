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


class WeatherServiceProvider extends ServiceProvider
{
    public function register(){
        $config = config('weather');
        $this->app->singleton('seniverse', function() use($config){
            return new SeniverseClient($config['seniverse']);
        });
        $this->app->singleton('heweather', function() use($config){
            return new HeWeatherClient($config['heweather']);
        });
    }
}