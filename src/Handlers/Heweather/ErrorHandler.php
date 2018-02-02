<?php
/**
 * Created by PhpStorm.
 * User: Emmanuel
 * Date: 2017/5/4
 * Time: 18:56
 */

namespace TurnCypher\Weather\Handlers\Heweather;

use GuzzleHttp\Psr7\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;

class ErrorHandler
{
    /**
     * @var Response
     */
    private $response;

    /**
     * ExceptionHandler constructor.
     * @param Response $response
     */
    public function __construct(Response $response)
    {
        $this->response = $response;
    }

    public function handle()
    {
        $status =  \GuzzleHttp\json_decode($this->response->getBody(), true);
        $code = $status['HeWeather5'][0]['status'];

        throw new HttpException(403, $code, null, [], config("weather.status.heweather.{$code}"));
    }
}