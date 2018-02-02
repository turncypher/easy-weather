<?php

namespace TurnCypher\Weather\Clients;

use Cache;
use Validator;
use Carbon\Carbon;
use GuzzleHttp\Client as Guzzle;
use Illuminate\Foundation\Bus\DispatchesJobs;
use TurnCypher\Weather\Requests\HeweatherV2\NowRequest;
use TurnCypher\Weather\Handlers\HeweatherV2\ErrorHandler;
use TurnCypher\Weather\Requests\HeweatherV2\ForecastRequest;
use TurnCypher\Weather\Exceptions\InvalidParameterException;
use TurnCypher\Weather\Transformers\HeweatherV2\NowTransformer;
use TurnCypher\Weather\Requests\HeweatherV2\IntegrationRequest;
use TurnCypher\Weather\Transformers\HeweatherV2\IntegrationTransformer;


class HeWeatherV2Client implements Client
{
    use DispatchesJobs;

    private $apiKey;
    private $guzzle;
    private $cacheKey;

    const API_NOW = 'now';
    const API_FORECAST = 'forecast';
    const API_SUGGESTION = 'suggestion';
    const API_INTEGRATION = 'weather';

    /**
     * HeWeather constructor.
     * @param $config
     */
    public function __construct($config)
    {
        $this->guzzle = new Guzzle(['base_uri' => $config['api_base_url']]);
        $this->apiKey = $config['api_key'];
        $this->cacheKey = $config['cache_key'];
    }

    //实时天气
    function now($params)
    {
        /**
         * @var \Illuminate\Validation\Validator $validator
         */

        $validator = Validator::make($params, [
            'city' => 'required',
            'location' => 'required',
        ], [
            'required' => 'parameter :attribute is required'
        ]);

        if (!isset($params['lang'])){
            $params['lang'] = 'zh_cn';
        }

        if ($validator->fails()) {
            throw  new InvalidParameterException(403, $validator->errors()->first());
        }
        $key = $this->cacheKey . ':now:' . $params['lang'] . ':' . md5($params['location']);
        $request = new NowRequest($params);
        if (!Cache::has($key)) {
            $response = $this->guzzle->request('GET', self::API_NOW, ['query' => $request->toArray()]);
            $now = \GuzzleHttp\json_decode($response->getBody(), true);
            if ($now['HeWeather5'][0]['status'] != 'ok') {
                $this->dispatch(new ErrorHandler($response));
            }
            $refresh = Carbon::now()->addSeconds(config('weather.refreshPeriod'));
            Cache::put($key, $now, $refresh);
        }


        return $this->dispatch(new NowTransformer(Cache::get($key)));
    }

    //天气查询
    function forecast($params)
    {
        $validator = Validator::make($params, [
            'city' => 'required',
            'location' => 'required',
        ], [
            'required' => 'parameter :attribute is required'
        ]);

        if ($validator->fails()) {
            throw  new InvalidParameterException(403, $validator->errors()->first());
        }

        if (!isset($params['lang'])){
            $params['lang'] = 'zh_cn';
        }

        $key = $this->cacheKey . ':forecast:' . $params['lang'] . ':' . md5($params['location']);
        $request = new ForecastRequest($params);
        if (!Cache::has($key)) {
            $response = $this->guzzle->request('GET', self::API_FORECAST, ['query' => $request->toArray()]);
            $forecast = \GuzzleHttp\json_decode($response->getBody(), true);
            if ($forecast['HeWeather5'][0]['status'] != 'ok') {
                $this->dispatch(new ErrorHandler($response));
            }
            $refresh = Carbon::now()->addSeconds(config('weather.refreshPeriod'));
            Cache::put($key, $forecast, $refresh);
        }
        return $this->dispatch(new ForecastTransformer(Cache::get($key), $request));
    }

    function suggestion($params)
    {
        $query = [
            'city' => $params['city'],
            'lang' => $params['lang'],
            'key' => $this->apiKey,
        ];
        $response = $this->guzzle->request('GET', self::API_SUGGESTION, ['query' => $query]);
        return $response->getBody();

    }

    function integration($params)
    {
        $validator = Validator::make($params, [
            'city' => 'required',
            'location' => 'required',
        ], [
            'required' => 'parameter :attribute is required'
        ]);
        if ($validator->fails()) {
            throw  new InvalidParameterException(403, $validator->errors()->first());
        }

        if (!isset($params['lang'])){
            $params['lang'] = 'zh_cn';
        }

        $key = $this->cacheKey . ':integration:' . $params['lang'] . ':' . md5($params['location']);
        $request = new IntegrationRequest($params);
        if (!Cache::has($key)) {
            $response = $this->guzzle->request('GET', self::API_INTEGRATION, ['query' => $request->toArray()]);
            $integration = \GuzzleHttp\json_decode($response->getBody(), true);
            if ($integration['HeWeather6'][0]['status'] != 'ok') {
                $this->dispatch(new ErrorHandler($response));
            }
            $refresh = Carbon::now()->addSeconds(config('weather.refreshPeriod'));
            Cache::put($key, $integration, $refresh);
        }
        return $this->dispatch(new IntegrationTransformer(Cache::get($key), $request));
    }
}
