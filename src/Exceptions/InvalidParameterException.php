<?php
/**
 * Created by PhpStorm.
 * User: Emmanuel
 * Date: 2017/5/4
 * Time: 15:25
 */

namespace TurnCypher\Weather\Exceptions;

use Symfony\Component\HttpKernel\Exception\HttpException;

class InvalidParameterException extends HttpException
{
    protected $code = 10000000;
}