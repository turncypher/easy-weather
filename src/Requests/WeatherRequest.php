<?php
/**
 * Created by PhpStorm.
 * User: Emmanuel
 * Date: 2017/5/4
 * Time: 14:59
 */
namespace TurnCypher\Weather\Requests;

use Illuminate\Contracts\Support\Arrayable;

class WeatherRequest implements Arrayable
{

    /**
     * Get the instance as an array.
     *
     * @return array
     */
    public function toArray()
    {
        return get_object_vars($this);
    }
}