<?php


namespace Morrios\Sms\Exception;

use Exception;

/**
 * Class MorriosException
 * @package Morrios\Sms\Exceptions
 */
abstract class MorriosException extends Exception
{
    protected $errorCode;

    protected $errorMessage;
}