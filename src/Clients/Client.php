<?php
/**
 * Created by PhpStorm.
 * User: emmanuel
 * Date: 17-4-25
 * Time: 上午12:13
 */
namespace TurnCypher\Weather\Clients;

interface Client
{
    function now($city);
    function forecast($city, $start, $days);
}