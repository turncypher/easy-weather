<?php
/**
 * Created by PhpStorm.
 * User: Emmanuel
 * Date: 2017/5/4
 * Time: 13:50
 */

namespace TurnCypher\Weather\Requests\HeweatherV2;

class NowRequest extends Request
{


    /**
     * NowRequest constructor.
     * @param $params
     * @internal param Request $request
     */
    public function __construct(array $params)
    {
        parent::__construct();
        $this->city = $params['city'];
        $this->lang = isset($params['lang']) ? $params['lang'] : 'zh_cn';
        $this->unit = isset($params['unit']) ? $params['unit'] : 'c';
    }
}