<?php

namespace TurnCypher\Weather\Requests\HeweatherV2;

/**
 * Created by PhpStorm.
 * User: emmanuel
 * Date: 17-7-12
 * Time: 上午11:04
 */
class ForecastRequest extends Request
{


    /**
     * ForecastRequest constructor.
     * @param $params
     */
    public function __construct($params)
    {
        parent::__construct();
        $this->city = $params['city'];
        $this->lang = isset($params['lang']) ? $params['lang'] : 'zh_cn';
        $this->unit = isset($params['unit']) ? $params['unit'] : 'c';
    }
}